<?php
declare(strict_types=1);

require_once __DIR__ . '/obtener_testimonios.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$testimonio = $id ? tes_obtener_publicado($id) : null;

if ($testimonio === null) {
    no_encontrado('El testimonio no está disponible.');
}

$etiquetas = tes_etiquetas_por_testimonio([$id])[$id] ?? [];

cabecera('Testimonio de ' . $testimonio['nombre'], '', 'css/listado.css?v=' . filemtime(__DIR__ . '/css/listado.css'));
?>

<section class="contenedor seccion">
    <p><a class="boton boton--secundario boton--pequeno" href="index.php"><span aria-hidden="true">←</span> Volver a testimonios</a></p>
    <article class="ancho-lectura tes-ficha">
        <h1><?= e($testimonio['nombre']) ?></h1>
        <p class="leyenda">
            <?= e(ucfirst($testimonio['tipo_persona'])) ?>

<?php if (!empty($testimonio['carrera'])): ?>
    · <?= e($testimonio['carrera']) ?>
<?php endif; ?>

<?php if (!empty($testimonio['generacion'])): ?>
    · <?= e($testimonio['generacion']) ?>
<?php endif; ?>
        </p>
        <p class="tes-texto-completo"><?= e($testimonio['testimonio']) ?></p>
        <?php if ((int) $testimonio['video_publico'] === 1): ?>
            <section class="tes-video-publico" aria-label="Video del testimonio">
                <h2>Video del testimonio</h2>
                <video controls playsinline preload="metadata" aria-label="<?= e('Video del testimonio de ' . $testimonio['nombre']) ?>">
                    <source src="video_publico.php?id=<?= (int) $testimonio['id'] ?>">
                    Tu navegador no admite la reproducción de este video.
                </video>
            </section>
        <?php endif; ?>
        <?php if ($etiquetas): ?>
            <div class="tes-etiquetas">
                <?php foreach ($etiquetas as $etiqueta): ?>
                    <span class="insignia insignia--info">
                        <?= e($etiqueta['nombre']) ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </article>
</section>

<?php require __DIR__ . '/includes/cta.php'; ?>
<?php pie(); ?>
