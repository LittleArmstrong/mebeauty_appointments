<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Locale helper (fork, spec 0025).
 *
 * Maps the language a view is rendered in to its HTML language code. Emails are rendered after Notifications
 * switched config('language') to the recipient's language, while config('language_code') still belongs to the
 * request, so the code is derived from the language here.
 */

if (!function_exists('language_code')) {
    /**
     * Get the HTML language code (e.g. "de") of a language (e.g. "german").
     *
     * @param string|null $language Language name; null uses the current rendering language config('language').
     *
     * @return string Returns the code from config('language_codes'), "en" for an unknown language.
     */
    function language_code(?string $language = null): string
    {
        $language ??= (string) config('language');

        $code = array_search($language, config('language_codes') ?: [], true);

        return $code !== false ? (string) $code : 'en';
    }
}
