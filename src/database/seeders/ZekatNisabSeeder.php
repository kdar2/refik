<?php

namespace Database\Seeders;

use App\Models\ZekatNisabSetting;
use Illuminate\Database\Seeder;

class ZekatNisabSeeder extends Seeder
{
    public function run(): void
    {
        // Mayıs 2026 referans değerleri (örnek; admin canlı güncelleyebilir).
        ZekatNisabSetting::updateOrCreate(
            ['updated_for_date' => now()->toDateString()],
            [
                'gold_price_per_gram'   => 4250.00,
                'silver_price_per_gram' => 49.50,
                'nisab_gold_grams'      => 80.18,
                'nisab_silver_grams'    => 560.00,
            ],
        );
    }
}
