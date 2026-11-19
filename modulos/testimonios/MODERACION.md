# Moderación de testimonios

1. Entra como **Coordinación** y abre **Panel → Testimonios**. También puedes abrir `modulos/testimonios/admin/index.php` desde la dirección local del proyecto.
2. Filtra por **Pendiente**, **Aprobado**, **Rechazado** o **Todos**. Revisa el texto y, si se adjuntó un video, descárgalo desde la bandeja.
3. Pulsa **Publicar** para guardar el estado `aprobado`, o escribe un motivo y pulsa **Rechazar**. El motivo debe tener de 1 a 300 caracteres. Solo se pueden moderar testimonios pendientes.
4. **Eliminar** pide confirmación y debe usarse con datos de prueba durante la demostración. El borrado quita el registro y el esquema elimina su historial asociado. El archivo de video permanece en el almacenamiento.

## Instalación para pruebas

En una base de prueba limpia, importa `sql/01-nucleo.sql` y luego `modulos/testimonios/esquema.sql`. Crea las cuentas de prueba del núcleo y entra como Coordinación. Si ya tienes la tabla provisional de la primera versión, usa una base de prueba separada: importar el esquema encima no cambia las columnas de una tabla que ya existe.

## Cómo funciona

- `admin/index.php` consulta los testimonios y sus carreras, muestra el filtro y envía la acción por POST.
- `admin/actualizar_estado.php` verifica el rol y el token, comprueba los datos y cambia solo un testimonio pendiente. Guarda el estado y el historial en una sola transacción.
- `admin/eliminar_testimonio.php` conecta la acción de borrado de Emmanuel con la bandeja.
- `css/admin.css` muestra una tabla en escritorio y acomoda los campos en vertical en pantallas estrechas.
