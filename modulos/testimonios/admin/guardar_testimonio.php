<?php
declare(strict_types=1);

/**
 * MODULO TES - Guardado de testimonios
 * Explorador Vocacional UTBB
 */

require_once __DIR__ . '/../../../nucleo/comun.php';

// Solo pueden subir testimonios los aspirantes y coordinación.
requerir_rol('aspirante', 'coordinacion');
// Detectar si la petición supera el límite de carga configurado en PHP.
$limitePost = trim((string) ini_get('post_max_size'));
$unidadPost = strtolower(substr($limitePost, -1));
$valorPost = (float) $limitePost;

$multiplicadorPost = match ($unidadPost) {
    'g' => 1024 ** 3,
    'm' => 1024 ** 2,
    'k' => 1024,
    default => 1
};

$limitePostBytes = (int) ($valorPost * $multiplicadorPost);
$tamanoPeticion = (int) ($_SERVER['CONTENT_LENGTH'] ?? 0);

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    $limitePostBytes > 0 &&
    $tamanoPeticion > $limitePostBytes
) {
    mensaje(
        'error',
        'El archivo es demasiado grande para el servidor. El tamaño total de la carga supera el límite permitido por PHP.'
    );
    redirigir('formulario.php');
}

// Esta operación solamente acepta POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('formulario.php');
    exit;
}

// Verificar protección CSRF.
verificar_token();

/**
 * Regresa al formulario mostrando un mensaje de error
 * y conserva los datos escritos.
 */
function tes_error_subida(string $mensajeError): never
{
    // Conservar los datos escritos para recuperarlos en el formulario.
    $_SESSION['tes_formulario'] = [
        'nombre' => is_string($_POST['nombre'] ?? null)
            ? trim($_POST['nombre'])
            : '',
        'tipo_persona' => is_string($_POST['tipo_persona'] ?? null)
            ? trim($_POST['tipo_persona'])
            : '',
        'carrera_id' => is_string($_POST['carrera_id'] ?? null)
            ? trim($_POST['carrera_id'])
            : '',
        'generacion' => is_string($_POST['generacion'] ?? null)
            ? trim($_POST['generacion'])
            : '',
        'testimonio' => is_string($_POST['testimonio'] ?? null)
            ? trim($_POST['testimonio'])
            : ''
    ];

    mensaje('error', $mensajeError);
    redirigir('formulario.php');
}

// --------------------------------------------------
// 1. RECIBIR Y VALIDAR LOS DATOS
// --------------------------------------------------

$nombre = isset($_POST['nombre']) && is_string($_POST['nombre'])
    ? trim($_POST['nombre'])
    : '';

$tipoPersona = isset($_POST['tipo_persona']) && is_string($_POST['tipo_persona'])
    ? trim($_POST['tipo_persona'])
    : '';

$carreraId = filter_var(
    $_POST['carrera_id'] ?? null,
    FILTER_VALIDATE_INT
);

$generacion = isset($_POST['generacion']) && is_string($_POST['generacion'])
    ? trim($_POST['generacion'])
    : '';

$testimonio = isset($_POST['testimonio']) && is_string($_POST['testimonio'])
    ? trim($_POST['testimonio'])
    : '';

if ($nombre === '' || mb_strlen($nombre, 'UTF-8') > 100) {
    tes_error_subida('El nombre es obligatorio y no debe superar los 100 caracteres.');
}

if (!in_array($tipoPersona, ['estudiante', 'egresado'], true)) {
    tes_error_subida('Selecciona un tipo de persona válido.');
}

if ($carreraId === false || $carreraId === null || $carreraId <= 0) {
    tes_error_subida('Selecciona una carrera válida.');
}

if (mb_strlen($generacion, 'UTF-8') > 20) {
    tes_error_subida('La generación no debe superar los 20 caracteres.');
}

if ($testimonio === '' || strlen($testimonio) > 60000) {
    tes_error_subida('El testimonio es obligatorio o supera el tamaño permitido.');
}

// Comprobar que la carrera exista y esté activa.
$consultaCarrera = bd()->prepare(
    'SELECT id FROM carreras
     WHERE id = :id AND activa = 1
     LIMIT 1'
);

$consultaCarrera->execute([
    ':id' => $carreraId
]);

if (!$consultaCarrera->fetch()) {
    tes_error_subida('La carrera seleccionada no está disponible.');
}

// --------------------------------------------------
// 2. VALIDAR EL VIDEO
// --------------------------------------------------

if (!isset($_FILES['video']) || !is_array($_FILES['video'])) {
    tes_error_subida('Debes seleccionar un video.');
}

$video = $_FILES['video'];

if ($video['error'] !== UPLOAD_ERR_OK) {
    $erroresVideo = [
        UPLOAD_ERR_INI_SIZE =>
            'El video supera el límite de carga configurado en PHP.',
        UPLOAD_ERR_FORM_SIZE =>
            'El video supera el tamaño permitido.',
        UPLOAD_ERR_PARTIAL =>
            'El video se recibió incompleto. Intenta nuevamente.',
        UPLOAD_ERR_NO_FILE =>
            'Debes seleccionar un video.',
        UPLOAD_ERR_NO_TMP_DIR =>
            'No se encontró la carpeta temporal de carga.',
        UPLOAD_ERR_CANT_WRITE =>
            'No fue posible guardar el archivo temporal.',
        UPLOAD_ERR_EXTENSION =>
            'PHP detuvo la carga del video.'
    ];

    tes_error_subida(
        $erroresVideo[$video['error']] ?? 'Ocurrió un error al recibir el video.'
    );
}

