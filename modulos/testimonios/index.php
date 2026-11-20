<?php
declare(strict_types=1);

require_once __DIR__ . '/obtener_testimonios.php';

$opcionesCarrera = carreras();

$carreraId = filter_input(
    INPUT_GET,
    'carrera',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
) ?: null;
$tipo = $_GET['tipo'] ?? '';
$tipos = ['estudiante' => 'Estudiantes', 'egresado' => 'Egresados', 'familiar' => 'Familiares'];
$tipo = is_string($tipo) && isset($tipos[$tipo]) ? $tipo : null;
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;

$listado = tes_listar_publicados($carreraId, $tipo, $pagina);
$testimonios = $listado['testimonios'];
$pagina = $listado['pagina'];
$paginas = $listado['paginas'];
$etiquetas = tes_etiquetas_por_testimonio(array_column($testimonios, 'id'));
$filtrosActivos = array_filter(['carrera' => $carreraId, 'tipo' => $tipo]);

$enlacePagina = static fn (int $numero): string => '?' . http_build_query($filtrosActivos + ['pagina' => $numero]);

cabecera('Testimonios', '', 'css/listado.css');
?>

<section class="contenedor seccion" id="testimonios">
    <header class="tes-encabezado">
        <div>
            <h1>Testimonios</h1>
            <p class="entradilla">Conoce las experiencias de estudiantes, egresados y familiares de la comunidad UTBB.</p>
        </div>
        <a class="boton boton--acento" href="formulario.php">Subir testimonio</a>
    </header>

    <form class="tes-filtros" method="get" action="index.php">
        <div class="campo">
    <label class="campo__etiqueta" for="carrera">
        Carrera
    </label>

    <select class="campo__control" id="carrera" name="carrera">
        <option value="">Todas las carreras</option>

        <?php foreach ($opcionesCarrera as $carrera): ?>
            <option
                value="<?= (int) $carrera['id'] ?>"
                <?= $carreraId === (int) $carrera['id'] ? 'selected' : '' ?>
            >
                <?= e($carrera['nombre']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
        <div class="campo">
            <label class="campo__etiqueta" for="tipo">Tipo de persona</label>
            <select class="campo__control" id="tipo" name="tipo">
                <option value="">Todos</option>
                <?php foreach ($tipos as $valor => $nombre): ?>
                    <option value="<?= e($valor) ?>" <?= $tipo === $valor ? 'selected' : '' ?>><?= e($nombre) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="boton boton--secundario" type="submit">Filtrar</button>
        <?php if ($filtrosActivos): ?>
            <a href="index.php">Limpiar filtros</a>
        <?php endif; ?>
    </form>

    <?php if (!$testimonios): ?>
        <div class="vacio">
            <h2><?= $filtrosActivos ? 'No hay testimonios con estos filtros' : 'Todavía no hay testimonios publicados' ?></h2>
        </div>
    <?php else: ?>
        <div class="rejilla">
            <?php foreach ($testimonios as $t): ?>
                <article class="tarjeta tes-tarjeta">
                    <div class="tarjeta__cuerpo">
                        <h2 class="tarjeta__titulo"><?= e($t['nombre']) ?></h2>
                        <p class="leyenda">
                            <?= e(ucfirst($t['tipo_persona'])) ?>

<?php if (!empty($t['carrera'])): ?>
    · <?= e($t['carrera']) ?>
<?php endif; ?>

<?php if (!empty($t['generacion'])): ?>
    · <?= e($t['generacion']) ?>
<?php endif; ?>
                        </p>
                        <p><?= e($t['extracto']) ?><?= $t['tiene_mas'] ? '…' : '' ?></p>
                        <?php if ($t['tiene_mas']): ?>
                            <a href="ficha.php?id=<?= (int) $t['id'] ?>" aria-label="<?= e('Leer más del testimonio de ' . $t['nombre']) ?>">Leer más</a>
                        <?php endif; ?>
                    </div>
                    <?php if (isset($etiquetas[$t['id']])): ?>
                        <div class="tarjeta__pie tes-etiquetas">
                            <?php foreach ($etiquetas[$t['id']] as $tag): ?>
                                <span class="insignia insignia--info">
                                    <?= e($tag['nombre']) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
        </div>

        <?php if ($paginas > 1): ?>
            <nav aria-label="Páginas de testimonios">
                <ul class="paginacion">
                    <?php if ($pagina > 1): ?>
                        <li><a href="<?= e($enlacePagina($pagina - 1)) ?>" rel="prev">Anterior</a></li>
                    <?php endif; ?>
                    <li><span aria-current="page">Página <?= $pagina ?> de <?= $paginas ?></span></li>
                    <?php if ($pagina < $paginas): ?>
                        <li><a href="<?= e($enlacePagina($pagina + 1)) ?>" rel="next">Siguiente</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php pie(); ?>
