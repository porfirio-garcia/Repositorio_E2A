<?php
// 1. Traemos el núcleo (seguridad, conexión a base de datos, diseño).
require_once __DIR__ . '/../../../nucleo/comun.php';

// 2. Seguridad: Solo el rol de coordinador/moderador puede entrar aquí.
requerir_rol('coordinacion'); 

// 3. Imprime la cabecera HTML (el diseño).
cabecera('Panel de Moderación', 'admin', 'admin.css');

// 4. Conectamos a la base de datos y traemos TODOS los testimonios.
$conexion = bd();
$stmt = $conexion->query("SELECT * FROM tes_testimonios ORDER BY fecha_creacion DESC");
$testimonios = $stmt->fetchAll();
?>

<main class="contenedor">
    <h1>Bandeja de Moderación TES</h1>
    
    <!-- Muestra mensajes de éxito o error -->
    <?php pintar_mensajes(); ?>

    <table class="tabla-admin">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Testimonio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Recorremos cada testimonio uno por uno -->
            <?php foreach ($testimonios as $t): ?>
            <tr>
                <!-- e() significa "escapar", evita que hackers inyecten código -->
                <td><?= e($t['nombre']) ?></td>
                <td><?= e($t['carrera']) ?></td>
                <td><?= e($t['testimonio']) ?></td>
                <td><span class="badge"><?= e($t['estado']) ?></span></td>
                <td>
                    <!-- Solo mostramos botones si está pendiente -->
                    <?php if ($t['estado'] === 'pendiente'): ?>
                        
                        <!-- FORMULARIO 1: Publicar -->
                        <form method="POST" action="actualizar_estado.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                            <button type="submit" name="accion" value="publicar">Publicar</button>
                        </form>

                        <!-- FORMULARIO 2: Rechazar (Requiere un motivo) -->
                        <form method="POST" action="actualizar_estado.php" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                            <!-- Esta es tu tarea: la caja de texto obligatoria -->
                            <input type="text" name="motivo" placeholder="Escribe el motivo" required>
                            <button type="submit" name="accion" value="rechazar">Rechazar</button>
                        </form>

                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php 
// Imprime el pie de página HTML.
pie(); 
?>