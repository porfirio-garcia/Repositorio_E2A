# TES · Testimonios UTBB

## Instalación

1. Configura el núcleo según el README de la raíz e importa `sql/01-nucleo.sql` si es una instalación nueva.
2. En una instalación nueva, ejecuta `modulos/testimonios/esquema.sql`. Si ya tienes `tes_historial`, ejecuta primero `modulos/testimonios/migracion_moderacion.sql` una sola vez para conservar los testimonios y las decisiones. Después puedes ejecutar el esquema para crear las tablas de etiquetas que falten.
3. Si vas a recibir videos, comprueba que PHP pueda escribir en `modulos/testimonios/video/`. En XAMPP, Apache debe aplicar el `.htaccess` de esta carpeta. Si usas otro servidor, configura el bloqueo de acceso HTTP directo a la carpeta. Para aceptar archivos de 35 MB, configura `upload_max_filesize = 35M` y `post_max_size = 40M` o superiores en `php.ini`.

## Flujo

- Abre `modulos/testimonios/` y selecciona **Subir testimonio**. El formulario es público. Pide nombre, tipo de persona, carrera, texto y autorización de publicación. Ya no se solicita generación ni años de estudio; la fecha de creación se registra automáticamente en MySQL. El video es opcional.
- Coordinación entra a `modulos/testimonios/admin/`, inicia la revisión y después publica o rechaza con motivo. Consulta [la guía de moderación](MODERACION.md) para el flujo y las pruebas. En **Administrar etiquetas** se pueden crear, renombrar y eliminar etiquetas. Desde **Editar etiquetas** se pueden asignar hasta tres por testimonio. Si un testimonio anterior tiene más de tres, desmarca las que sobran antes de guardar. Al eliminar una etiqueta se retira de todos los testimonios donde se usaba.
- El listado público muestra los testimonios publicados de treinta en treinta. Permite combinar filtros por carrera, tipo de persona y una etiqueta creada por Coordinación; las etiquetas de las tarjetas son informativas y solo el selector de filtros cambia la etiqueta activa. Las tarjetas muestran hasta **40 palabras o 180 caracteres**, con **4 líneas** visibles, con altura uniforme. El indicador **Ver más** aparece cuando el texto se recorta por palabras o líneas; toda la tarjeta sigue siendo clicable, incluso si el texto es corto. El testimonio completo se conserva en la ficha. El detalle solo está disponible para testimonios publicados. Los videos se consultan en el panel de moderación. El detalle público reproduce videos únicamente cuando el testimonio está aprobado y existe autorización expresa para publicar el video.
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
- El formulario público muestra las etiquetas creadas por Coordinación en un desplegable. Se pueden seleccionar de cero a tres y un contador indica cuántas se han elegido.
- Al enviar el testimonio, PHP verifica que las etiquetas existan y guarda tanto el testimonio pendiente como sus relaciones en una transacción.
- Si falla otro campo del formulario, se recupera la selección de etiquetas siempre que esté dentro del límite.
- Los testimonios solo aparecen públicamente si están aprobados. Carrera, tipo de persona y varias etiquetas se combinan automáticamente sin pulsar «Filtrar» y sin recargar toda la página. Las etiquetas coinciden por cualquiera de las seleccionadas; la paginación conserva los filtros activos.
- No se requieren nuevas tablas: se reutilizan `tes_etiquetas` y `tes_testimonio_etiqueta`.

- La X limpia **solo las etiquetas** seleccionadas y conserva la carrera y el tipo de persona. Sin JavaScript, se muestra el botón «Aplicar filtros» como alternativa.

## Listado y paginación

- Se muestran 30 testimonios aprobados por página, distribuidos en 3 columnas y hasta 10 filas en pantallas de escritorio (2 columnas en tabletas, 1 en celulares), en tarjetas uniformes con extractos de hasta 40 palabras, 180 caracteres y cuatro líneas; el texto completo se mantiene en la ficha.
- Se eliminó el relleno extra del listado y se evita que las páginas con pocos resultados dejen un hueco grande antes del pie.
- La navegación usa números de página, Anterior y Siguiente y conserva los filtros activos.
- Los enlaces de regreso en el formulario, la ficha y las vistas administrativas usan botones del estilo común de la plataforma.

## Límites de texto

- El formulario acepta de 20 a 3000 **caracteres** por testimonio; no hay un límite de 100 palabras al enviar.
- En el listado se muestran hasta 40 **palabras**, 180 caracteres y 4 líneas de cada testimonio, con `Ver más` si queda texto adicional. La ficha conserva y muestra el testimonio completo.
- La paginación pública no cambia la bandeja de moderación.

## Limpieza del módulo

Los archivos SQL de datos ficticios usados durante el desarrollo ya no forman parte del repositorio. Los testimonios cargados previamente en MySQL no se borran al eliminar esos scripts: se gestionan desde la bandeja de Coordinación o desde una base de desarrollo. Se conservan `esquema.sql` y `migracion_video_publico.sql` para la instalación y actualización del módulo.

## Fecha de creación

- El formulario no solicita generación ni años de ingreso o egreso.
- El listado y la ficha muestran «Creado el» con fecha en formato día/mes/año, obtenida de `tes_testimonios.creado_en`.
- Los nuevos envíos obtienen la fecha automáticamente por el valor predeterminado de la columna; no se necesita escribirla desde PHP.
- El esquema para instalaciones nuevas ya no crea la columna `generacion`. Si tu MySQL ya tenía esa columna, puede permanecer sin causar problemas: dejamos de utilizarla, sin borrar los datos históricos ni requerir una migración destructiva.

## Actualización automática del listado

- `index.php` reutiliza `includes/listado_publico.php` para servir las tarjetas y la paginación cuando llega `tes_fragmento=1`.
- `assets/js/filtros.js` actualiza el listado mediante solicitudes GET al cambiar cualquier filtro, conserva el historial del navegador y permite avanzar o retroceder en las páginas sin recarga completa.
- `assets/js/tarjetas.js` vuelve a calcular cuándo mostrar «Ver más» después de cada actualización.
- Se aceptan varias etiquetas con `etiquetas[]` y se mantiene compatibilidad con enlaces antiguos `?etiqueta=ID`.

## Desplegable flotante de etiquetas

- El selector público de etiquetas conserva un ancho compacto cercano a 19 rem en escritorio y se adapta al ancho disponible en móviles.
- Las opciones se despliegan sobre los testimonios, en un panel de posición absoluta, sin desplazar las tarjetas hacia abajo.
- Al hacer clic fuera o pulsar Escape se cierra el panel sin perder la selección. Los filtros siguen actualizándose automáticamente al marcar o desmarcar etiquetas.

- Los textos sin espacios también se truncan a 180 caracteres en el listado. El CSS reserva espacio para «Ver más» y evita que se superponga con el extracto. El texto completo permanece intacto en la ficha.
