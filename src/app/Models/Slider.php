<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active'       => 'boolean',
        'overlay_opacity' => 'integer',
        'order'           => 'integer',
    ];

    public function scopeActive($q)  { return $q->where('is_active', true); }
    public function scopeOrdered($q) { return $q->orderBy('order'); }

    protected static function booted(): void
    {
        $invalidate = fn () => \Illuminate\Support\Facades\Cache::forget('home:slider-ids');
        static::saved($invalidate);
        static::deleted($invalidate);
    }
}
