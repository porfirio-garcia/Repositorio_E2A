<?php
require_once __DIR__ . '/../../../nucleo/comun.php';
requerir_rol('coordinacion');

$consulta = bd()->prepare(
    'SELECT t.id, t.nombre, t.testimonio, t.estado, c.nombre AS carrera
     FROM tes_testimonios AS t
     JOIN carreras AS c ON c.id = t.carrera_id
     ORDER BY t.creado_en DESC, t.id DESC'
);
$consulta->execute();
$testimonios = $consulta->fetchAll();

cabecera('Panel de Moderación');
?>

<section class="contenedor seccion">
    <h1>Bandeja de Moderación TES</h1>
    <?php pintar_mensajes(); ?>

    <?php if (!$testimonios): ?>
        <p>Aún no hay testimonios.</p>
    <?php else: ?>
        <div class="tabla-envoltura">
            <table class="tabla">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Carrera</th>
                        <th scope="col">Testimonio</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($testimonios as $t): ?>
                        <tr>
                            <td><?= e($t['nombre']) ?></td>
                            <td><?= e($t['carrera']) ?></td>
                            <td><?= e($t['testimonio']) ?></td>
                            <td><?= e(ucfirst($t['estado'])) ?></td>
                            <td>
                                <?php if ($t['estado'] === 'pendiente'): ?>
                                    <form method="POST" action="actualizar_estado.php">
                                        <?= campo_token() ?>
                                        <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                                        <button class="boton boton--primario boton--pequeno"
                                                type="submit" name="accion" value="publicar">Publicar</button>
                                    </form>
                                    <form method="POST" action="actualizar_estado.php" class="mt-4">
                                        <?= campo_token() ?>
                                        <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                                        <label for="motivo-<?= (int) $t['id'] ?>">Motivo de rechazo</label>
                                        <input class="campo__control" id="motivo-<?= (int) $t['id'] ?>"
                                               type="text" name="motivo" maxlength="300" required>
                                        <button class="boton boton--peligro boton--pequeno"
                                                type="submit" name="accion" value="rechazar">Rechazar</button>
                                    </form>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php pie();
