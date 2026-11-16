<?php

declare(strict_types=1);

require_once __DIR__ . '/../../nucleo/comun.php';

requerir_rol('coordinacion');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    http_response_code(405);
    exit('Método no permitido.');
}

if (!is_string($_POST['token'] ?? null)) {
    http_response_code(400);
    exit('Petición no válida.');
}

verificar_token();

$idRecibido = $_POST['id'] ?? null;
$id = is_string($idRecibido) ? entero(trim($idRecibido)) : null;

if ($id === null) {
    mensaje('error', 'Indica un identificador válido.');
    redirigir('admin.php');
}

try {
    $consulta = bd()->prepare('DELETE FROM tes_testimonios WHERE id = ?');
    $consulta->bindValue(1, $id, PDO::PARAM_INT);
    $consulta->execute();

    if ($consulta->rowCount() === 1) {
        mensaje('exito', 'Testimonio eliminado.');
    } else {
        mensaje('error', 'El testimonio ya no existe.');
    }
} catch (PDOException $error) {
    error_log('Error al eliminar un testimonio: ' . $error->getMessage());
    mensaje('error', 'No se pudo eliminar el testimonio.');
}

redirigir('admin.php');