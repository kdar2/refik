<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CampaignCategory extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'order'     => 'integer',
    ];

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'name_tr']];
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'category_id');
    }

    public function scopeActive($q)  { return $q->where('is_active', true); }
    public function scopeOrdered($q) { return $q->orderBy('order')->orderBy('name_tr'); }
}
