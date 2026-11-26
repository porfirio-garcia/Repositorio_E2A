<?php
declare(strict_types=1);

require_once __DIR__ . '/../../nucleo/comun.php';
require_once __DIR__ . '/includes/video.php';
require_once __DIR__ . '/includes/etiquetas.php';

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
foreach (['nombre', 'tipo_persona', 'carrera_id', 'testimonio'] as $campo) {
    $valor = $_POST[$campo] ?? null;
    $datos[$campo] = is_string($valor) ? trim($valor) : '';
}

function tes_error_subida(string $aviso, array $datos): never
{
    $_SESSION['tes_formulario'] = [
        'nombre' => mb_substr($datos['nombre'], 0, 100),
        'tipo_persona' => mb_substr($datos['tipo_persona'], 0, 10),
        'carrera_id' => mb_substr($datos['carrera_id'], 0, 10),
        'testimonio' => mb_substr($datos['testimonio'], 0, 3000),
        'etiquetas' => $datos['etiquetas'] ?? [],
    ];
    mensaje('error', $aviso);
    redirigir('formulario.php');
}

try {
    $etiquetasIds = tes_validar_etiquetas($_POST['etiquetas'] ?? []);
} catch (InvalidArgumentException $error) {
    tes_error_subida($error->getMessage(), $datos);
}
$datos['etiquetas'] = $etiquetasIds;
if ($etiquetasIds) {
    $marcadores = implode(',', array_fill(0, count($etiquetasIds), '?'));
    $validarEtiquetas = bd()->prepare("SELECT COUNT(*) FROM tes_etiquetas WHERE id IN ($marcadores)");
    $validarEtiquetas->execute($etiquetasIds);
    if ((int) $validarEtiquetas->fetchColumn() !== count($etiquetasIds)) {
        tes_error_subida('Una etiqueta seleccionada ya no existe. Actualiza el formulario.', $datos);
    }
}

$carreraId = entero($datos['carrera_id']);
$longitud = mb_strlen($datos['testimonio']);

if (!mb_check_encoding($datos, 'UTF-8')) {
    tes_error_subida('El texto del envío contiene caracteres no válidos.', $datos);
}
if ($datos['nombre'] === '' || mb_strlen($datos['nombre']) > 100) {
    tes_error_subida('Escribe un nombre de 1 a 100 caracteres.', $datos);
}
if (!in_array($datos['tipo_persona'], ['estudiante', 'egresado', 'familiar'], true)) {
    tes_error_subida('Selecciona un tipo de persona válido.', $datos);
}
if ($datos['tipo_persona'] !== 'familiar' && $carreraId === null) {
    tes_error_subida('Selecciona una carrera válida.', $datos);
}
if ($datos['tipo_persona'] === 'familiar' && $datos['carrera_id'] === '') {
    $carreraId = null;
}
if ($longitud < 20 || $longitud > 3000) {
    tes_error_subida('Escribe un testimonio de 20 a 3000 caracteres.', $datos);
}
if (($_POST['consentimiento'] ?? '') !== '1') {
    tes_error_subida('Confirma que aceptas la publicación de tu testimonio.', $datos);
}

if ($carreraId !== null) {
    $consulta = bd()->prepare(
        'SELECT id FROM carreras WHERE id = ? AND activa = 1'
    );

    $consulta->execute([$carreraId]);

    if (!$consulta->fetchColumn()) {
        tes_error_subida('La carrera seleccionada no está disponible.', $datos);
    }
} elseif ($datos['carrera_id'] !== '') {
    tes_error_subida('Selecciona una carrera válida.', $datos);
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
    $extension = tes_extension_video($mime);
    if ($extension === null) {
        throw new InvalidArgumentException('Puedes subir videos en ' . TES_FORMATOS_VIDEO_TEXTO . '.');
    }

    $archivo = bin2hex(random_bytes(16)) . '.' . $extension;
    if (!move_uploaded_file($video['tmp_name'], __DIR__ . '/video/' . $archivo)) {
        throw new RuntimeException('No fue posible guardar el video.');
    }

    return $archivo;
}

$archivo = null;
$conexion = bd();
try {
    $archivo = tes_guardar_video($_FILES['video'] ?? ['error' => UPLOAD_ERR_NO_FILE]);
    $conexion->beginTransaction();
    $consulta = $conexion->prepare(
        "INSERT INTO tes_testimonios
         (nombre, tipo_persona, carrera_id, testimonio, video_archivo, video_publico, estado)
         VALUES (?, ?, ?, ?, ?, ?, 'pendiente')"
    );
    $consulta->execute([
        $datos['nombre'],
        $datos['tipo_persona'],
        $carreraId,
        $datos['testimonio'],
        $archivo,
        $archivo !== null && ($_POST['consentimiento_video'] ?? null) === '1' ? 1 : 0,
    ]);

    $testimonioId = (int) $conexion->lastInsertId();
    if ($etiquetasIds) {
        $asociar = $conexion->prepare(
            'INSERT INTO tes_testimonio_etiqueta (testimonio_id, etiqueta_id) VALUES (?, ?)'
        );
        foreach ($etiquetasIds as $idEtiqueta) {
            $asociar->execute([$testimonioId, $idEtiqueta]);
        }
    }
    $conexion->commit();
} catch (InvalidArgumentException $error) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    tes_error_subida($error->getMessage(), $datos);
} catch (Throwable $error) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    if ($archivo !== null && !unlink(__DIR__ . '/video/' . $archivo)) {
        error_log('TES: No se pudo retirar un video después de un error al guardar el testimonio.');
    }
    error_log('TES: Error al guardar un testimonio: ' . $error->getMessage());
    tes_error_subida('No fue posible enviar el testimonio. Intenta nuevamente.', $datos);
}

mensaje('exito', 'Recibimos tu testimonio. Se publicará si el equipo de coordinación lo aprueba.');
redirigir('formulario.php');
