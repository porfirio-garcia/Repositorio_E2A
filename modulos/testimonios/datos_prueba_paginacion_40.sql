-- 40 TESTIMONIOS FICTICIOS PARA EL LISTADO DE 30 POR PÁGINA.
-- SOLO DESARROLLO LOCAL. No ejecutar en producción.
-- Las personas y experiencias son simuladas; se insertan como aprobadas para probar el listado.
-- Idempotente por nombre: ejecutar repetidas veces no duplica estos registros.
USE explorador_vocacional;

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 01 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 01. Este testimonio simulado explica las actividades prácticas de clase dentro de una experiencia de aprendizaje inventada. Durante este ejercicio imaginario, la persona organiza sus tareas, compara distintas maneras de resolver dificultades y evalúa lo aprendido. El objetivo de este registro es comprobar que el sistema mantiene los nombres, los datos generales y el texto correctamente dentro del listado público.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 01 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 02 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 02. Este testimonio simulado explica los proyectos colaborativos dentro de una experiencia de aprendizaje inventada. El relato es únicamente una simulación para verificar que las tarjetas conservan su diseño al cambiar de fila. También permite revisar la navegación con los botones de página anterior, siguiente y los números del paginador.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 02 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 03 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 03. Este testimonio simulado explica las presentaciones académicas dentro de una experiencia de aprendizaje inventada. En este caso ficticio se pone atención a la claridad de las instrucciones, a las actividades y a los resultados alcanzados durante un proyecto universitario. Es información creada con fines de prueba, no una declaración de una persona real.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 03 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 04 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 04. Este testimonio simulado explica la comunicación entre estudiantes dentro de una experiencia de aprendizaje inventada. El contenido fue preparado para comprobar la visualización del módulo de testimonios en computadoras y teléfonos. Ninguna de estas frases representa la experiencia auténtica de un alumno, egresado o familiar. A lo largo de varias sesiones, se describen ideas, dudas y posibles mejoras para el trabajo conjunto. El equipo hace una lista de tareas, prepara materiales, revisa los avances y presenta sus resultados al final. En algunos momentos se requiere corregir una propuesta, escuchar opiniones diferentes y volver a organizar el calendario. Esta parte más extensa sirve para comprobar el corte del texto a cien palabras sin afectar la publicación completa. También permite observar la transición entre una vista breve y la página de lectura individual, junto con las etiquetas y los controles de navegación, sin introducir contenido real de usuarios.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 04 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 05 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 05. Este testimonio simulado explica el aprendizaje de herramientas digitales dentro de una experiencia de aprendizaje inventada. Durante este ejercicio imaginario, la persona organiza sus tareas, compara distintas maneras de resolver dificultades y evalúa lo aprendido. El objetivo de este registro es comprobar que el sistema mantiene los nombres, los datos generales y el texto correctamente dentro del listado público.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 05 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 06 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 06. Este testimonio simulado explica la organización del tiempo dentro de una experiencia de aprendizaje inventada. El relato es únicamente una simulación para verificar que las tarjetas conservan su diseño al cambiar de fila. También permite revisar la navegación con los botones de página anterior, siguiente y los números del paginador.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 06 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 07 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 07. Este testimonio simulado explica la participación en actividades universitarias dentro de una experiencia de aprendizaje inventada. En este caso ficticio se pone atención a la claridad de las instrucciones, a las actividades y a los resultados alcanzados durante un proyecto universitario. Es información creada con fines de prueba, no una declaración de una persona real.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 07 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 08 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 08. Este testimonio simulado explica la orientación académica dentro de una experiencia de aprendizaje inventada. El contenido fue preparado para comprobar la visualización del módulo de testimonios en computadoras y teléfonos. Ninguna de estas frases representa la experiencia auténtica de un alumno, egresado o familiar. A lo largo de varias sesiones, se describen ideas, dudas y posibles mejoras para el trabajo conjunto. El equipo hace una lista de tareas, prepara materiales, revisa los avances y presenta sus resultados al final. En algunos momentos se requiere corregir una propuesta, escuchar opiniones diferentes y volver a organizar el calendario. Esta parte más extensa sirve para comprobar el corte del texto a cien palabras sin afectar la publicación completa. También permite observar la transición entre una vista breve y la página de lectura individual, junto con las etiquetas y los controles de navegación, sin introducir contenido real de usuarios.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 08 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 09 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 09. Este testimonio simulado explica los talleres formativos dentro de una experiencia de aprendizaje inventada. Durante este ejercicio imaginario, la persona organiza sus tareas, compara distintas maneras de resolver dificultades y evalúa lo aprendido. El objetivo de este registro es comprobar que el sistema mantiene los nombres, los datos generales y el texto correctamente dentro del listado público.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 09 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 10 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 10. Este testimonio simulado explica la preparación para presentar proyectos dentro de una experiencia de aprendizaje inventada. El relato es únicamente una simulación para verificar que las tarjetas conservan su diseño al cambiar de fila. También permite revisar la navegación con los botones de página anterior, siguiente y los números del paginador.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 10 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 11 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 11. Este testimonio simulado explica la experiencia en laboratorios dentro de una experiencia de aprendizaje inventada. En este caso ficticio se pone atención a la claridad de las instrucciones, a las actividades y a los resultados alcanzados durante un proyecto universitario. Es información creada con fines de prueba, no una declaración de una persona real.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 11 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 12 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 12. Este testimonio simulado explica las habilidades para investigar dentro de una experiencia de aprendizaje inventada. El contenido fue preparado para comprobar la visualización del módulo de testimonios en computadoras y teléfonos. Ninguna de estas frases representa la experiencia auténtica de un alumno, egresado o familiar. A lo largo de varias sesiones, se describen ideas, dudas y posibles mejoras para el trabajo conjunto. El equipo hace una lista de tareas, prepara materiales, revisa los avances y presenta sus resultados al final. En algunos momentos se requiere corregir una propuesta, escuchar opiniones diferentes y volver a organizar el calendario. Esta parte más extensa sirve para comprobar el corte del texto a cien palabras sin afectar la publicación completa. También permite observar la transición entre una vista breve y la página de lectura individual, junto con las etiquetas y los controles de navegación, sin introducir contenido real de usuarios.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 12 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 13 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 13. Este testimonio simulado explica el intercambio de ideas con compañeros dentro de una experiencia de aprendizaje inventada. Durante este ejercicio imaginario, la persona organiza sus tareas, compara distintas maneras de resolver dificultades y evalúa lo aprendido. El objetivo de este registro es comprobar que el sistema mantiene los nombres, los datos generales y el texto correctamente dentro del listado público.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 13 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 14 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 14. Este testimonio simulado explica la creación de materiales escolares dentro de una experiencia de aprendizaje inventada. El relato es únicamente una simulación para verificar que las tarjetas conservan su diseño al cambiar de fila. También permite revisar la navegación con los botones de página anterior, siguiente y los números del paginador.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 14 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 15 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 15. Este testimonio simulado explica la resolución de problemas dentro de una experiencia de aprendizaje inventada. En este caso ficticio se pone atención a la claridad de las instrucciones, a las actividades y a los resultados alcanzados durante un proyecto universitario. Es información creada con fines de prueba, no una declaración de una persona real.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 15 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 16 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 16. Este testimonio simulado explica la planeación de objetivos dentro de una experiencia de aprendizaje inventada. El contenido fue preparado para comprobar la visualización del módulo de testimonios en computadoras y teléfonos. Ninguna de estas frases representa la experiencia auténtica de un alumno, egresado o familiar. A lo largo de varias sesiones, se describen ideas, dudas y posibles mejoras para el trabajo conjunto. El equipo hace una lista de tareas, prepara materiales, revisa los avances y presenta sus resultados al final. En algunos momentos se requiere corregir una propuesta, escuchar opiniones diferentes y volver a organizar el calendario. Esta parte más extensa sirve para comprobar el corte del texto a cien palabras sin afectar la publicación completa. También permite observar la transición entre una vista breve y la página de lectura individual, junto con las etiquetas y los controles de navegación, sin introducir contenido real de usuarios.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 16 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 17 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 17. Este testimonio simulado explica la importancia de recibir retroalimentación dentro de una experiencia de aprendizaje inventada. Durante este ejercicio imaginario, la persona organiza sus tareas, compara distintas maneras de resolver dificultades y evalúa lo aprendido. El objetivo de este registro es comprobar que el sistema mantiene los nombres, los datos generales y el texto correctamente dentro del listado público.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 17 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 18 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 18. Este testimonio simulado explica el desarrollo de hábitos de estudio dentro de una experiencia de aprendizaje inventada. El relato es únicamente una simulación para verificar que las tarjetas conservan su diseño al cambiar de fila. También permite revisar la navegación con los botones de página anterior, siguiente y los números del paginador.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 18 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 19 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 19. Este testimonio simulado explica la colaboración entre distintas carreras dentro de una experiencia de aprendizaje inventada. En este caso ficticio se pone atención a la claridad de las instrucciones, a las actividades y a los resultados alcanzados durante un proyecto universitario. Es información creada con fines de prueba, no una declaración de una persona real.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 19 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 20 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 20. Este testimonio simulado explica el uso responsable de tecnología dentro de una experiencia de aprendizaje inventada. El contenido fue preparado para comprobar la visualización del módulo de testimonios en computadoras y teléfonos. Ninguna de estas frases representa la experiencia auténtica de un alumno, egresado o familiar. A lo largo de varias sesiones, se describen ideas, dudas y posibles mejoras para el trabajo conjunto. El equipo hace una lista de tareas, prepara materiales, revisa los avances y presenta sus resultados al final. En algunos momentos se requiere corregir una propuesta, escuchar opiniones diferentes y volver a organizar el calendario. Esta parte más extensa sirve para comprobar el corte del texto a cien palabras sin afectar la publicación completa. También permite observar la transición entre una vista breve y la página de lectura individual, junto con las etiquetas y los controles de navegación, sin introducir contenido real de usuarios.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 20 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 21 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 21. Este testimonio simulado explica las actividades prácticas de clase dentro de una experiencia de aprendizaje inventada. Durante este ejercicio imaginario, la persona organiza sus tareas, compara distintas maneras de resolver dificultades y evalúa lo aprendido. El objetivo de este registro es comprobar que el sistema mantiene los nombres, los datos generales y el texto correctamente dentro del listado público.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 21 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 22 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 22. Este testimonio simulado explica los proyectos colaborativos dentro de una experiencia de aprendizaje inventada. El relato es únicamente una simulación para verificar que las tarjetas conservan su diseño al cambiar de fila. También permite revisar la navegación con los botones de página anterior, siguiente y los números del paginador.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 22 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 23 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 23. Este testimonio simulado explica las presentaciones académicas dentro de una experiencia de aprendizaje inventada. En este caso ficticio se pone atención a la claridad de las instrucciones, a las actividades y a los resultados alcanzados durante un proyecto universitario. Es información creada con fines de prueba, no una declaración de una persona real.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 23 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 24 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 24. Este testimonio simulado explica la comunicación entre estudiantes dentro de una experiencia de aprendizaje inventada. El contenido fue preparado para comprobar la visualización del módulo de testimonios en computadoras y teléfonos. Ninguna de estas frases representa la experiencia auténtica de un alumno, egresado o familiar. A lo largo de varias sesiones, se describen ideas, dudas y posibles mejoras para el trabajo conjunto. El equipo hace una lista de tareas, prepara materiales, revisa los avances y presenta sus resultados al final. En algunos momentos se requiere corregir una propuesta, escuchar opiniones diferentes y volver a organizar el calendario. Esta parte más extensa sirve para comprobar el corte del texto a cien palabras sin afectar la publicación completa. También permite observar la transición entre una vista breve y la página de lectura individual, junto con las etiquetas y los controles de navegación, sin introducir contenido real de usuarios.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 24 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 25 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 25. Este testimonio simulado explica el aprendizaje de herramientas digitales dentro de una experiencia de aprendizaje inventada. Durante este ejercicio imaginario, la persona organiza sus tareas, compara distintas maneras de resolver dificultades y evalúa lo aprendido. El objetivo de este registro es comprobar que el sistema mantiene los nombres, los datos generales y el texto correctamente dentro del listado público.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 25 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 26 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 26. Este testimonio simulado explica la organización del tiempo dentro de una experiencia de aprendizaje inventada. El relato es únicamente una simulación para verificar que las tarjetas conservan su diseño al cambiar de fila. También permite revisar la navegación con los botones de página anterior, siguiente y los números del paginador.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 26 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 27 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 27. Este testimonio simulado explica la participación en actividades universitarias dentro de una experiencia de aprendizaje inventada. En este caso ficticio se pone atención a la claridad de las instrucciones, a las actividades y a los resultados alcanzados durante un proyecto universitario. Es información creada con fines de prueba, no una declaración de una persona real.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 27 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 28 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 28. Este testimonio simulado explica la orientación académica dentro de una experiencia de aprendizaje inventada. El contenido fue preparado para comprobar la visualización del módulo de testimonios en computadoras y teléfonos. Ninguna de estas frases representa la experiencia auténtica de un alumno, egresado o familiar. A lo largo de varias sesiones, se describen ideas, dudas y posibles mejoras para el trabajo conjunto. El equipo hace una lista de tareas, prepara materiales, revisa los avances y presenta sus resultados al final. En algunos momentos se requiere corregir una propuesta, escuchar opiniones diferentes y volver a organizar el calendario. Esta parte más extensa sirve para comprobar el corte del texto a cien palabras sin afectar la publicación completa. También permite observar la transición entre una vista breve y la página de lectura individual, junto con las etiquetas y los controles de navegación, sin introducir contenido real de usuarios.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 28 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 29 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 29. Este testimonio simulado explica los talleres formativos dentro de una experiencia de aprendizaje inventada. Durante este ejercicio imaginario, la persona organiza sus tareas, compara distintas maneras de resolver dificultades y evalúa lo aprendido. El objetivo de este registro es comprobar que el sistema mantiene los nombres, los datos generales y el texto correctamente dentro del listado público.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 29 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 30 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 30. Este testimonio simulado explica la preparación para presentar proyectos dentro de una experiencia de aprendizaje inventada. El relato es únicamente una simulación para verificar que las tarjetas conservan su diseño al cambiar de fila. También permite revisar la navegación con los botones de página anterior, siguiente y los números del paginador.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 30 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 31 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 31. Este testimonio simulado explica la experiencia en laboratorios dentro de una experiencia de aprendizaje inventada. En este caso ficticio se pone atención a la claridad de las instrucciones, a las actividades y a los resultados alcanzados durante un proyecto universitario. Es información creada con fines de prueba, no una declaración de una persona real.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 31 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 32 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 32. Este testimonio simulado explica las habilidades para investigar dentro de una experiencia de aprendizaje inventada. El contenido fue preparado para comprobar la visualización del módulo de testimonios en computadoras y teléfonos. Ninguna de estas frases representa la experiencia auténtica de un alumno, egresado o familiar. A lo largo de varias sesiones, se describen ideas, dudas y posibles mejoras para el trabajo conjunto. El equipo hace una lista de tareas, prepara materiales, revisa los avances y presenta sus resultados al final. En algunos momentos se requiere corregir una propuesta, escuchar opiniones diferentes y volver a organizar el calendario. Esta parte más extensa sirve para comprobar el corte del texto a cien palabras sin afectar la publicación completa. También permite observar la transición entre una vista breve y la página de lectura individual, junto con las etiquetas y los controles de navegación, sin introducir contenido real de usuarios.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 32 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 33 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 33. Este testimonio simulado explica el intercambio de ideas con compañeros dentro de una experiencia de aprendizaje inventada. Durante este ejercicio imaginario, la persona organiza sus tareas, compara distintas maneras de resolver dificultades y evalúa lo aprendido. El objetivo de este registro es comprobar que el sistema mantiene los nombres, los datos generales y el texto correctamente dentro del listado público.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 33 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 34 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 34. Este testimonio simulado explica la creación de materiales escolares dentro de una experiencia de aprendizaje inventada. El relato es únicamente una simulación para verificar que las tarjetas conservan su diseño al cambiar de fila. También permite revisar la navegación con los botones de página anterior, siguiente y los números del paginador.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 34 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 35 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 35. Este testimonio simulado explica la resolución de problemas dentro de una experiencia de aprendizaje inventada. En este caso ficticio se pone atención a la claridad de las instrucciones, a las actividades y a los resultados alcanzados durante un proyecto universitario. Es información creada con fines de prueba, no una declaración de una persona real.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 35 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 36 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 36. Este testimonio simulado explica la planeación de objetivos dentro de una experiencia de aprendizaje inventada. El contenido fue preparado para comprobar la visualización del módulo de testimonios en computadoras y teléfonos. Ninguna de estas frases representa la experiencia auténtica de un alumno, egresado o familiar. A lo largo de varias sesiones, se describen ideas, dudas y posibles mejoras para el trabajo conjunto. El equipo hace una lista de tareas, prepara materiales, revisa los avances y presenta sus resultados al final. En algunos momentos se requiere corregir una propuesta, escuchar opiniones diferentes y volver a organizar el calendario. Esta parte más extensa sirve para comprobar el corte del texto a cien palabras sin afectar la publicación completa. También permite observar la transición entre una vista breve y la página de lectura individual, junto con las etiquetas y los controles de navegación, sin introducir contenido real de usuarios.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 36 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 37 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 37. Este testimonio simulado explica la importancia de recibir retroalimentación dentro de una experiencia de aprendizaje inventada. Durante este ejercicio imaginario, la persona organiza sus tareas, compara distintas maneras de resolver dificultades y evalúa lo aprendido. El objetivo de este registro es comprobar que el sistema mantiene los nombres, los datos generales y el texto correctamente dentro del listado público.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 37 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 38 (ficticio)', 'familiar', NULL, '2023-2026', 'Registro ficticio número 38. Este testimonio simulado explica el desarrollo de hábitos de estudio dentro de una experiencia de aprendizaje inventada. El relato es únicamente una simulación para verificar que las tarjetas conservan su diseño al cambiar de fila. También permite revisar la navegación con los botones de página anterior, siguiente y los números del paginador.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 38 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 39 (ficticio)', 'estudiante', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 39. Este testimonio simulado explica la colaboración entre distintas carreras dentro de una experiencia de aprendizaje inventada. En este caso ficticio se pone atención a la claridad de las instrucciones, a las actividades y a los resultados alcanzados durante un proyecto universitario. Es información creada con fines de prueba, no una declaración de una persona real.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 39 (ficticio)'
);

