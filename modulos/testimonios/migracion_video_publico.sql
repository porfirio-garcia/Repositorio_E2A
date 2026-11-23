-- Ejecutar una sola vez en instalaciones TES existentes.
-- Ningún video anterior se publica automáticamente.
USE explorador_vocacional;

ALTER TABLE tes_testimonios
ADD COLUMN video_publico TINYINT(1) NOT NULL DEFAULT 0;
