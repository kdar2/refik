<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditReport extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_published' => 'boolean',
        'year'         => 'integer',
        'file_size'    => 'integer',
    ];

    public function scopePublished($q) { return $q->where('is_published', true); }
}
