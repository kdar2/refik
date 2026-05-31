<?php

namespace Database\Seeders;

use App\Models\DonationIntention;
use Illuminate\Database\Seeder;

class DonationIntentionSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['Kendi adıma',          'On my behalf',           1],
            ['Rahmetli yakınım için','For my deceased loved',  2],
            ['Kefaret',              'Atonement (Kaffara)',    3],
            ['Adak',                 'Vow (Adaq)',             4],
            ['Şükür',                'Gratitude',              5],
        ];

        foreach ($rows as [$tr, $en, $order]) {
            DonationIntention::updateOrCreate(
                ['label_tr' => $tr],
                ['label_en' => $en, 'order' => $order, 'is_active' => true],
            );
        }
    }
}
