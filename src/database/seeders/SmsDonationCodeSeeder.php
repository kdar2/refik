<?php

namespace Database\Seeders;

use App\Models\SmsDonationCode;
use Illuminate\Database\Seeder;

class SmsDonationCodeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['Bağış', 'Donation', '2516', 'BAGIS', 50,  1],
            ['Zekat', 'Zakat',    '7705', 'ZEKAT', 240, 2],
            ['İlim',  'Knowledge','7701', 'ILIM',  100, 3],
        ];

        foreach ($rows as [$tr, $en, $code, $kw, $amount, $order]) {
            SmsDonationCode::updateOrCreate(
                ['short_code' => $code, 'keyword' => $kw],
                [
                    'label_tr'    => $tr,
                    'label_en'    => $en,
                    'amount'      => $amount,
                    'currency'    => 'TRY',
                    'order'       => $order,
                    'is_active'   => true,
                ],
            );
        }
    }
}
