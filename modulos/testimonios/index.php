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
// Aceptar selección múltiple y el enlace legado ?etiqueta=ID.
$etiquetasRecibidas = $_GET['etiquetas'] ?? [];
if (!is_array($etiquetasRecibidas)) {
    $etiquetasRecibidas = [];
}
$etiquetaAnterior = $_GET['etiqueta'] ?? null;
if (is_string($etiquetaAnterior) && $etiquetaAnterior !== '') {
    $etiquetasRecibidas[] = $etiquetaAnterior;
}
$etiquetasValidas = array_column($opcionesEtiqueta, 'id');
$etiquetasIds = [];
foreach (array_slice($etiquetasRecibidas, 0, 100) as $etiqueta) {
    if (!is_string($etiqueta) || !ctype_digit($etiqueta)) {
        continue;
    }
    $idEtiqueta = (int) $etiqueta;
    if (in_array($idEtiqueta, array_map('intval', $etiquetasValidas), true)) {
        $etiquetasIds[$idEtiqueta] = $idEtiqueta;
    }
}
$etiquetasIds = array_values($etiquetasIds);
$tipo = $_GET['tipo'] ?? '';
$tipos = ['estudiante' => 'Estudiantes', 'egresado' => 'Egresados', 'familiar' => 'Familiares'];
$tipo = is_string($tipo) && isset($tipos[$tipo]) ? $tipo : null;
$pagina = filter_input(INPUT_GET, 'pagina', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;

$listado = tes_listar_publicados($carreraId, $tipo, $pagina, $etiquetasIds);
$testimonios = $listado['testimonios'];
$pagina = $listado['pagina'];
$paginas = $listado['paginas'];
$etiquetas = tes_etiquetas_por_testimonio(array_column($testimonios, 'id'));
$filtrosActivos = array_filter(['carrera' => $carreraId, 'tipo' => $tipo]);
if ($etiquetasIds) {
    $filtrosActivos['etiquetas'] = $etiquetasIds;
}
$hayFiltros = $carreraId !== null || $tipo !== null || (bool) $etiquetasIds;

$enlacePagina = static fn (int $numero): string => '?' . http_build_query($filtrosActivos + ['pagina' => $numero]);

// El mismo HTML se reutiliza para actualizaciones parciales sin recargar la página.
if (($_GET['tes_fragmento'] ?? null) === '1') {
    header('Content-Type: text/html; charset=utf-8');
    require __DIR__ . '/includes/listado_publico.php';
    exit;
}

cabecera('Testimonios', '', 'css/listado.css?v=' . filemtime(__DIR__ . '/css/listado.css'));
?>

<section class="contenedor seccion" id="testimonios">
    <header class="tes-encabezado">
        <div>
            <h1>Testimonios</h1>
            <p class="entradilla">Conoce las experiencias de estudiantes, egresados y familiares de la comunidad UTBB.</p>
        </div>
        
    </header>

    <form class="tes-filtros" id="tes-filtros" method="get" action="index.php">
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
        <fieldset class="tes-filtro-etiquetas">
            <legend class="campo__etiqueta">Etiquetas</legend>
            <details class="tes-filtro-etiquetas__desplegable" id="tes-filtro-desplegable">
                <summary>
                    <span>Seleccionar etiquetas</span>
                    <span class="tes-filtro-etiquetas__contador"><?= count($etiquetasIds) ?> seleccionadas</span>
                </summary>
                <div class="tes-filtro-etiquetas__panel">
                    <?php foreach ($opcionesEtiqueta as $tag): ?>
                        <label class="tes-filtro-etiquetas__opcion">
                            <input type="checkbox" name="etiquetas[]" value="<?= (int) $tag['id'] ?>"
                                <?= in_array((int) $tag['id'], $etiquetasIds, true) ? 'checked' : '' ?>>
                            <?= e($tag['nombre']) ?>
                        </label>
                    <?php endforeach; ?>
                    <?php if (!$opcionesEtiqueta): ?>
                        <p>No hay etiquetas disponibles.</p>
                    <?php endif; ?>
                </div>
            </details>
        </fieldset>
        <button class="tes-limpiar-filtros" type="button" id="tes-limpiar-etiquetas"
                aria-label="Limpiar únicamente etiquetas" title="Limpiar etiquetas"
                <?= $etiquetasIds ? '' : 'hidden' ?>>&times;</button>
        <noscript><button class="boton boton--secundario" type="submit">Aplicar filtros</button></noscript>
    </form>

    <div id="tes-resultados" aria-live="polite" aria-busy="false">
        <?php require __DIR__ . '/includes/listado_publico.php'; ?>
    </div>
</section>

<script src="assets/js/tarjetas.js?v=<?= filemtime(__DIR__ . '/assets/js/tarjetas.js') ?>" defer></script>
<script src="assets/js/filtros.js?v=<?= filemtime(__DIR__ . '/assets/js/filtros.js') ?>" defer></script>
<?php require __DIR__ . '/includes/cta.php'; ?>
<?php pie(); ?>
