
-- =============================================================================
-- MODULO TES · TESTIMONIOS
-- Explorador Vocacional UTBB
--
-- Se ejecuta despues de sql/01-nucleo.sql.
-- Tablas propias del modulo: prefijo tes_.
-- =============================================================================

USE explorador_vocacional;

SET NAMES utf8mb4;


-- -----------------------------------------------------------------------------
-- Entidad principal: testimonios
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS tes_testimonios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nombre VARCHAR(100) NOT NULL,

    tipo_persona ENUM('estudiante', 'egresado')
        NOT NULL,

    carrera_id INT UNSIGNED NOT NULL,

    generacion VARCHAR(20) NULL,

    testimonio TEXT NOT NULL,

    -- Nombre del archivo generado por el servidor.
    video_archivo VARCHAR(255) NULL,

    estado ENUM('pendiente', 'aprobado', 'rechazado')
        NOT NULL DEFAULT 'pendiente',

    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    fecha_moderacion DATETIME NULL,

    moderado_por INT UNSIGNED NULL,

    KEY ix_tes_testimonios_estado (estado),
    KEY ix_tes_testimonios_carrera (carrera_id),

    CONSTRAINT fk_tes_testimonios_carrera
        FOREIGN KEY (carrera_id) REFERENCES carreras(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,

    CONSTRAINT fk_tes_testimonios_moderador
        FOREIGN KEY (moderado_por) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------------------------------
-- Bitacora de cambios de estado
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS tes_historial (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    testimonio_id INT UNSIGNED NOT NULL,

    estado_previo VARCHAR(20) NULL,

    estado_nuevo VARCHAR(20) NOT NULL,

    motivo VARCHAR(300) NULL,

    usuario_id INT UNSIGNED NULL,

    creado_en DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,

    KEY ix_tes_historial_testimonio (testimonio_id),

    CONSTRAINT fk_tes_historial_testimonio
        FOREIGN KEY (testimonio_id) REFERENCES tes_testimonios(id)
        ON UPDATE CASCADE ON DELETE CASCADE,

    CONSTRAINT fk_tes_historial_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE SET NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;


-- -----------------------------------------------------------------------------
-- Datos de prueba
-- Ejecutar este bloque una sola vez para evitar duplicados.
-- -----------------------------------------------------------------------------

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
VALUES
    (
        'Estudiante de prueba',
        'estudiante',
        (SELECT id FROM carreras WHERE clave = 'AGR'),
        '2024-2027',
        'Este es un testimonio de prueba para demostrar el modulo.',
        'pendiente'
    ),
    (
        'Egresado de prueba',
        'egresado',
        (SELECT id FROM carreras WHERE clave = 'AGR'),
        '2020-2023',
        'Este es un testimonio aprobado de ejemplo.',
        'aprobado'
    ),
    (
        'Segundo estudiante',
        'estudiante',
        (SELECT id FROM carreras WHERE clave = 'AGR'),
        NULL,
        'Este testimonio de ejemplo fue rechazado.',
        'rechazado'
    );
