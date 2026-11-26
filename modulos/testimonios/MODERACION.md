# Moderación de testimonios

1. Entra como **Coordinación** y abre **Panel → Testimonios** o la ruta `modulos/testimonios/admin/index.php`.
2. Elige **Enviado** en el filtro y pulsa **Iniciar revisión**. El testimonio cambia a **En revisión** y sigue fuera del listado público.
3. Revisa el texto y, si se adjuntó un video, descárgalo desde la bandeja. Pulsa **Publicar** o escribe un motivo de 1 a 300 caracteres y pulsa **Rechazar**.
4. Para consultar una decisión, filtra por **Publicado** o **Rechazado**. El motivo de rechazo se muestra en la bandeja de Coordinación. Desde aquí también puedes gestionar etiquetas y acceder a la acción de borrado.

## Instalación y cuentas locales

- En una base nueva, importa `sql/01-nucleo.sql` y después `modulos/testimonios/esquema.sql`.
- Si la base ya contiene `tes_historial`, haz un respaldo y ejecuta `modulos/testimonios/migracion_moderacion.sql` una sola vez **antes** de importar el esquema nuevo. La migración conserva las decisiones al renombrar la tabla a `tes_moderacion` y agrega `en_revision`. Los testimonios ya publicados o rechazados conservan su estado.
- Genera las cuentas ficticias en modo desarrollo con la herramienta del núcleo `herramientas/crear-usuarios.php`. Usa la cuenta de Coordinación para moderar y la de Editor para verificar que la bandeja está restringida. Para crear testimonios, abre el formulario público.

## Cómo funciona

| Estado en la bandeja | Valor en SQL | Acciones de moderación |
|---|---|---|
| Enviado | `pendiente` | Iniciar revisión |
| En revisión | `en_revision` | Publicar o rechazar |
| Publicado | `aprobado` | Estado final |
| Rechazado | `rechazado` | Estado final |

`admin/actualizar_estado.php` valida el rol, el método POST y el token. Para cambiar el estado, el testimonio debe seguir en el estado anterior que corresponde a la acción. La decisión y su entrada en `tes_moderacion` se guardan en la misma transacción. La bandeja consulta el motivo desde ese historial. Se conservan los valores `pendiente` y `aprobado` para que el formulario y el listado público sigan usando el mismo criterio.

La incorporación de la etapa de revisión, la migración y las comprobaciones de moderación se prepararon con asistencia de IA.

## Pruebas ejecutadas el 25 de noviembre de 2026

Se usaron PHP 8.4, MariaDB 11.8, Chromium y datos ficticios.

| Comprobación | Resultado |
|---|---|
| Entrar con Coordinación, Editor y sin sesión | Coordinación puede moderar. El Editor recibe 403. Sin sesión se redirige al inicio de sesión. |
| Enviar desde el formulario e iniciar revisión | El envío aparece como Enviado. Al iniciar revisión pasa a En revisión y aparece al filtrar por ese estado. |
| Cambiar estado sin POST, con token incorrecto, fuera de orden o repetir una decisión | El estado y el historial se conservan. |
| Rechazar con motivo vacío, solo espacios o más de 300 caracteres | El testimonio permanece En revisión. Un motivo de 300 caracteres se guarda y se muestra en la bandeja. |
| Publicar y consultar el listado y la ficha | Solo el publicado aparece en el listado y tiene ficha pública. |
| Simular un fallo al escribir el historial | Se revierte el cambio de estado y se informa del fallo en el panel. |
| Migrar una instalación anterior con una decisión registrada | El testimonio, su estado y su motivo se conservan. |
| Abrir la bandeja a 375 px de ancho | La página y la tabla caben sin desplazamiento horizontal. Se pudo iniciar revisión y el navegador bloqueó un rechazo vacío. |

La prueba de integración del 19 de noviembre registró además el envío con video, su descarga por Coordinación y la cancelación del borrado.
