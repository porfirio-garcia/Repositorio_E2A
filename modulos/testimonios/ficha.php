<?php
declare(strict_types=1);

require_once __DIR__ . '/obtener_testimonios.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$testimonio = $id ? tes_obtener_publicado($id) : null;

if ($testimonio === null) {
    no_encontrado('El testimonio no está disponible.');
}

$etiquetas = tes_etiquetas_por_testimonio([$id])[$id] ?? [];

cabecera('Testimonio de ' . $testimonio['nombre'], '', 'css/listado.css');
?>

<section class="contenedor seccion">
    <a href="index.php">Volver a testimonios</a>
    <article class="ancho-lectura tes-ficha">
        <h1><?= e($testimonio['nombre']) ?></h1>
        <p class="leyenda">
            <?= e(ucfirst($testimonio['tipo_persona'])) ?> · <?= e($testimonio['carrera']) ?>
            <?= $testimonio['generacion'] ? ' · ' . e($testimonio['generacion']) : '' ?>
        </p>
        <p class="tes-texto-completo"><?= e($testimonio['testimonio']) ?></p>
        <?php if ($etiquetas): ?>
            <div class="tes-etiquetas">
                <?php foreach ($etiquetas as $etiqueta): ?>
                    <a class="insignia insignia--info" href="index.php?etiqueta=<?= (int) $etiqueta['id'] ?>">
                        <?= e($etiqueta['nombre']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </article>
</section>

<?php pie(); ?>
