<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../nucleo/comun.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    http_response_code(307);
    redirigir('../guardar_testimonio.php');
}

redirigir('../formulario.php');
