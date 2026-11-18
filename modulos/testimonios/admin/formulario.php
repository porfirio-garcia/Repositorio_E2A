<?php
declare(strict_types=1);

/**
 * MODULO TES - Formulario de subida de testimonios
 * Explorador Vocacional UTBB
 */

require_once __DIR__ . '/../../../nucleo/comun.php';

// Acceso para aspirantes y coordinación.
requerir_rol('aspirante', 'coordinacion');

// Obtener carreras desde el núcleo.
$listaCarreras = carreras();

// Recuperar los datos anteriores, si hubo un error.
$datosFormulario = $_SESSION['tes_formulario'] ?? [];
unset($_SESSION['tes_formulario']);

cabecera('Subir testimonio', '', '../css/modulo.css');
pintar_mensajes();
?>

    <section class="contenedor seccion">

        <h1>Subir nuevo testimonio</h1>

        <p>
            Registra la experiencia de un estudiante o egresado.
            El testimonio será enviado a moderación antes de publicarse.
        </p>

        <form
            class="formulario tes-formulario"
            action="guardar_testimonio.php"
            method="POST"
            enctype="multipart/form-data"
        >

            <?= campo_token() ?>

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
                </select>
            </div>

            <div class="campo">
                <label class="campo__etiqueta" for="carrera_id">
                    Carrera *
                </label>

                <select
                    class="campo__control"
                    id="carrera_id"
                    name="carrera_id"
                    required
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
            </div>

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

            <div class="campo">
                <label class="campo__etiqueta" for="testimonio">
                    Testimonio *
                </label>

                <textarea
                    class="campo__control"
                    id="testimonio"
                    name="testimonio"
                    rows="6"
                    required
                ><?= e($datosFormulario['testimonio'] ?? '') ?></textarea>
            </div>

            <div class="campo">
                <label class="campo__etiqueta" for="video">
                    Video del testimonio *
                </label>

                <input
                    class="campo__control"
                    type="file"
                    id="video"
                    name="video"
                    accept=".mp4,.webm,video/mp4,video/webm"
                    required
                >

                <small class="campo__ayuda">
                    Formatos permitidos: MP4 y WebM.
                    Tamaño máximo: 35 MB.
                </small>
            </div>

            <button
                class="boton boton--primario"
                type="submit"
            >
                Guardar testimonio
            </button>

        </form>

    </section>

<?php pie(); ?>
