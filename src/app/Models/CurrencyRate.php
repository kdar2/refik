<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'rate'       => 'decimal:6',
        'fetched_at' => 'datetime',
    ];

    public static function latestRate(string $from, string $to): ?float
    {
        if ($from === $to) {
            return 1.0;
        }
        $row = static::where('from_currency', $from)->where('to_currency', $to)->orderByDesc('fetched_at')->first();
        return $row ? (float) $row->rate : null;
    }
}
