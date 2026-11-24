-- DATOS FICTICIOS PARA PROBAR LA PAGINACION PUBLICA DE TES
-- No ejecutar en una base de produccion. Importar desde phpMyAdmin solo en desarrollo.
-- Puede repetirse sin duplicar los diez registros: se comprueba el nombre de cada ejemplo.
-- Los testimonios se marcan como aprobados exclusivamente para comprobar el listado.
-- No se agregan videos ni se alteran los testimonios existentes.
USE explorador_vocacional;

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 01 (ficticio)', 'estudiante', (SELECT id FROM carreras ORDER BY id LIMIT 1), '2023-2026', 'La experiencia de ejemplo describe cómo las actividades prácticas ayudan a relacionar los contenidos de clase con situaciones de trabajo. Este texto es ficticio y se utiliza para probar la paginación.', 'aprobado'
WHERE NOT EXISTS (SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 01 (ficticio)');

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 02 (ficticio)', 'egresado', (SELECT id FROM carreras ORDER BY id LIMIT 1), '2023-2026', 'Testimonio ficticio: durante los proyectos de equipo se fortalecen habilidades de comunicación, organización y resolución de problemas. Registro creado exclusivamente para ensayar el listado público.', 'aprobado'
WHERE NOT EXISTS (SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 02 (ficticio)');

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 03 (ficticio)', 'familiar', NULL, '2023-2026', 'Esta familia ficticia comparte una impresión positiva sobre la atención y las oportunidades de aprendizaje. El registro se creó únicamente para comprobar el comportamiento del sistema.', 'aprobado'
WHERE NOT EXISTS (SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 03 (ficticio)');

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 04 (ficticio)', 'estudiante', (SELECT id FROM carreras ORDER BY id LIMIT 1), '2023-2026', 'Ejemplo de prueba sobre clases, actividades y participación estudiantil. El contenido es inventado y no representa una opinión real de ninguna persona o institución.', 'aprobado'
WHERE NOT EXISTS (SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 04 (ficticio)');

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 05 (ficticio)', 'egresado', (SELECT id FROM carreras ORDER BY id LIMIT 1), '2023-2026', 'El ejemplo explica que elaborar proyectos y presentarlos permite practicar competencias profesionales. Es un testimonio simulado para revisar la navegación entre páginas.', 'aprobado'
WHERE NOT EXISTS (SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 05 (ficticio)');

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 06 (ficticio)', 'familiar', NULL, '2023-2026', 'En este relato de ejemplo, una familia ficticia destaca la importancia de acompañar el aprendizaje. Solo se incluye para evaluar la presentación de las tarjetas.', 'aprobado'
WHERE NOT EXISTS (SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 06 (ficticio)');

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 07 (ficticio)', 'estudiante', (SELECT id FROM carreras ORDER BY id LIMIT 1), '2023-2026', 'Testimonio inventado sobre el trabajo colaborativo, la orientación de docentes y el aprendizaje mediante ejercicios. Su finalidad es probar el orden del listado y los filtros.', 'aprobado'
WHERE NOT EXISTS (SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 07 (ficticio)');

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 08 (ficticio)', 'egresado', (SELECT id FROM carreras ORDER BY id LIMIT 1), '2023-2026', 'Ejemplo ficticio sobre la utilidad de las experiencias académicas durante la preparación profesional. Sirve para verificar la lectura completa de un testimonio.', 'aprobado'
WHERE NOT EXISTS (SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 08 (ficticio)');

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 09 (ficticio)', 'familiar', NULL, '2023-2026', 'Una persona familiar ficticia comenta que la comunicación y la información académica son importantes durante la formación. Este registro no corresponde a un usuario real.', 'aprobado'
WHERE NOT EXISTS (SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 09 (ficticio)');

INSERT INTO tes_testimonios
    (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 10 (ficticio)', 'estudiante', (SELECT id FROM carreras ORDER BY id LIMIT 1), '2023-2026', 'Este testimonio simulado trata sobre el desarrollo de habilidades en actividades universitarias. Se creó para probar tarjetas uniformes, numeración y cambio de página.', 'aprobado'
WHERE NOT EXISTS (SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 10 (ficticio)');

