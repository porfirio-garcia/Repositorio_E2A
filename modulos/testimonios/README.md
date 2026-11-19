# TES · Testimonios UTBB

## Instalación

1. Configura el núcleo según el README de la raíz e importa `sql/01-nucleo.sql` si es una instalación nueva.
2. Ejecuta `modulos/testimonios/esquema.sql`. Si ya tienes el módulo instalado, puedes volver a ejecutar este archivo para incorporar las tablas de etiquetas. Conserva los testimonios y el historial existentes.
3. Para probar con ejemplos en una base local, importa `modulos/testimonios/datos_prueba.sql` una sola vez.
4. Si vas a recibir videos, comprueba que PHP pueda escribir en `modulos/testimonios/video/`. En XAMPP, Apache debe aplicar el `.htaccess` de esta carpeta. Si usas otro servidor, configura el bloqueo de acceso HTTP directo a la carpeta. Para aceptar archivos de 35 MB, configura `upload_max_filesize = 35M` y `post_max_size = 40M` o superiores en `php.ini`.

## Flujo

- Abre `modulos/testimonios/` y selecciona **Subir testimonio**. El formulario es público. Pide nombre, tipo de persona, carrera, texto y autorización de publicación. La generación y el video para revisión son opcionales.
- Coordinación entra a `modulos/testimonios/admin/` para publicar o rechazar los envíos pendientes. En **Administrar etiquetas** se pueden crear, renombrar y eliminar etiquetas. Desde **Editar etiquetas** en cada testimonio se pueden asignar varias. Al eliminar una etiqueta se retira de todos los testimonios donde se usaba.
- El listado público muestra los testimonios publicados de nueve en nueve. Permite filtrar por etiqueta y tipo de persona. Las tarjetas muestran hasta 100 caracteres y un enlace **Leer más** si tienen texto adicional. El detalle solo está disponible para testimonios publicados. Los videos se consultan en el panel de moderación.
