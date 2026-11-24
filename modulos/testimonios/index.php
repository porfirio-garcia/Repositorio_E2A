<?php
// Incluimos el archivo que hace la consulta
require_once 'obtener_testimonios.php';

// Llamamos a la cabecera del profe (título y CSS)
cabecera('Testimonios');
?>

<section class="contenedor seccion" id="testimonios">
  <h2>Lo que dicen nuestros estudiantes</h2>
  <p class="entradilla ancho-lectura">
    Conoce las experiencias de quienes ya forman parte de la comunidad UTBB.
  </p>

  <div style="text-align: center; margin-bottom: 30px;">
      <a class="boton boton--primario" href="admin/formulario.php">Deja tu testimonio aquí</a>
  </div>

  <?php if (empty($testimonios)): ?>
    <!-- Si no hay testimonios, mostramos un aviso -->
    <div class="vacio">
      <h3>Todavía no hay testimonios publicados</h3>
      <p>¡Sé el primero en compartir tu experiencia!</p>
    </div>
  <?php else: ?>
    <div class="rejilla"> 
      <?php foreach ($testimonios as $t): ?>
        <div class="tarjeta">
          <div class="tarjeta__cuerpo">
            <h3 class="tarjeta__titulo"><?= htmlspecialchars($t['nombre']) ?></h3>
            <p class="leyenda">
              <?= htmlspecialchars(ucfirst($t['tipo_persona'])) ?> | Carrera ID: <?= htmlspecialchars($t['carrera_id']) ?>
              <?= !empty($t['generacion']) ? ' | ' . htmlspecialchars($t['generacion']) : '' ?>
            </p>
            <p><?= htmlspecialchars($t['testimonio']) ?></p>
          </div>
          <div class="tarjeta__pie">
            <span class="insignia insignia--info">Aprobado</span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php
// Llamamos al pie de página del profe
pie();
?>