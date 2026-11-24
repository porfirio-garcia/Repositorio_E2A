<?php if (!$testimonios): ?>
        <div class="vacio">
            <h2><?= $filtrosActivos ? 'No hay testimonios con estos filtros' : 'Todavía no hay testimonios publicados' ?></h2>
        </div>
    <?php else: ?>
        <div class="rejilla">
            <?php foreach ($testimonios as $t): ?>
                <?php $resumen = tes_resumir_testimonio($t['extracto'], 40); ?>
                <a class="tarjeta tes-tarjeta" href="ficha.php?id=<?= (int) $t['id'] ?>" aria-label="<?= e('Ver testimonio de ' . $t['nombre']) ?>">
                    <div class="tarjeta__cuerpo">
                        <h2 class="tarjeta__titulo"><?= e($t['nombre']) ?></h2>
                        <p class="leyenda tes-tarjeta__metadatos">
                            <?= e(ucfirst($t['tipo_persona'])) ?>

<?php if (!empty($t['carrera'])): ?>
    · <?= e($t['carrera']) ?>
<?php endif; ?>

<?php if (!empty($t['creado_en'])): ?>
    · Creado el <?= e((new DateTimeImmutable($t['creado_en']))->format('d/m/Y')) ?>
<?php endif; ?>
                        </p>
                        <p class="tes-tarjeta__extracto"><?= e($resumen['texto']) ?><?= $resumen['recortado'] ? '…' : '' ?></p>
                        <span class="tes-tarjeta__accion"
                              data-recortado-palabras="<?= $resumen['recortado'] ? '1' : '0' ?>"
                              <?= $resumen['recortado'] ? '' : 'hidden' ?>>
                            Ver más <span aria-hidden="true">→</span>
                        </span>
                    </div>
                    <?php if (isset($etiquetas[$t['id']])): ?>
                        <div class="tarjeta__pie tes-etiquetas">
                            <?php foreach ($etiquetas[$t['id']] as $tag): ?>
                                <span class="insignia insignia--info"><?= e($tag['nombre']) ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if ($paginas > 1): ?>
            <nav class="tes-paginacion" aria-label="Páginas de testimonios">
                <?php if ($pagina > 1): ?>
                    <a class="tes-paginacion__control" href="<?= e($enlacePagina($pagina - 1)) ?>" rel="prev">
                        <span aria-hidden="true">←</span> Anterior
                    </a>
                <?php else: ?>
                    <span class="tes-paginacion__control tes-paginacion__control--desactivado" aria-disabled="true">← Anterior</span>
                <?php endif; ?>

                <div class="tes-paginacion__numeros">
                    <?php
                    $primeraVisible = max(1, min($pagina - 2, $paginas - 4));
                    $ultimaVisible = min($paginas, $primeraVisible + 4);
                    ?>
                    <?php for ($numero = $primeraVisible; $numero <= $ultimaVisible; $numero++): ?>
                        <?php if ($numero === $pagina): ?>
                            <span class="tes-paginacion__numero tes-paginacion__numero--actual" aria-current="page"><?= $numero ?></span>
                        <?php else: ?>
                            <a class="tes-paginacion__numero"
                               href="<?= e($enlacePagina($numero)) ?>"
                               aria-label="Ir a la página <?= $numero ?>"><?= $numero ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>

                <?php if ($pagina < $paginas): ?>
                    <a class="tes-paginacion__control" href="<?= e($enlacePagina($pagina + 1)) ?>" rel="next">
                        Siguiente <span aria-hidden="true">→</span>
                    </a>
                <?php else: ?>
                    <span class="tes-paginacion__control tes-paginacion__control--desactivado" aria-disabled="true">Siguiente →</span>
                <?php endif; ?>
                <small class="tes-paginacion__total">Página <?= $pagina ?> de <?= $paginas ?></small>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
