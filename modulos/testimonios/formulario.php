<?php
declare(strict_types=1);

/**
 * MODULO TES - Formulario de subida de testimonios
 * Explorador Vocacional UTBB
 */

require_once __DIR__ . '/../../nucleo/comun.php';

// Obtener carreras desde el núcleo.
$listaCarreras = carreras();
require_once __DIR__ . '/includes/etiquetas.php';
$listaEtiquetas = tes_etiquetas_disponibles();

// Recuperar datos anteriores si hubo un error.
$datosFormulario = $_SESSION['tes_formulario'] ?? [];
unset($_SESSION['tes_formulario']);

cabecera('Subir testimonio', '', 'css/modulo.css?v=' . filemtime(__DIR__ . '/css/modulo.css'));
?>

<section class="contenedor seccion">

    <p><a class="boton boton--secundario boton--pequeno" href="index.php"><span aria-hidden="true">←</span> Volver a testimonios</a></p>
    <h1>Subir testimonio</h1>

    <?php pintar_mensajes(); ?>

    <p>
        Comparte tu experiencia como estudiante, egresado o familiar
        de un estudiante de la UTBB.
        El equipo de coordinación revisará tu envío antes de publicarlo.
    </p>

    <form
        id="form-testimonio"
        class="formulario tes-formulario"
        action="guardar_testimonio.php"
        method="POST"
        enctype="multipart/form-data"
    >

        <?= campo_token() ?>

        <p id="mensaje-formulario" role="alert"></p>

        <!-- NOMBRE -->
        <div class="campo">
            <label class="campo__etiqueta" for="nombre">
                Nombre completo *
            </label>

            <input
                class="campo__control"
                type="text"
                id="nombre"
                name="nombre"
                value="<?= e($datosFormulario['nombre'] ?? '') ?>"
                maxlength="100"
                required
            >
        </div>

        <!-- TIPO DE PERSONA -->
        <div class="campo">
            <label class="campo__etiqueta" for="tipo_persona">
                Tipo de persona *
            </label>

            <select
                class="campo__control"
                id="tipo_persona"
                name="tipo_persona"
                required
            >
                <option value="">Selecciona una opción</option>

                <option
                    value="estudiante"
                    <?= ($datosFormulario['tipo_persona'] ?? '') === 'estudiante' ? 'selected' : '' ?>
                >
                    Estudiante
                </option>

                <option
                    value="egresado"
                    <?= ($datosFormulario['tipo_persona'] ?? '') === 'egresado' ? 'selected' : '' ?>
                >
                    Egresado
                </option>

                <option
                    value="familiar"
                    <?= ($datosFormulario['tipo_persona'] ?? '') === 'familiar' ? 'selected' : '' ?>
                >
                    Familiar
                </option>
            </select>
        </div>

        <!-- CARRERA -->
        <div class="campo">
            <label class="campo__etiqueta" for="carrera_id">
                Carrera (opcional para familiares)
            </label>

            <select
                class="campo__control"
                id="carrera_id"
                name="carrera_id"
            >
                <option value="">Selecciona una carrera</option>

                <?php foreach ($listaCarreras as $carrera): ?>
                    <option
                        value="<?= e((string) $carrera['id']) ?>"
                        <?= (string)($datosFormulario['carrera_id'] ?? '') === (string)$carrera['id'] ? 'selected' : '' ?>
                    >
                        <?= e($carrera['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <small class="campo__ayuda">
                Obligatoria para estudiantes y egresados.
                Opcional para familiares.
            </small>
        </div>

        <!-- ETIQUETAS DEFINIDAS POR COORDINACIÓN -->
        <fieldset class="campo tes-campo-etiquetas">
            <legend class="campo__etiqueta">Etiquetas (opcional)</legend>
            <p class="campo__ayuda">Puedes elegir varias etiquetas para describir tu experiencia.</p>

            <?php if (!$listaEtiquetas): ?>
                <p class="campo__ayuda">Todavía no hay etiquetas disponibles.</p>
            <?php else: ?>
                <details class="tes-etiquetas-desplegable" <?= !empty($datosFormulario['etiquetas']) ? 'open' : '' ?>>
                    <summary class="tes-etiquetas-desplegable__resumen">
                        <span>Seleccionar etiquetas</span>
                        <span class="tes-etiquetas-desplegable__contador" aria-live="polite">
                            <?php $cantidadElegidas = count($datosFormulario['etiquetas'] ?? []); ?>
                            <?= $cantidadElegidas ? $cantidadElegidas . ' seleccionada' . ($cantidadElegidas === 1 ? '' : 's') : 'Ninguna seleccionada' ?>
                        </span>
                    </summary>
                    <div class="tes-etiquetas-desplegable__panel">
                        <div class="tes-opciones-etiquetas">
                            <?php foreach ($listaEtiquetas as $tag): ?>
                                <label class="tes-opcion-etiqueta-publica">
                                    <input type="checkbox" name="etiquetas[]"
                                           value="<?= (int) $tag['id'] ?>"
                                           <?= in_array((int) $tag['id'], $datosFormulario['etiquetas'] ?? [], true) ? 'checked' : '' ?>>
                                    <span><?= e($tag['nombre']) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </details>
            <?php endif; ?>
        </fieldset>

        <!-- GENERACIÓN -->
        <div class="campo">
            <label class="campo__etiqueta" for="generacion">
                Generación
            </label>

            <input
                class="campo__control"
                type="text"
                id="generacion"
                name="generacion"
                value="<?= e($datosFormulario['generacion'] ?? '') ?>"
                maxlength="20"
                placeholder="Ej. 2023-2026"
            >

            <small class="campo__ayuda">
                Campo opcional.
            </small>
        </div>

        <!-- TESTIMONIO -->
        <div class="campo">
            <label class="campo__etiqueta" for="testimonio">
                Testimonio *
            </label>

            <textarea
                class="campo__control"
                id="testimonio"
                name="testimonio"
                rows="6"
                aria-describedby="ayuda-testimonio"
                required
            ><?= e($datosFormulario['testimonio'] ?? '') ?></textarea>

            <small class="campo__ayuda" id="ayuda-testimonio">
                Escribe entre 20 y 3000 caracteres.
            </small>
        </div>

        <!-- VIDEO -->
        <div class="campo">
            <label class="campo__etiqueta" for="video">
                Video (opcional; puede publicarse con tu autorización)
            </label>

            <input
                class="campo__control"
                type="file"
                id="video"
                name="video"
                accept=".mp4,.webm,video/mp4,video/webm"
            >

            <small class="campo__ayuda">
                Formatos permitidos: MP4 y WebM.
                Tamaño máximo: 35 MB.
            </small>
        </div>

        <!-- AUTORIZACIÓN ESPECÍFICA DEL VIDEO -->
        <div class="campo">
            <label>
                <input type="checkbox" name="consentimiento_video" value="1">
                Si adjunto un video, autorizo que se publique junto con mi testimonio
                escrito cuando Coordinación lo apruebe.
            </label>
            <small class="campo__ayuda">
                Sin esta autorización, el video estará disponible solo para Coordinación.
            </small>
        </div>

        <!-- CONSENTIMIENTO -->
        <div class="campo">
            <label>
                <input
                    type="checkbox"
                    name="consentimiento"
                    value="1"
                    required
                >
                Acepto que se publiquen mi nombre, los datos proporcionados
                y mi testimonio escrito si se aprueba mi envío.
            </label>
        </div>

        <!-- BOTÓN DE ENVÍO -->
        <button
            class="boton boton--primario"
            type="submit"
        >
            Enviar testimonio
        </button>

    </form>

</section>

<script src="assets/js/tes.js?v=<?= filemtime(__DIR__ . '/assets/js/tes.js') ?>" defer></script>

<?php pie(); ?>
