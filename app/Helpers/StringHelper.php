<?php

namespace App\Helpers;

/**
 * String helper class.
 */
class StringHelper
{
    /**
     * Normalize a display name by trimming leading/trailing whitespace.
     *
     * @param string $name
     * @return string
     */
    public static function normalizeDisplayName(string $name): string
    {
        return trim($name);
    }

    /**
     * Collapse runs of internal whitespace into a single space and trim
     * leading/trailing whitespace.
     *
     * @param string $name
     * @return string
     */
    public static function collapseWhitespace(string $name): string
    {
        return trim(preg_replace('/\s+/', ' ', $name));
    }
}
