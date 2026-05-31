<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $guarded = [];

    protected $casts = [
        'gallery'          => 'array',
        'seo'              => 'array',
        'goal_amount'      => 'decimal:2',
        'raised_amount'    => 'decimal:2',
        'donor_count'      => 'integer',
        'order'            => 'integer',
        'is_featured'      => 'boolean',
        'is_emergency'     => 'boolean',
        'is_active'        => 'boolean',
        'zakat_eligible'   => 'boolean',
        'sadaka_eligible'  => 'boolean',
        'fitre_eligible'   => 'boolean',
        'kurban_eligible'  => 'boolean',
        'start_date'       => 'date',
        'end_date'         => 'date',
    ];

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title_tr']];
    }

    public function category(): BelongsTo  { return $this->belongsTo(CampaignCategory::class, 'category_id'); }
    public function country(): BelongsTo   { return $this->belongsTo(Country::class); }
    public function donations(): HasMany   { return $this->hasMany(Donation::class); }

    public function scopeActive($q)    { return $q->where('is_active', true); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }
    public function scopeEmergency($q) { return $q->where('is_emergency', true); }
    public function scopeOrdered($q)   { return $q->orderBy('order')->orderByDesc('id'); }

    public function getProgressPercentAttribute(): int
    {
        if (!$this->goal_amount || $this->goal_amount <= 0) {
            return 0;
        }
        return min(100, (int) round(($this->raised_amount / $this->goal_amount) * 100));
    }

    protected static function booted(): void
    {
        $invalidate = function () {
            \Illuminate\Support\Facades\Cache::forget('home:featured-ids');
            \Illuminate\Support\Facades\Cache::forget('sitemap.xml');
        };
        static::saved($invalidate);
        static::deleted($invalidate);
    }
}
