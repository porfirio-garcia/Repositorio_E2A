<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../nucleo/comun.php';
requerir_rol('coordinacion');
require_once __DIR__ . '/../includes/etiquetas.php';

$opcionesId = ['options' => ['min_range' => 1]];
$testimonioId = filter_input(INPUT_GET, 'testimonio_id', FILTER_VALIDATE_INT, $opcionesId) ?: null;
$volver = $testimonioId === null ? 'etiquetas.php' : 'etiquetas.php?testimonio_id=' . $testimonioId;
$conexion = bd();

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!is_string($_POST['token'] ?? null)) {
        http_response_code(400);
        exit('Petición no válida.');
    }
    verificar_token();

    $accion = $_POST['accion'] ?? '';
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT, $opcionesId);
    $nombre = is_string($_POST['nombre'] ?? null) ? trim($_POST['nombre']) : '';

    try {
        switch ($accion) {
            case 'crear':
            case 'renombrar':
                if ($nombre === '' || !mb_check_encoding($nombre, 'UTF-8') || mb_strlen($nombre) > 50) {
                    throw new InvalidArgumentException('Escribe un nombre de 1 a 50 caracteres.');
                }
                if ($accion === 'crear') {
                    $consulta = $conexion->prepare('INSERT INTO tes_etiquetas (nombre) VALUES (?)');
                    $consulta->execute([$nombre]);
                    mensaje('exito', 'Etiqueta creada.');
                    break;
                }
                if (!$id) {
                    throw new InvalidArgumentException('Selecciona una etiqueta válida.');
                }

                $conexion->beginTransaction();
                $consulta = $conexion->prepare('SELECT id FROM tes_etiquetas WHERE id = ? FOR UPDATE');
                $consulta->execute([$id]);
                if (!$consulta->fetchColumn()) {
                    throw new InvalidArgumentException('La etiqueta ya no existe.');
                }
                $consulta = $conexion->prepare('UPDATE tes_etiquetas SET nombre = ? WHERE id = ?');
                $consulta->execute([$nombre, $id]);
                $conexion->commit();
                mensaje('exito', 'Etiqueta guardada.');
                break;

            case 'eliminar':
                if (!$id) {
                    throw new InvalidArgumentException('Selecciona una etiqueta válida.');
                }

                $consulta = $conexion->prepare('DELETE FROM tes_etiquetas WHERE id = ?');
                $consulta->execute([$id]);
                if (!$consulta->rowCount()) {
                    throw new InvalidArgumentException('La etiqueta ya no existe.');
                }
                mensaje('exito', 'Etiqueta eliminada.');
                break;

            case 'asignar':
                if ($testimonioId === null) {
                    throw new InvalidArgumentException('Selecciona un testimonio válido.');
                }

                $ids = tes_validar_etiquetas($_POST['etiquetas'] ?? []);

                $conexion->beginTransaction();
                $consulta = $conexion->prepare('SELECT id FROM tes_testimonios WHERE id = ? FOR UPDATE');
                $consulta->execute([$testimonioId]);
                if (!$consulta->fetchColumn()) {
                    throw new InvalidArgumentException('El testimonio ya no existe.');
                }
                $consulta = $conexion->prepare('DELETE FROM tes_testimonio_etiqueta WHERE testimonio_id = ?');
                $consulta->execute([$testimonioId]);

                $consulta = $conexion->prepare(
                    'INSERT INTO tes_testimonio_etiqueta (testimonio_id, etiqueta_id) VALUES (?, ?)'
                );
                foreach ($ids as $idEtiqueta) {
                    $consulta->execute([$testimonioId, $idEtiqueta]);
                }

                $conexion->commit();
                mensaje('exito', 'Etiquetas del testimonio guardadas.');
                break;

            default:
                throw new InvalidArgumentException('La acción no es válida.');
        }
    } catch (InvalidArgumentException | PDOException $error) {
        if ($conexion->inTransaction()) {
            $conexion->rollBack();
        }

        if ($error instanceof InvalidArgumentException) {
            mensaje('error', $error->getMessage());
        } elseif (($error->errorInfo[1] ?? null) === 1062) {
            mensaje('error', 'Ya existe una etiqueta con ese nombre.');
        } elseif (($error->errorInfo[1] ?? null) === 1452) {
            mensaje('error', 'Una de las etiquetas ya no existe. Actualiza la selección.');
        } else {
            error_log('TES: Error al administrar etiquetas: ' . $error->getMessage());
            mensaje('error', 'No se pudo guardar el cambio. Intenta nuevamente.');
        }
    }

    redirigir($volver);
}

