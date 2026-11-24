<?php
declare(strict_types=1);

require_once __DIR__ . '/../../nucleo/comun.php';
require_once __DIR__ . '/includes/etiquetas.php';

const TES_POR_PAGINA = 30;

/**
 * Limita el extracto a 40 palabras separadas por espacios.
 * El diseño recorta visualmente el texto a cinco líneas.
 * El texto íntegro permanece en la ficha del testimonio.
 */
function tes_resumir_testimonio(string $texto, int $limitePalabras = 40): array
{
    $palabras = preg_split('/\\s+/u', trim($texto), $limitePalabras + 1, PREG_SPLIT_NO_EMPTY);
    if ($palabras === false || $palabras === []) {
        return ['texto' => '', 'recortado' => false];
    }

    $recortado = count($palabras) > $limitePalabras;
    return [
        'texto' => implode(' ', array_slice($palabras, 0, $limitePalabras)),
        'recortado' => $recortado,
    ];
}

function tes_etiquetas_publicas(): array
{
    return bd()->query(
        "SELECT DISTINCT e.id, e.nombre
         FROM tes_etiquetas e
         JOIN tes_testimonio_etiqueta te ON te.etiqueta_id = e.id
         JOIN tes_testimonios t ON t.id = te.testimonio_id
         WHERE t.estado = 'aprobado'
         ORDER BY e.nombre"
    )->fetchAll();
}

function tes_listar_publicados(?int $carreraId, ?string $tipo, int $pagina, array $etiquetasIds = []): array
{
    $condiciones = ["t.estado = 'aprobado'"];
    $valores = [];

    if ($carreraId !== null) {
        $condiciones[] = 't.carrera_id = ?';
        $valores[] = $carreraId;
    }

    if ($tipo !== null) {
        $condiciones[] = 't.tipo_persona = ?';
        $valores[] = $tipo;
    }

    // Una o varias etiquetas: mostrar testimonios que coincidan con cualquiera.
    // EXISTS evita duplicar filas cuando un testimonio tiene varias etiquetas.
    if ($etiquetasIds) {
        $marcadores = implode(', ', array_fill(0, count($etiquetasIds), '?'));
        $condiciones[] = "EXISTS (
            SELECT 1 FROM tes_testimonio_etiqueta te
            WHERE te.testimonio_id = t.id AND te.etiqueta_id IN ($marcadores)
        )";
        array_push($valores, ...$etiquetasIds);
    }

    $donde = implode(' AND ', $condiciones);
    $consulta = bd()->prepare("SELECT COUNT(*) FROM tes_testimonios t WHERE $donde");
    $consulta->execute($valores);
    $total = (int) $consulta->fetchColumn();
    $paginas = max(1, (int) ceil($total / TES_POR_PAGINA));
    $pagina = min(max(1, $pagina), $paginas);

    $limite = TES_POR_PAGINA;
    $desde = ($pagina - 1) * $limite;

    $consulta = bd()->prepare(
        "SELECT t.id, t.nombre, t.tipo_persona, t.creado_en, c.nombre AS carrera,
                t.testimonio AS extracto
         FROM tes_testimonios t
         LEFT JOIN carreras c ON c.id = t.carrera_id
         WHERE $donde
         ORDER BY t.creado_en DESC, t.id DESC
         LIMIT $limite OFFSET $desde"
    );
    $consulta->execute($valores);

    return [
        'testimonios' => $consulta->fetchAll(),
        'pagina' => $pagina,
        'paginas' => $paginas,
    ];
}

function tes_obtener_publicado(int $id): ?array
{
    $consulta = bd()->prepare(
        "SELECT t.id, t.nombre, t.tipo_persona, t.creado_en, t.testimonio, t.video_publico, c.nombre AS carrera
         FROM tes_testimonios t
         LEFT JOIN carreras c ON c.id = t.carrera_id
         WHERE t.id = ? AND t.estado = 'aprobado'"
    );
    $consulta->execute([$id]);

    return $consulta->fetch() ?: null;
}
