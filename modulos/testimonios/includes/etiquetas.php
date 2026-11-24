<?php
declare(strict_types=1);

/** Catálogo creado por Coordinación, visible en el formulario y el filtro público. */
function tes_etiquetas_disponibles(): array
{
    return bd()->query('SELECT id, nombre FROM tes_etiquetas ORDER BY nombre')->fetchAll();
}

/** Agrupa las etiquetas de los testimonios solicitados en una sola consulta. */
function tes_etiquetas_por_testimonio(array $ids): array
{
    if (!$ids) {
        return [];
    }

    $marcadores = implode(', ', array_fill(0, count($ids), '?'));
    $consulta = bd()->prepare(
        "SELECT te.testimonio_id, e.id, e.nombre
         FROM tes_testimonio_etiqueta te
         JOIN tes_etiquetas e ON e.id = te.etiqueta_id
         WHERE te.testimonio_id IN ($marcadores)
         ORDER BY e.nombre"
    );
    $consulta->execute($ids);

    $porTestimonio = [];
    foreach ($consulta as $etiqueta) {
        $porTestimonio[$etiqueta['testimonio_id']][] = $etiqueta;
    }

    return $porTestimonio;
}
