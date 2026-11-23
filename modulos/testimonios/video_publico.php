<?php
declare(strict_types=1);

require_once __DIR__ . '/../../nucleo/comun.php';

// El archivo físico permanece protegido: únicamente se entrega con
// consentimiento expreso y si el testimonio fue publicado.
if (!in_array($_SERVER['REQUEST_METHOD'] ?? '', ['GET', 'HEAD'], true)) {
    http_response_code(405);
    header('Allow: GET, HEAD');
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) {
    no_encontrado('Video no disponible.');
}

$consulta = bd()->prepare(
    "SELECT video_archivo FROM tes_testimonios
     WHERE id = ? AND estado = 'aprobado' AND video_publico = 1"
);
$consulta->execute([$id]);
$archivo = $consulta->fetchColumn();

if (!is_string($archivo) || !preg_match('/\\A[a-f0-9]{32}\\.(?:mp4|webm)\\z/D', $archivo)) {
    no_encontrado('Video no disponible.');
}

$ruta = __DIR__ . '/video/' . $archivo;
if (!is_file($ruta) || !is_readable($ruta)) {
    no_encontrado('El archivo de video no está disponible.');
}

$tamano = filesize($ruta);
if ($tamano === false || $tamano === 0) {
    no_encontrado('El video está vacío.');
}

$inicio = 0;
$fin = $tamano - 1;
$rango = $_SERVER['HTTP_RANGE'] ?? '';
if ($rango !== '') {
    if (!is_string($rango) || !preg_match('/\\Abytes=(\\d*)-(\\d*)\\z/D', $rango, $m)
        || ($m[1] === '' && $m[2] === '')) {
        header("Content-Range: bytes */{$tamano}");
        http_response_code(416);
        exit;
    }

    if ($m[1] === '') {
        $longitud = (int) $m[2];
        $inicio = max(0, $tamano - $longitud);
    } else {
        $inicio = (int) $m[1];
        if ($m[2] !== '') {
            $fin = min($fin, (int) $m[2]);
        }
    }

    if ($inicio > $fin || $inicio >= $tamano || ($m[1] === '' && (int) $m[2] === 0)) {
        header("Content-Range: bytes */{$tamano}");
        http_response_code(416);
        exit;
    }
    http_response_code(206);
    header("Content-Range: bytes {$inicio}-{$fin}/{$tamano}");
}

$extension = pathinfo($archivo, PATHINFO_EXTENSION);
header('Content-Type: ' . ($extension === 'webm' ? 'video/webm' : 'video/mp4'));
header('Content-Disposition: inline; filename="testimonio.' . $extension . '"');
header('Content-Length: ' . ($fin - $inicio + 1));
header('Accept-Ranges: bytes');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');

if ($_SERVER['REQUEST_METHOD'] === 'HEAD') {
    exit;
}

$flujo = fopen($ruta, 'rb');
if ($flujo === false) {
    exit;
}
fseek($flujo, $inicio);
$restante = $fin - $inicio + 1;
while ($restante > 0 && !feof($flujo) && !connection_aborted()) {
    $fragmento = fread($flujo, min(65536, $restante));
    if ($fragmento === false || $fragmento === '') {
        break;
    }
    echo $fragmento;
    $restante -= strlen($fragmento);
    flush();
}
fclose($flujo);
