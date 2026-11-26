<?php
declare(strict_types=1);

const TES_FORMATOS_VIDEO = [
    'mp4' => ['video/mp4'],
    'webm' => ['video/webm'],
    'mov' => ['video/quicktime'],
    'avi' => ['video/x-msvideo', 'video/avi', 'video/msvideo'],
    'mkv' => ['video/x-matroska', 'video/matroska'],
];
const TES_FORMATOS_VIDEO_TEXTO = 'MP4, WebM, MOV, AVI y MKV';

function tes_extension_video(string|false $mime): ?string
{
    foreach (TES_FORMATOS_VIDEO as $extension => $tipos) {
        if (in_array($mime, $tipos, true)) {
            return $extension;
        }
    }

    return null;
}

function tes_mime_video_guardado(mixed $archivo): ?string
{
    if (!is_string($archivo) || !preg_match('/\A[a-f0-9]{32}\.([a-z0-9]+)\z/', $archivo, $partes)) {
        return null;
    }

    return TES_FORMATOS_VIDEO[$partes[1]][0] ?? null;
}
