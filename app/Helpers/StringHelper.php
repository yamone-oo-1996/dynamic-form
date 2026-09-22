<?php

namespace App\Helpers;

/**
 * String helper class.
 */
class StringHelper
{
    /**
     * Normalize a display name by trimming leading/trailing whitespace and
     * collapsing repeated internal whitespace into a single space.
     *
     * @param string $name
     * @return string
     */
    public static function normalizeDisplayName(string $name): string
    {
        return trim(preg_replace('/\s+/', ' ', $name));
    }
}
