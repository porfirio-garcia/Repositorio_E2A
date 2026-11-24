# Moderación de testimonios

1. Entra como **Coordinación** y abre **Panel → Testimonios**. También puedes abrir `modulos/testimonios/admin/index.php` desde la dirección local del proyecto.
2. Filtra por **Pendiente**, **Publicado**, **Rechazado** o **Todos**. Revisa el texto y, si se adjuntó un video, descárgalo desde la bandeja.
3. Pulsa **Publicar** para guardar el estado `aprobado`, o escribe un motivo y pulsa **Rechazar**. El motivo debe tener de 1 a 300 caracteres. Solo se pueden moderar testimonios pendientes.
4. Abre **Administrar etiquetas** para gestionar el catálogo. En cada testimonio, usa **Editar etiquetas** para asignar una o varias.
5. **Eliminar** pide confirmación y debe usarse con datos de prueba durante la demostración. El borrado quita el registro y el esquema elimina su historial y sus asignaciones de etiquetas. El archivo de video permanece en el almacenamiento.

## Instalación para pruebas

En una base de prueba limpia, importa `sql/01-nucleo.sql` y luego `modulos/testimonios/esquema.sql`. Genera los ejemplos desde el formulario y modéralos desde Coordinación; los archivos de carga masiva ficticia se retiraron del repositorio. Si ya tienes instalada una versión anterior, revisa las migraciones y realiza un respaldo antes de modificar las tablas.

## Cómo funciona

- `admin/index.php` consulta los testimonios y sus carreras, muestra el filtro y envía la acción por POST.
- `admin/actualizar_estado.php` verifica el rol y el token, comprueba los datos y cambia solo un testimonio pendiente. Guarda el estado y el historial en una sola transacción.
- `admin/eliminar_testimonio.php` conecta la acción de borrado de Emmanuel con la bandeja.
- `css/admin.css` muestra una tabla en escritorio y acomoda los campos en vertical en pantallas estrechas.

## Prueba de integración del 19 de noviembre de 2026

Con una base MariaDB limpia y datos ficticios se comprobó el envío con video, el filtro de pendientes, la descarga del video por Coordinación, el rechazo sin motivo, el historial de decisiones y la actualización del listado público después de aprobar, rechazar o eliminar. En Chrome a 375 px se verificaron el ancho de la bandeja, la validación del formulario y la cancelación del borrado.

Para repetir la prueba en XAMPP, crea dos testimonios ficticios desde el formulario. Aprueba uno, rechaza el otro con motivo y revisa `tes_historial`. El listado público debe mostrar solo el aprobado. Elimínalo al final y vuelve a cargar el listado.
