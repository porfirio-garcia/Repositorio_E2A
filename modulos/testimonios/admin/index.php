<?php
require_once __DIR__ . '/../../../nucleo/comun.php';
requerir_rol('coordinacion');
require_once __DIR__ . '/../includes/etiquetas.php';

$estados = [
    'pendiente' => 'Enviado',
    'en_revision' => 'En revisión',
    'aprobado' => 'Publicado',
    'rechazado' => 'Rechazado',
];
$filtro = $_GET['estado'] ?? '';
if (!is_string($filtro) || !isset($estados[$filtro])) {
    $filtro = '';
}

$consulta = bd()->prepare(
    "SELECT t.id, t.nombre, t.testimonio, t.estado, t.video_archivo, c.nombre AS carrera,
            (SELECT m.motivo FROM tes_moderacion AS m
             WHERE t.estado = 'rechazado' AND m.testimonio_id = t.id
               AND m.estado_nuevo = 'rechazado'
             ORDER BY m.id DESC LIMIT 1) AS motivo
     FROM tes_testimonios AS t
     LEFT JOIN carreras AS c ON c.id = t.carrera_id
     WHERE (? = '' OR t.estado = ?)
     ORDER BY t.creado_en DESC, t.id DESC"
);
$consulta->execute([$filtro, $filtro]);
$testimonios = $consulta->fetchAll();
$etiquetas = tes_etiquetas_por_testimonio(array_column($testimonios, 'id'));

cabecera('Panel de Moderación', '', '../css/admin.css');
?>

<section class="contenedor seccion">
    <p><a class="boton boton--secundario boton--pequeno" href="../index.php"><span aria-hidden="true">←</span> Ver testimonios públicos</a></p>
    <h1>Bandeja de Moderación TES</h1>
    <?php pintar_mensajes(); ?>
    <p><a class="boton boton--secundario" href="etiquetas.php">Administrar etiquetas</a></p>
    <form class="tes-filtro" method="GET" action="index.php">
        <label class="campo__etiqueta" for="estado">Filtrar por estado</label>
        <select class="campo__control" id="estado" name="estado">
            <option value="">Todos</option>
            <?php foreach ($estados as $valor => $etiqueta): ?>
                <option value="<?= e($valor) ?>" <?= $filtro === $valor ? 'selected' : '' ?>>
                    <?= e($etiqueta) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button class="boton boton--secundario" type="submit">Filtrar</button>
    </form>

    <?php if (!$testimonios): ?>
        <p>No hay testimonios para mostrar.</p>
    <?php else: ?>
        <div class="tabla-envoltura">
            <table class="tabla tes-tabla" role="table" aria-label="Testimonios">
                <thead role="rowgroup">
                    <tr role="row">
                        <th scope="col" role="columnheader">Nombre</th>
                        <th scope="col" role="columnheader">Carrera</th>
                        <th scope="col" role="columnheader">Testimonio</th>
                        <th scope="col" role="columnheader">Estado</th>
                        <th scope="col" role="columnheader">Etiquetas</th>
                        <th scope="col" role="columnheader">Acciones</th>
                    </tr>
                </thead>
                <tbody role="rowgroup">
                    <?php foreach ($testimonios as $t): ?>
                        <tr role="row">
                            <td role="cell"><span class="tes-etiqueta" aria-hidden="true">Nombre</span><?= e($t['nombre']) ?></td>
                            <td role="cell"><span class="tes-etiqueta" aria-hidden="true">Carrera</span><?= e($t['carrera'] ?? 'No especificada') ?></td>
                            <td role="cell" class="tes-texto">
                                <span class="tes-etiqueta" aria-hidden="true">Testimonio</span>
                                <?= e($t['testimonio']) ?>
                                <?php if ($t['video_archivo']): ?>
                                    <a class="tes-video" href="video.php?id=<?= (int) $t['id'] ?>">Descargar video</a>
                                <?php endif; ?>
                            </td>
                            <td role="cell" class="tes-texto">
                                <span class="tes-etiqueta" aria-hidden="true">Estado</span>
                                <span class="insignia"><?= e($estados[$t['estado']] ?? $t['estado']) ?></span>
                                <?php if ($t['motivo'] !== null): ?>
                                    <p class="tes-motivo"><strong>Motivo:</strong> <?= e($t['motivo']) ?></p>
                                <?php endif; ?>
                            </td>
                            <td role="cell">
                                <span class="tes-etiqueta" aria-hidden="true">Etiquetas</span>
                                <?php foreach ($etiquetas[$t['id']] ?? [] as $tag): ?>
                                    <span class="insignia insignia--info"><?= e($tag['nombre']) ?></span>
                                <?php endforeach; ?>
                                <a class="tes-editar-enlace" href="etiquetas.php?testimonio_id=<?= (int) $t['id'] ?>">
                                    Editar etiquetas
                                </a>
                            </td>
                            <td role="cell" class="tes-acciones">
                                <span class="tes-etiqueta" aria-hidden="true">Acciones</span>
                                <?php if ($t['estado'] === 'pendiente'): ?>
                                    <form method="POST" action="actualizar_estado.php">
                                        <?= campo_token() ?>
                                        <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                                        <button class="boton boton--primario boton--pequeno"
                                                type="submit" name="accion" value="revisar">Iniciar revisión</button>
                                    </form>
                                <?php elseif ($t['estado'] === 'en_revision'): ?>
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
                                <?php endif; ?>
                                <form method="POST" action="eliminar_testimonio.php" class="mt-4 form-eliminar">
                                    <?= campo_token() ?>
                                    <input type="hidden" name="id" value="<?= (int) $t['id'] ?>">
                                    <button class="boton boton--peligro boton--pequeno" type="submit">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<script src="../assets/js/tes.js" defer></script>
<?php pie();