$testimonio = null;
$asignadas = [];
if ($testimonioId !== null) {
    $consulta = $conexion->prepare('SELECT nombre FROM tes_testimonios WHERE id = ?');
    $consulta->execute([$testimonioId]);
    $testimonio = $consulta->fetch();
    if (!$testimonio) {
        no_encontrado('El testimonio no existe.');
    }

    $asignadas = array_column(tes_etiquetas_por_testimonio([$testimonioId])[$testimonioId] ?? [], null, 'id');
}
$etiquetas = $conexion->query('SELECT id, nombre FROM tes_etiquetas ORDER BY nombre')->fetchAll();

cabecera('Administrar etiquetas', '', '../css/admin.css');
?>

<section class="contenedor seccion">
    <p><a class="boton boton--secundario boton--pequeno" href="index.php"><span aria-hidden="true">←</span> Volver a moderación</a></p>
    <h1>Administrar etiquetas</h1>
    <?php pintar_mensajes(); ?>

    <?php if ($testimonio !== null): ?>
        <h2>Etiquetas del testimonio de <?= e($testimonio['nombre']) ?></h2>
        <?php if ($etiquetas): ?>
            <form class="formulario" action="<?= e($volver) ?>" method="post">
                <?= campo_token() ?>
                <fieldset class="campo" data-limite-etiquetas="<?= TES_MAX_ETIQUETAS ?>">
                    <legend class="campo__etiqueta">Selecciona las etiquetas</legend>
                    <p class="campo__ayuda" data-contador-etiquetas aria-live="polite">
                        <?= count($asignadas) ?> de <?= TES_MAX_ETIQUETAS ?> seleccionadas.
                        <?php if (count($asignadas) > TES_MAX_ETIQUETAS): ?>
                            Desmarca las que sobran antes de guardar.
                        <?php endif; ?>
                    </p>
                    <?php foreach ($etiquetas as $tag): ?>
                        <label class="tes-opcion-etiqueta">
                            <input type="checkbox" name="etiquetas[]" value="<?= (int) $tag['id'] ?>"
                                   <?= isset($asignadas[$tag['id']]) ? 'checked' : '' ?>>
                            <?= e($tag['nombre']) ?>
                        </label>
                    <?php endforeach; ?>
                </fieldset>
                <button class="boton boton--primario" type="submit" name="accion" value="asignar">Guardar etiquetas</button>
            </form>
        <?php else: ?>
            <p>Crea una etiqueta para poder asignarla a este testimonio.</p>
        <?php endif; ?>
    <?php endif; ?>

    <h2>Crear etiqueta</h2>
    <form class="formulario" action="<?= e($volver) ?>" method="post">
        <?= campo_token() ?>
        <div class="campo">
            <label class="campo__etiqueta" for="nueva-etiqueta">Nombre</label>
            <input class="campo__control" id="nueva-etiqueta" name="nombre" maxlength="50" required>
        </div>
        <button class="boton boton--primario" type="submit" name="accion" value="crear">Crear etiqueta</button>
    </form>

    <h2>Etiquetas existentes</h2>
    <?php if (!$etiquetas): ?>
        <p>Todavía no hay etiquetas.</p>
    <?php endif; ?>
    <?php foreach ($etiquetas as $tag): ?>
        <div class="tes-editar-etiqueta">
            <form class="tes-renombrar-etiqueta" action="<?= e($volver) ?>" method="post">
                <?= campo_token() ?>
                <input type="hidden" name="id" value="<?= (int) $tag['id'] ?>">
                <label class="campo__etiqueta" for="etiqueta-<?= (int) $tag['id'] ?>">Nombre</label>
                <input class="campo__control" id="etiqueta-<?= (int) $tag['id'] ?>" name="nombre"
                       value="<?= e($tag['nombre']) ?>" maxlength="50" required>
                <button class="boton boton--secundario" type="submit" name="accion" value="renombrar">Renombrar</button>
            </form>
            <form action="<?= e($volver) ?>" method="post">
                <?= campo_token() ?>
                <input type="hidden" name="id" value="<?= (int) $tag['id'] ?>">
                <button class="boton boton--peligro" type="submit" name="accion" value="eliminar"
                        aria-label="<?= e('Eliminar etiqueta ' . $tag['nombre']) ?>">Eliminar</button>
            </form>
        </div>
    <?php endforeach; ?>
</section>

<script src="../assets/js/tes.js?v=<?= filemtime(__DIR__ . '/../assets/js/tes.js') ?>" defer></script>
<?php pie(); ?>
