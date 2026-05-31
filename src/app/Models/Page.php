<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = [];

    protected $casts = [
        'seo'          => 'array',
        'is_published' => 'boolean',
    ];

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title_tr']];
    }

    public function scopePublished($q) { return $q->where('is_published', true); }
}
