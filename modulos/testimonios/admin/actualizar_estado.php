<?php
require_once __DIR__ . '/../../../nucleo/comun.php';
requerir_rol('coordinacion');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('index.php');
}

if (!is_string($_POST['token'] ?? null)) {
    http_response_code(400);
    exit('Petición no válida.');
}
verificar_token();

$idRecibido = $_POST['id'] ?? null;
$id = is_string($idRecibido) ? entero($idRecibido) : null;
$accion = $_POST['accion'] ?? null;
$transicion = match ($accion) {
    'revisar' => ['pendiente', 'en_revision', 'Testimonio marcado en revisión.'],
    'publicar' => ['en_revision', 'aprobado', 'Testimonio publicado.'],
    'rechazar' => ['en_revision', 'rechazado', 'Testimonio rechazado.'],
    default => null,
};

if ($id === null || $transicion === null) {
    mensaje('error', 'El testimonio o la acción no son válidos.');
    redirigir('index.php');
}

[$estadoPrevio, $estadoNuevo, $confirmacion] = $transicion;
$motivo = null;
if ($accion === 'rechazar') {
    $texto = $_POST['motivo'] ?? null;
    if (is_string($texto)) {
        $motivo = preg_replace('/\A[\p{Z}\s]+|[\p{Z}\s]+\z/u', '', $texto);
    }
    if ($motivo === null || $motivo === '' || mb_strlen($motivo) > 300) {
        mensaje('error', 'Escribe un motivo de rechazo de 1 a 300 caracteres.');
        redirigir('index.php');
    }
}

$conexion = bd();
$moderador = (int) usuario()['id'];

try {
    // El cambio de estado y su historial se guardan juntos.
    $conexion->beginTransaction();

    $actualizar = $conexion->prepare(
        'UPDATE tes_testimonios
         SET estado = ?, fecha_moderacion = NOW(), moderado_por = ?
         WHERE id = ? AND estado = ?'
    );
    $actualizar->execute([$estadoNuevo, $moderador, $id, $estadoPrevio]);

    if ($actualizar->rowCount() !== 1) {
        $conexion->rollBack();
        mensaje('error', 'El testimonio no existe o su estado actual no permite esta acción.');
        redirigir('index.php');
    }

    $historial = $conexion->prepare(
        'INSERT INTO tes_moderacion
         (testimonio_id, estado_previo, estado_nuevo, motivo, usuario_id)
         VALUES (?, ?, ?, ?, ?)'
    );
    $historial->execute([$id, $estadoPrevio, $estadoNuevo, $motivo, $moderador]);

    $conexion->commit();
    mensaje('exito', $confirmacion);
} catch (PDOException $error) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    error_log('TES: Error al moderar un testimonio: ' . $error->getMessage());
    mensaje('error', 'No fue posible guardar la decisión.');
}

redirigir('index.php');
