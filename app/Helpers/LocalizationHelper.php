<?php

use App\Models\LocalizedString;

if (!function_exists('T')) {
    /**
     * Get localized string from database
     * 
     * Usage:
     * T('home.welcome')
     * T('footer.copyright', ['year' => 2024])
     * T('contact.phone', [], 'en')
     * 
     * @param string $key
     * @param array $replacements
     * @param string|null $locale
     * @return string
     */
    function T(string $key, array $replacements = [], ?string $locale = null): string
    {
        return LocalizedString::get($key, $replacements, $locale);
    }
}
