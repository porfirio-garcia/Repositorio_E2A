-- Ejecutar una vez en instalaciones anteriores con tes_historial,
-- antes de importar el esquema nuevo. Conserva estados y decisiones previas.
USE explorador_vocacional;

ALTER TABLE tes_testimonios
    MODIFY estado ENUM('pendiente', 'aprobado', 'rechazado', 'en_revision')
        NOT NULL DEFAULT 'pendiente';

RENAME TABLE tes_historial TO tes_moderacion;
