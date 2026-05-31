<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes, Sluggable;

    protected $guarded = [];

    protected $casts = [
        'gallery'      => 'array',
        'seo'          => 'array',
        'is_featured'  => 'boolean',
        'is_published' => 'boolean',
        'view_count'   => 'integer',
        'published_at' => 'datetime',
    ];

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title_tr']];
    }

    public function category(): BelongsTo { return $this->belongsTo(PostCategory::class, 'post_category_id'); }
    public function author(): BelongsTo   { return $this->belongsTo(User::class, 'author_id'); }

    public function scopePublished($q) { return $q->where('is_published', true)->whereNotNull('published_at')->where('published_at', '<=', now()); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }

    protected static function booted(): void
    {
        $invalidate = fn () => \Illuminate\Support\Facades\Cache::forget('sitemap.xml');
        static::saved($invalidate);
        static::deleted($invalidate);
    }
}
