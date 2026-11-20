<?php
declare(strict_types=1);

/**
 * MODULO TES - Formulario de subida de testimonios
 * Explorador Vocacional UTBB
 */

require_once __DIR__ . '/../../nucleo/comun.php';

// Obtener carreras desde el núcleo.
$listaCarreras = carreras();

// Recuperar los datos anteriores, si hubo un error.
$datosFormulario = $_SESSION['tes_formulario'] ?? [];
unset($_SESSION['tes_formulario']);

cabecera('Subir testimonio', '', 'css/modulo.css');
?>

    <section class="contenedor seccion">

        <a href="index.php">Volver a testimonios</a>
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
                    <option
    value="familiar"
    <?= ($datosFormulario['tipo_persona'] ?? '') === 'familiar' ? 'selected' : '' ?>
>
    Familiar
</option>   
                </select>
            </div>

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
                    Obligatoria para estudiantes y egresados.
    Opcional para familiares.   
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
                    aria-describedby="ayuda-testimonio"
                    required
                ><?= e($datosFormulario['testimonio'] ?? '') ?></textarea>
                <small class="campo__ayuda" id="ayuda-testimonio">De 20 a 3000 caracteres.</small>
            </div>

            <div class="campo">
                <label class="campo__etiqueta" for="video">
                    Video para revisión (opcional)
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

            <div class="campo">
                <label>
                    <input type="checkbox" name="consentimiento" value="1" required>
                    Acepto que se publiquen mi nombre, datos académicos y testimonio escrito si se aprueba mi envío.
                </label>
            </div>

            <button
                class="boton boton--primario"
                type="submit"
            >
                Enviar testimonio
            </button>

        </form>

    </section>

<script src="assets/js/tes.js" defer></script>
<?php pie(); ?>
