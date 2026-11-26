<?php
require_once __DIR__ . '/../../../nucleo/comun.php';
requerir_rol('coordinacion');

$id = $_GET['id'] ?? '';
$id = is_string($id) ? entero($id) : null;
if ($id === null) {
    no_encontrado();
}

$consulta = bd()->prepare('SELECT video_archivo FROM tes_testimonios WHERE id = ?');
$consulta->execute([$id]);
$archivo = $consulta->fetchColumn();

if (!is_string($archivo) || !preg_match('/^[a-f0-9]{32}\.(mp4|webm)$/D', $archivo)) {
    no_encontrado('Este testimonio no tiene un video disponible.');
}

entregar_archivo(__DIR__ . '/../video/' . $archivo, $archivo);
