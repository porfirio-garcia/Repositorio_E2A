<?php
declare(strict_types=1);

require_once __DIR__ . '/obtener_testimonios.php';

$opcionesCarrera = carreras();
$opcionesEtiqueta = tes_etiquetas_disponibles();

$carreraId = filter_input(
    INPUT_GET,
    'carrera',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
) ?: null;
$etiquetaId = filter_input(
    INPUT_GET,
    'etiqueta',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
) ?: null;
$tipo = $_GET['tipo'] ?? '';
$tipos = ['estudiante' => 'Estudiantes', 'egresado' => 'Egresados', 'familiar' => 'Familiares'];
$tipo = is_string($tipo) && isset($tipos[$tipo]) ? $tipo : null;
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;

$listado = tes_listar_publicados($carreraId, $tipo, $pagina, $etiquetaId);
$testimonios = $listado['testimonios'];
$pagina = $listado['pagina'];
$paginas = $listado['paginas'];
$etiquetas = tes_etiquetas_por_testimonio(array_column($testimonios, 'id'));
$filtrosActivos = array_filter(['carrera' => $carreraId, 'tipo' => $tipo, 'etiqueta' => $etiquetaId]);

$enlacePagina = static fn (int $numero): string => '?' . http_build_query($filtrosActivos + ['pagina' => $numero]);

cabecera('Testimonios', '', 'css/listado.css?v=' . filemtime(__DIR__ . '/css/listado.css'));
?>

<section class="contenedor seccion" id="testimonios">
    <header class="tes-encabezado">
        <div>
            <h1>Testimonios</h1>
            <p class="entradilla">Conoce las experiencias de estudiantes, egresados y familiares de la comunidad UTBB.</p>
        </div>
        
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
        <div class="campo">
            <label class="campo__etiqueta" for="etiqueta">Etiqueta</label>
            <select class="campo__control" id="etiqueta" name="etiqueta">
                <option value="">Todas las etiquetas</option>
                <?php foreach ($opcionesEtiqueta as $tag): ?>
                    <option value="<?= (int) $tag['id'] ?>"
                        <?= $etiquetaId === (int) $tag['id'] ? 'selected' : '' ?>>
                        <?= e($tag['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="boton boton--secundario" type="submit">Filtrar</button>
        <?php if ($filtrosActivos): ?>
            <a class="tes-limpiar-filtros" href="index.php" aria-label="Limpiar filtros" title="Limpiar filtros"><span aria-hidden="true">&times;</span></a>
        <?php endif; ?>
    </form>

    <?php if (!$testimonios): ?>
        <div class="vacio">
            <h2><?= $filtrosActivos ? 'No hay testimonios con estos filtros' : 'Todavía no hay testimonios publicados' ?></h2>
        </div>
    <?php else: ?>
        <div class="rejilla">
            <?php foreach ($testimonios as $t): ?>
                <?php $resumen = tes_resumir_testimonio($t['extracto'], 40); ?>
                <a class="tarjeta tes-tarjeta" href="ficha.php?id=<?= (int) $t['id'] ?>" aria-label="<?= e('Ver testimonio de ' . $t['nombre']) ?>">
                    <div class="tarjeta__cuerpo">
                        <h2 class="tarjeta__titulo"><?= e($t['nombre']) ?></h2>
                        <p class="leyenda tes-tarjeta__metadatos">
                            <?= e(ucfirst($t['tipo_persona'])) ?>

<?php if (!empty($t['carrera'])): ?>
    · <?= e($t['carrera']) ?>
<?php endif; ?>

<?php if (!empty($t['creado_en'])): ?>
    · Creado el <?= e((new DateTimeImmutable($t['creado_en']))->format('d/m/Y')) ?>
<?php endif; ?>
                        </p>
                        <p class="tes-tarjeta__extracto"><?= e($resumen['texto']) ?><?= $resumen['recortado'] ? '…' : '' ?></p>
                        <span class="tes-tarjeta__accion"
                              data-recortado-palabras="<?= $resumen['recortado'] ? '1' : '0' ?>"
                              <?= $resumen['recortado'] ? '' : 'hidden' ?>>
                            Ver más <span aria-hidden="true">→</span>
                        </span>
                    </div>
                    <?php if (isset($etiquetas[$t['id']])): ?>
                        <div class="tarjeta__pie tes-etiquetas">
                            <?php foreach ($etiquetas[$t['id']] as $tag): ?>
                                <span class="insignia insignia--info"><?= e($tag['nombre']) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($paginas > 1): ?>
            <nav class="tes-paginacion" aria-label="Páginas de testimonios">
                <?php if ($pagina > 1): ?>
                    <a class="tes-paginacion__control" href="<?= e($enlacePagina($pagina - 1)) ?>" rel="prev">
                        <span aria-hidden="true">←</span> Anterior
                    </a>
                <?php else: ?>
                    <span class="tes-paginacion__control tes-paginacion__control--desactivado" aria-disabled="true">← Anterior</span>
                <?php endif; ?>

                <div class="tes-paginacion__numeros">
                    <?php
                    $primeraVisible = max(1, min($pagina - 2, $paginas - 4));
                    $ultimaVisible = min($paginas, $primeraVisible + 4);
                    ?>
                    <?php for ($numero = $primeraVisible; $numero <= $ultimaVisible; $numero++): ?>
                        <?php if ($numero === $pagina): ?>
                            <span class="tes-paginacion__numero tes-paginacion__numero--actual" aria-current="page"><?= $numero ?></span>
                        <?php else: ?>
                            <a class="tes-paginacion__numero"
                               href="<?= e($enlacePagina($numero)) ?>"
                               aria-label="Ir a la página <?= $numero ?>"><?= $numero ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <?php if ($pagina < $paginas): ?>
                    <a class="tes-paginacion__control" href="<?= e($enlacePagina($pagina + 1)) ?>" rel="next">
                        Siguiente <span aria-hidden="true">→</span>
                    </a>
                <?php else: ?>
                    <span class="tes-paginacion__control tes-paginacion__control--desactivado" aria-disabled="true">Siguiente →</span>
                <?php endif; ?>
                <small class="tes-paginacion__total">Página <?= $pagina ?> de <?= $paginas ?></small>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>

<script src="assets/js/tarjetas.js?v=<?= filemtime(__DIR__ . '/assets/js/tarjetas.js') ?>" defer></script>
<?php require __DIR__ . '/includes/cta.php'; ?>
<?php pie(); ?>
