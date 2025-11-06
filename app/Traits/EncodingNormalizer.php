<?php

namespace App\Traits;

trait EncodingNormalizer
{
    /**
     * Normaliza la codificación de un string a UTF-8.
     * Detecta automáticamente ISO-8859-1, Windows-1252 u otras.
     */
    public function normalizeEncoding(string $value): string
    {
        if (mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        $encoding = mb_detect_encoding($value, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);

        if ($encoding && $encoding !== 'UTF-8') {
            return mb_convert_encoding($value, 'UTF-8', $encoding);
        }

        // Fallback por si no se detecta
        return utf8_encode($value);
    }
}
