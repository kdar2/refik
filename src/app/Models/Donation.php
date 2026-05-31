<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'amount'                => 'decimal:2',
        'amount_try'            => 'decimal:2',
        'is_recurring'          => 'boolean',
        'is_corporate'          => 'boolean',
        'certificate_requested' => 'boolean',
        'payment_response'      => 'array',
        'next_charge_at'        => 'date',
        'completed_at'          => 'datetime',
    ];

    public function user(): BelongsTo     { return $this->belongsTo(User::class); }
    public function campaign(): BelongsTo { return $this->belongsTo(Campaign::class); }

    public function scopeCompleted($q) { return $q->where('payment_status', 'completed'); }
    public function scopePending($q)   { return $q->where('payment_status', 'pending'); }
    public function scopeRecurring($q) { return $q->where('is_recurring', true); }

    protected static function booted(): void
    {
        static::creating(function (Donation $d) {
            if (empty($d->reference)) {
                $year = now()->year;
                $next = (int) (static::where('reference', 'like', "RFK-{$year}-%")->max('id') ?? 0) + 1;
                $d->reference = sprintf('RFK-%s-%06d', $year, $next);
            }
        });
    }
}
