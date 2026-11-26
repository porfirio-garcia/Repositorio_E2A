<?php
declare(strict_types=1);

const TES_MAX_ETIQUETAS = 3;

/** Valida las etiquetas que se van a guardar en un testimonio. */
function tes_validar_etiquetas(mixed $seleccion): array
{
    if (!is_array($seleccion)) {
        throw new InvalidArgumentException('Selecciona etiquetas válidas.');
    }
    if (count($seleccion) > TES_MAX_ETIQUETAS) {
        throw new InvalidArgumentException('Puedes guardar hasta ' . TES_MAX_ETIQUETAS . ' etiquetas por testimonio.');
    }

    $ids = [];
    foreach ($seleccion as $valor) {
        $id = is_string($valor) ? entero($valor) : null;
        if ($id === null) {
            throw new InvalidArgumentException('Selecciona etiquetas válidas.');
        }
        $ids[$id] = $id;
    }

    sort($ids);
    return $ids;
}

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
