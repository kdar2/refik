<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_read'     => 'boolean',
        'is_archived' => 'boolean',
    ];

    public function scopeUnread($q)   { return $q->where('is_read', false); }
    public function scopeArchived($q) { return $q->where('is_archived', true); }
}
