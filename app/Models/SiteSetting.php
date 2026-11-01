<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    private const CACHE_KEY = 'site_settings.all';

    protected $fillable = ['key', 'value'];

    protected function casts(): array
    {
        return ['value' => 'array'];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * All settings keyed by name. Falls back to an empty list when the table
     * is unavailable so the public site keeps rendering from config defaults.
     *
     * @return array<string, mixed>
     */
    public static function allValues(): array
    {
        return Cache::remember(self::CACHE_KEY, 300, fn () => static::query()
            ->pluck('value', 'key')
            ->all());
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = static::allValues()[$key] ?? null;

        return filled($value) ? $value : $default;
    }

    public static function put(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
