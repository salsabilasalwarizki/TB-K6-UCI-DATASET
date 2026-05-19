<?php

namespace App\Helpers;

class StringHelper
{
    /**
     * Safe limit: handle string, object, array, null
     */
    public static function safeLimit($value, int $limit = 100, string $end = '...'): string
    {
        if ($value === null) return '';
        if (is_string($value)) return \Illuminate\Support\Str::limit($value, $limit, $end);
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        return \Illuminate\Support\Str::limit((string) $value, $limit, $end);
    }
    
    /**
     * Safe empty check: handle string, object, array
     */
    public static function isNotEmpty($value): bool
    {
        if ($value === null) return false;
        if (is_string($value)) return trim($value) !== '';
        if (is_array($value)) return !empty($value);
        if (is_object($value)) return !empty((array) $value);
        return (bool) $value;
    }
}