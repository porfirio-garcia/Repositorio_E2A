USE explorador_vocacional;

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
        'Este es un testimonio publicado de ejemplo.',
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
