<?php

namespace App\Helpers;

/**
 * Request helper class.
 */
class RequestHelper
{
    /**
     * retrieve language from request
     *
     * @return void
     */
    public static function getLanguage()
    {
        $request = request();
        $language = $request->header('X-Frontiir-Lang') ?? $request->input('lang') ?? 'en';
        return $language;
    }
}
