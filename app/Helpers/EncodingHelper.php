<?php

if (!function_exists('normalizeEncoding')) {
    /**
     * Convierte cualquier cadena a UTF-8 detectando su codificación original.
     *
     * @param string $string
     * @return string
     */
    function normalizeEncoding($string) {
        $encoding = mb_detect_encoding($string, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        if ($encoding !== 'UTF-8') {
            $string = mb_convert_encoding($string, 'UTF-8', $encoding);
        }
        return $string;
    }
}