INSERT INTO tes_testimonios (nombre, tipo_persona, carrera_id, generacion, testimonio, estado)
SELECT 'Prueba TES 40 (ficticio)', 'egresado', (SELECT id FROM carreras WHERE activa = 1 ORDER BY id LIMIT 1), '2023-2026', 'Registro ficticio número 40. Este testimonio simulado explica el uso responsable de tecnología dentro de una experiencia de aprendizaje inventada. El contenido fue preparado para comprobar la visualización del módulo de testimonios en computadoras y teléfonos. Ninguna de estas frases representa la experiencia auténtica de un alumno, egresado o familiar. A lo largo de varias sesiones, se describen ideas, dudas y posibles mejoras para el trabajo conjunto. El equipo hace una lista de tareas, prepara materiales, revisa los avances y presenta sus resultados al final. En algunos momentos se requiere corregir una propuesta, escuchar opiniones diferentes y volver a organizar el calendario. Esta parte más extensa sirve para comprobar el corte del texto a cien palabras sin afectar la publicación completa. También permite observar la transición entre una vista breve y la página de lectura individual, junto con las etiquetas y los controles de navegación, sin introducir contenido real de usuarios.', 'aprobado'
WHERE NOT EXISTS (
    SELECT 1 FROM tes_testimonios WHERE nombre = 'Prueba TES 40 (ficticio)'
);

