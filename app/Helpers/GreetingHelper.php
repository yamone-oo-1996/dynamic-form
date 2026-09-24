<?php

namespace App\Helpers;

/**
 * Greeting helper class.
 */
class GreetingHelper
{
    /**
     * Build a greeting message for a display name.
     *
     * @param string $name
     * @return string
     */
    public static function buildGreeting(string $name): string
    {
        return 'Hello, ' . StringHelper::normalizeDisplayName($name) . '!';
    }
}
