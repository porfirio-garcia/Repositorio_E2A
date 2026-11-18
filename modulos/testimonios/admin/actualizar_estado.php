<?php
require_once __DIR__ . '/../../../nucleo/comun.php';
requerir_rol('coordinacion'); 

// Si alguien intenta abrir este archivo directo desde el navegador, lo regresamos.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('index.php');
}

// Obtenemos los datos que envió el formulario
$id = entero(post('id')); 
$accion = post('accion'); 
$motivo = post('motivo'); 

// Validamos que el ID sea correcto
if ($id <= 0) {
    mensaje('error', 'ID inválido.');
    redirigir('index.php');
}

$conexion = bd();

if ($accion === 'publicar') {
    // Actualizamos el estado a publicado
    $stmt = $conexion->prepare("UPDATE tes_testimonios SET estado = 'publicado' WHERE id = ?");
    $stmt->execute([$id]);
    mensaje('exito', 'Testimonio publicado.');
    
} elseif ($accion === 'rechazar') {
    
    // TU TAREA: Validar que el motivo no esté vacío.
    if (empty(trim($motivo))) {
        mensaje('error', '¡El motivo de rechazo es obligatorio!');
        redirigir('index.php');
    }
    
    // Actualizamos el estado y guardamos el motivo en la base de datos
    $stmt = $conexion->prepare("UPDATE tes_testimonios SET estado = 'rechazado', motivo = ? WHERE id = ?");
    $stmt->execute([$motivo, $id]);
    mensaje('exito', 'Testimonio rechazado y motivo guardado.');
}

// Regresamos al panel
redirigir('index.php');
?>