<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        // database cache driver Eloquent modellerini unserialize ederken patladığı için
        // sadece [type, value] tuple'ını cache'liyoruz — raw scalar tipler güvenli.
        $cached = Cache::remember("settings:{$key}", 3600, function () use ($key) {
            $row = static::where('key', $key)->first();
            return $row ? ['type' => $row->type, 'value' => $row->value] : null;
        });

        if (!$cached) {
            return $default;
        }

        return match ($cached['type']) {
            'json'  => json_decode((string) $cached['value'], true),
            'bool'  => filter_var($cached['value'], FILTER_VALIDATE_BOOLEAN),
            'int'   => (int) $cached['value'],
            default => $cached['value'],
        };
    }

    public static function put(string $key, mixed $value, string $type = 'string', string $group = 'general'): self
    {
        $stored = $type === 'json' ? json_encode($value) : (string) $value;

        $row = static::updateOrCreate(
            ['key' => $key],
            ['value' => $stored, 'type' => $type, 'group' => $group],
        );

        Cache::forget("settings:{$key}");

        return $row;
    }

    protected static function booted(): void
    {
        static::saved(fn (self $s) => Cache::forget("settings:{$s->key}"));
        static::deleted(fn (self $s) => Cache::forget("settings:{$s->key}"));
    }
}
