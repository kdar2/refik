<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZekatNisabSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'gold_price_per_gram'   => 'decimal:2',
        'silver_price_per_gram' => 'decimal:2',
        'nisab_gold_grams'      => 'decimal:2',
        'nisab_silver_grams'    => 'decimal:2',
        'updated_for_date'      => 'date',
    ];
}
