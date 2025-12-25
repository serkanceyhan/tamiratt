<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LocalizedString extends Model
{
    protected $fillable = ['key', 'locale', 'value', 'group', 'description'];

    /**
     * Get localized string by key
     * 
     * @param string $key
     * @param array $replacements
     * @param string|null $locale
     * @return string
     */
    public static function get(string $key, array $replacements = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        
        // Cache key
        $cacheKey = "localized_string.{$locale}.{$key}";
        
        // Get from cache or database
        $value = Cache::remember($cacheKey, 3600, function () use ($key, $locale) {
            $string = self::where('key', $key)
                ->where('locale', $locale)
                ->first();
                
            // Fallback to tr if not found
            if (!$string && $locale !== 'tr') {
                $string = self::where('key', $key)
                    ->where('locale', 'tr')
                    ->first();
            }
            
            return $string?->value ?? $key;
        });
        
        // Replace placeholders
        foreach ($replacements as $search => $replace) {
            $value = str_replace("{{$search}}", $replace, $value);
        }
        
        return $value;
    }
    
    /**
     * Clear localization cache
     */
    public static function clearCache(): void
    {
        Cache::flush();
    }
    
    /**
     * Sync from array (for seeding)
     */
    public static function sync(array $strings, string $locale = 'tr'): void
    {
        foreach ($strings as $key => $value) {
            self::updateOrCreate(
                ['key' => $key, 'locale' => $locale],
                ['value' => $value]
            );
        }
        
        self::clearCache();
    }
}
