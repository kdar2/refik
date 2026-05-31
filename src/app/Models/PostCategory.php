<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostCategory extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = [];

    protected $casts = ['is_active' => 'boolean'];

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'name_tr']];
    }

    public function posts(): HasMany { return $this->hasMany(Post::class); }

    public function scopeActive($q) { return $q->where('is_active', true); }
}