if (
    !isset($video['tmp_name'], $video['size'], $video['name']) ||
    !is_uploaded_file($video['tmp_name'])
) {
    tes_error_subida('El archivo recibido no es válido.');
}

// Límite de 35 MB.
$limiteVideo = 35 * 1024 * 1024;

if ($video['size'] <= 0 || $video['size'] > $limiteVideo) {
    tes_error_subida('El video debe pesar más de 0 y máximo 35 MB.');
}

// Comprobar extensión.
$extension = strtolower(
    pathinfo($video['name'], PATHINFO_EXTENSION)
);

$extensionesPermitidas = ['mp4', 'webm'];

if (!in_array($extension, $extensionesPermitidas, true)) {
    tes_error_subida('Solo se permiten videos MP4 y WebM.');
}

// Comprobar MIME real del archivo.
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($video['tmp_name']);

$mimesPermitidos = [
    'mp4'  => 'video/mp4',
    'webm' => 'video/webm'
];

$mimeValido = isset($mimesPermitidos[$extension])
    && $mime === $mimesPermitidos[$extension];

// Algunos sistemas no reconocen ciertos MP4 y devuelven
// application/octet-stream. En ese caso verificamos su estructura.
if ($extension === 'mp4' && $mime === 'application/octet-stream') {

    $archivo = fopen($video['tmp_name'], 'rb');

    if ($archivo !== false) {
        $cabecera = fread($archivo, 16);
        fclose($archivo);

        // Tamaño del átomo ftyp (los primeros 4 bytes).
        $datosTamano = unpack('N', substr($cabecera, 0, 4));
        $tamanoFtyp = $datosTamano[1] ?? 0;

        // Identificador del contenedor MP4.
        $esFtyp = substr($cabecera, 4, 4) === 'ftyp';

        // Marca principal del contenedor.
        $marca = substr($cabecera, 8, 4);

        $marcasMP4 = [
            'isom', 'iso2', 'iso3', 'iso4',
            'iso5', 'iso6', 'mp41', 'mp42',
            'avc1', 'M4V '
        ];

        $estructuraMP4Valida =
            $esFtyp &&
            $tamanoFtyp >= 16 &&
            $tamanoFtyp <= $video['size'] &&
            in_array($marca, $marcasMP4, true);

        if ($estructuraMP4Valida) {
            $mimeValido = true;
        }
    }
}

if (!$mimeValido) {
    tes_error_subida(
        'El formato real del video no coincide con su extensión.'
    );
}

// --------------------------------------------------
// 3. GUARDAR EL VIDEO EN EL SERVIDOR
// --------------------------------------------------

$carpetaVideos = __DIR__ . '/../video';

if (!is_dir($carpetaVideos) || !is_writable($carpetaVideos)) {
    error_log('TES: La carpeta de videos no existe o no tiene permisos de escritura.');
    tes_error_subida('No fue posible almacenar el video. Intenta más tarde.');
}

// Nombre aleatorio para evitar colisiones y nombres manipulados.
try {
    $nombreArchivo = bin2hex(random_bytes(16)) . '.' . $extension;
} catch (Throwable $error) {
    error_log('TES: No fue posible generar el nombre del video.');
    tes_error_subida('No fue posible procesar el video. Intenta más tarde.');
}

$rutaVideo = $carpetaVideos . DIRECTORY_SEPARATOR . $nombreArchivo;

if (!move_uploaded_file($video['tmp_name'], $rutaVideo)) {
    tes_error_subida('No fue posible guardar el video. Intenta nuevamente.');
}

// --------------------------------------------------
// 4. INSERTAR EL TESTIMONIO EN LA BASE DE DATOS
// --------------------------------------------------

try {
    $sql = 'INSERT INTO tes_testimonios
        (
            nombre,
            tipo_persona,
            carrera_id,
            generacion,
            testimonio,
            video_archivo,
            estado
        )
        VALUES
        (
            :nombre,
            :tipo_persona,
            :carrera_id,
            :generacion,
            :testimonio,
            :video_archivo,
            \'pendiente\'
        )';

    $consulta = bd()->prepare($sql);

    $consulta->execute([
        ':nombre'        => $nombre,
        ':tipo_persona'  => $tipoPersona,
        ':carrera_id'    => $carreraId,
        ':generacion'    => $generacion !== '' ? $generacion : null,
        ':testimonio'    => $testimonio,
        ':video_archivo' => $nombreArchivo
    ]);

} catch (Throwable $error) {

    // Registrar el error técnico sin mostrar detalles al usuario.
    error_log('TES: Error al registrar un testimonio: ' . $error->getMessage());

    // Evitar dejar videos huérfanos si falla el INSERT.
if (is_file($rutaVideo) && !unlink($rutaVideo)) {
    error_log('TES: No fue posible eliminar el video huérfano: ' . $rutaVideo);

    }

    tes_error_subida(
        'No fue posible registrar el testimonio. Intenta nuevamente.'
    );
}

// --------------------------------------------------
// 5. FINALIZAR
// --------------------------------------------------

mensaje(
    'exito',
    'El testimonio se registró correctamente y quedó pendiente de moderación.'
);

redirigir('formulario.php');
exit;
