<?php
// 1. Conectamos con la base de datos usando la función del núcleo
require_once __DIR__ . '/../../nucleo/comun.php';

// 2. Usamos la función bd() para obtener la conexión
$conexion = bd();

// 3. Consulta SQL para traer SOLO los aprobados, del más nuevo al más viejo
$sql = "SELECT nombre, tipo_persona, carrera_id, generacion, testimonio 
        FROM tes_testimonios 
        WHERE estado = 'aprobado' 
        ORDER BY creado_en DESC, id DESC";
        
$resultado = $conexion->query($sql);
$testimonios = [];

if ($resultado) {
    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
        $testimonios[] = $fila;
    }
}
?>