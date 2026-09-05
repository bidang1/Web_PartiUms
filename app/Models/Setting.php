<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key with cache and default fallback.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
                $setting = static::find($key);
                return $setting ? $setting->value : $default;
            });
        } catch (\Throwable $e) {
            // ponytail: fallback if database is not migrated yet
            return $default;
        }
    }

    /**
     * Set a setting value by key and update cache.
     */
    public static function set(string $key, mixed $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value]
        );

        Cache::forever("setting_{$key}", (string) $value);
    }

    /**
     * Remove a setting value.
     */
    public static function forget(string $key): void
    {
        static::where('key', $key)->delete();
        Cache::forget("setting_{$key}");
    }
}
