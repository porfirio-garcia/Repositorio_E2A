
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

    tipo_persona ENUM('estudiante', 'egresado', 'familiar')
        NOT NULL,

    carrera_id INT UNSIGNED NULL,

    testimonio TEXT NOT NULL,

    -- Nombre del archivo generado por el servidor.
    video_archivo VARCHAR(255) NULL,
    video_publico TINYINT(1) NOT NULL DEFAULT 0,

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
-- Etiquetas de los testimonios
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS tes_etiquetas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    UNIQUE KEY uq_tes_etiquetas_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS tes_testimonio_etiqueta (
    testimonio_id INT UNSIGNED NOT NULL,
    etiqueta_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (testimonio_id, etiqueta_id),
    KEY ix_tes_testimonio_etiqueta (etiqueta_id, testimonio_id),
    CONSTRAINT fk_tes_etiquetas_testimonio
        FOREIGN KEY (testimonio_id) REFERENCES tes_testimonios(id) ON DELETE CASCADE,
    CONSTRAINT fk_tes_etiquetas_etiqueta
        FOREIGN KEY (etiqueta_id) REFERENCES tes_etiquetas(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
