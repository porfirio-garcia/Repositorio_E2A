# TES · Testimonios UTBB

## Instalación

1. Configura el núcleo según el README de la raíz e importa `sql/01-nucleo.sql` si es una instalación nueva.
2. Ejecuta `modulos/testimonios/esquema.sql`. Si ya tienes el módulo instalado, puedes volver a ejecutar este archivo para incorporar las tablas de etiquetas. Conserva los testimonios y el historial existentes.
3. Para probar con ejemplos en una base local, importa `modulos/testimonios/datos_prueba.sql` una sola vez.
4. Si vas a recibir videos, comprueba que PHP pueda escribir en `modulos/testimonios/video/`. En XAMPP, Apache debe aplicar el `.htaccess` de esta carpeta. Si usas otro servidor, configura el bloqueo de acceso HTTP directo a la carpeta. Para aceptar archivos de 35 MB, configura `upload_max_filesize = 35M` y `post_max_size = 40M` o superiores en `php.ini`.

## Flujo

- Abre `modulos/testimonios/` y selecciona **Subir testimonio**. El formulario es público. Pide nombre, tipo de persona, carrera, texto y autorización de publicación. La generación y el video para revisión son opcionales.
- Coordinación entra a `modulos/testimonios/admin/` para publicar o rechazar los envíos pendientes. En **Administrar etiquetas** se pueden crear, renombrar y eliminar etiquetas. Desde **Editar etiquetas** en cada testimonio se pueden asignar varias. Al eliminar una etiqueta se retira de todos los testimonios donde se usaba.
- El listado público muestra los testimonios publicados de nueve en nueve. Permite combinar filtros por carrera, tipo de persona y una etiqueta creada por Coordinación; las etiquetas también son enlaces de filtrado desde las tarjetas. Las tarjetas muestran hasta 100 caracteres; cada una permite abrir la ficha individual mediante el nombre y el enlace **Leer más** (para textos extensos) o **Ver testimonio** (para textos de hasta 100 caracteres). El detalle solo está disponible para testimonios publicados. Los videos se consultan en el panel de moderación. El detalle público reproduce videos únicamente cuando el testimonio está aprobado y existe autorización expresa para publicar el video.
## Tipos de persona

El módulo permite registrar testimonios de:

- Estudiantes
- Egresados
- Familiares

La carrera es obligatoria para estudiantes y egresados,
pero opcional para familiares.

Los testimonios enviados quedan pendientes de moderación.
Solo los aprobados aparecen en el listado público.

## Video público: migración y consentimiento

- En instalaciones existentes, ejecutar una sola vez `modulos/testimonios/migracion_video_publico.sql` desde phpMyAdmin antes de usar el nuevo formulario o el reproductor.
- El campo `video_publico` inicia en `0`: **los videos anteriores siguen siendo privados** hasta obtener el permiso de su autor. No habilitar videos antiguos sin autorización.
- Al enviar un nuevo testimonio con video, el autor puede autorizar su publicación mediante una casilla independiente. Un video autorizado solo aparece en `ficha.php` cuando Coordinación publica el testimonio.
- El reproductor utiliza `video_publico.php`, que verifica estado y autorización y entrega el archivo en modo de reproducción con soporte HTTP Range. La carpeta `video/` continúa bloqueada al acceso directo.


## Etiquetas en el formulario público

- Coordinación crea, renombra y elimina etiquetas desde `admin/etiquetas.php`.
- El formulario público muestra un desplegable que contiene las etiquetas creadas por Coordinación, distribuidas en columnas adaptables. Se puede seleccionar ninguna, una o varias mediante casillas y un contador muestra cuántas se han elegido.
- Al enviar el testimonio, PHP verifica que las etiquetas existan y guarda tanto el testimonio pendiente como sus relaciones en una transacción.
- Si una validación falla, el formulario recupera las etiquetas seleccionadas.
- Los testimonios solo aparecen públicamente si están aprobados. Los filtros por carrera, tipo y etiqueta se pueden combinar sin perder la paginación de nueve elementos.
- No se requieren nuevas tablas: se reutilizan `tes_etiquetas` y `tes_testimonio_etiqueta`.
