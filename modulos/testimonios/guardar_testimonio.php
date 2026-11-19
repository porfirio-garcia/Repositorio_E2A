<?php
declare(strict_types=1);

require_once __DIR__ . '/../../nucleo/comun.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    redirigir('formulario.php');
}

// PHP descarta POST y FILES cuando se supera post_max_size.
$limitePost = trim((string) ini_get('post_max_size'));
$multiplicador = match (strtolower(substr($limitePost, -1))) {
    'g' => 1024 ** 3,
    'm' => 1024 ** 2,
    'k' => 1024,
    default => 1,
};
$limitePostBytes = (float) $limitePost * $multiplicador;
if ($limitePostBytes > 0 && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > $limitePostBytes) {
    mensaje('error', 'El envío supera el tamaño permitido por el servidor.');
    redirigir('formulario.php');
}

if (!is_string($_POST['token'] ?? null)) {
    http_response_code(400);
    exit('Petición no válida.');
}
verificar_token();

$datos = [];
foreach (['nombre', 'tipo_persona', 'carrera_id', 'generacion', 'testimonio'] as $campo) {
    $valor = $_POST[$campo] ?? null;
    $datos[$campo] = is_string($valor) ? trim($valor) : '';
}

function tes_error_subida(string $aviso, array $datos): never
{
    $_SESSION['tes_formulario'] = [
        'nombre' => mb_substr($datos['nombre'], 0, 100),
        'tipo_persona' => mb_substr($datos['tipo_persona'], 0, 10),
        'carrera_id' => mb_substr($datos['carrera_id'], 0, 10),
        'generacion' => mb_substr($datos['generacion'], 0, 20),
        'testimonio' => mb_substr($datos['testimonio'], 0, 3000),
    ];
    mensaje('error', $aviso);
    redirigir('formulario.php');
}

$carreraId = entero($datos['carrera_id']);
$longitud = mb_strlen($datos['testimonio']);

if (!mb_check_encoding($datos, 'UTF-8')) {
    tes_error_subida('El texto del envío contiene caracteres no válidos.', $datos);
}
if ($datos['nombre'] === '' || mb_strlen($datos['nombre']) > 100) {
    tes_error_subida('Escribe un nombre de 1 a 100 caracteres.', $datos);
}
if (!in_array($datos['tipo_persona'], ['estudiante', 'egresado'], true)) {
    tes_error_subida('Selecciona un tipo de persona válido.', $datos);
}
if ($carreraId === null) {
    tes_error_subida('Selecciona una carrera válida.', $datos);
}
if (mb_strlen($datos['generacion']) > 20) {
    tes_error_subida('La generación admite hasta 20 caracteres.', $datos);
}
if ($longitud < 20 || $longitud > 3000) {
    tes_error_subida('Escribe un testimonio de 20 a 3000 caracteres.', $datos);
}
if (($_POST['consentimiento'] ?? '') !== '1') {
    tes_error_subida('Confirma que aceptas la publicación de tu testimonio.', $datos);
}

$consulta = bd()->prepare('SELECT id FROM carreras WHERE id = ? AND activa = 1');
$consulta->execute([$carreraId]);
if (!$consulta->fetchColumn()) {
    tes_error_subida('La carrera seleccionada no está disponible.', $datos);
}

/** Guarda el video opcional con extensión generada a partir del tipo real. */
function tes_guardar_video(array $video): ?string
{
    $error = $video['error'] ?? null;
    if ($error === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) {
        throw new InvalidArgumentException('El video supera el tamaño permitido por el servidor.');
    }
    if ($error !== UPLOAD_ERR_OK || !is_string($video['tmp_name'] ?? null)
        || !is_uploaded_file($video['tmp_name']) || !is_int($video['size'] ?? null)) {
        throw new InvalidArgumentException('No fue posible recibir el video. Vuelve a seleccionarlo.');
    }
    if ($video['size'] < 1 || $video['size'] > 35 * 1024 * 1024) {
        throw new InvalidArgumentException('Sube un video de hasta 35 MB.');
    }

    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($video['tmp_name']);
    $extensiones = ['video/mp4' => 'mp4', 'video/webm' => 'webm'];
    if (!isset($extensiones[$mime])) {
        throw new InvalidArgumentException('Sube un video en formato MP4 o WebM.');
    }

    $archivo = bin2hex(random_bytes(16)) . '.' . $extensiones[$mime];
    if (!move_uploaded_file($video['tmp_name'], __DIR__ . '/video/' . $archivo)) {
        throw new RuntimeException('No fue posible guardar el video.');
    }

    return $archivo;
}

$archivo = null;
try {
    $archivo = tes_guardar_video($_FILES['video'] ?? ['error' => UPLOAD_ERR_NO_FILE]);
    $consulta = bd()->prepare(
        "INSERT INTO tes_testimonios
         (nombre, tipo_persona, carrera_id, generacion, testimonio, video_archivo, estado)
         VALUES (?, ?, ?, ?, ?, ?, 'pendiente')"
    );
    $consulta->execute([
        $datos['nombre'],
        $datos['tipo_persona'],
        $carreraId,
        $datos['generacion'] !== '' ? $datos['generacion'] : null,
        $datos['testimonio'],
        $archivo,
    ]);
} catch (InvalidArgumentException $error) {
    tes_error_subida($error->getMessage(), $datos);
} catch (Throwable $error) {
    if ($archivo !== null && !unlink(__DIR__ . '/video/' . $archivo)) {
        error_log('TES: No se pudo retirar un video después de un error al guardar el testimonio.');
    }
    error_log('TES: Error al guardar un testimonio: ' . $error->getMessage());
    tes_error_subida('No fue posible enviar el testimonio. Intenta nuevamente.', $datos);
}

mensaje('exito', 'Recibimos tu testimonio. Se publicará si el equipo de coordinación lo aprueba.');
redirigir('formulario.php');
