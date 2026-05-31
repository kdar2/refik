<?php

namespace App\Support;

use App\Models\Setting;

class Settings
{
    public static function get(string $key, mixed $default = null): mixed
    {
        return Setting::get($key, $default);
    }

    public static function put(string $key, mixed $value, string $type = 'string', string $group = 'general'): Setting
    {
        return Setting::put($key, $value, $type, $group);
    }

    /**
     * `site.*` veya `social.*` gibi grupları toptan döndür.
     */
    public static function group(string $group): array
    {
        return Setting::where('group', $group)
            ->get()
            ->mapWithKeys(fn (Setting $s) => [$s->key => self::get($s->key)])
            ->all();
    }
}
