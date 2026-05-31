<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsDonationCode extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount'    => 'decimal:2',
        'order'     => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive($q)  { return $q->where('is_active', true); }
    public function scopeOrdered($q) { return $q->orderBy('order'); }
}
