<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsletterSubscriber extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active'        => 'boolean',
        'verified_at'      => 'datetime',
        'unsubscribed_at'  => 'datetime',
    ];

    public function scopeActive($q) { return $q->where('is_active', true)->whereNull('unsubscribed_at'); }
}
