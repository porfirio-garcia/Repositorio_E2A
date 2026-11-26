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

$id = $_POST['id'] ?? '';
$accion = $_POST['accion'] ?? '';
$motivo = $_POST['motivo'] ?? '';
$id = is_string($id) ? entero($id) : null;
$motivo = is_string($motivo) ? trim($motivo) : '';

if ($id === null || !in_array($accion, ['publicar', 'rechazar'], true)) {
    mensaje('error', 'El testimonio o la acción no son válidos.');
    redirigir('index.php');
}

if ($accion === 'publicar') {
    $estado = 'aprobado';
    $motivo = null;
} else {
    $estado = 'rechazado';
    if ($motivo === '' || mb_strlen($motivo) > 300) {
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
        "UPDATE tes_testimonios
         SET estado = ?, fecha_moderacion = NOW(), moderado_por = ?
         WHERE id = ? AND estado = 'pendiente'"
    );
    $actualizar->execute([$estado, $moderador, $id]);

    if ($actualizar->rowCount() !== 1) {
        $conexion->rollBack();
        mensaje('error', 'El testimonio no existe o ya fue moderado.');
        redirigir('index.php');
    }

    $historial = $conexion->prepare(
        "INSERT INTO tes_historial
         (testimonio_id, estado_previo, estado_nuevo, motivo, usuario_id)
         VALUES (?, 'pendiente', ?, ?, ?)"
    );
    $historial->execute([$id, $estado, $motivo, $moderador]);

    $conexion->commit();
    mensaje('exito', $estado === 'aprobado' ? 'Testimonio publicado.' : 'Testimonio rechazado.');
} catch (PDOException $error) {
    if ($conexion->inTransaction()) {
        $conexion->rollBack();
    }
    error_log('TES: Error al moderar un testimonio: ' . $error->getMessage());
    mensaje('error', 'No fue posible guardar la decisión.');
}

redirigir('index.php');
