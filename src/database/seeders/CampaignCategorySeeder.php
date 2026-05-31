<?php

namespace Database\Seeders;

use App\Models\CampaignCategory;
use Illuminate\Database\Seeder;

class CampaignCategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['gida',            'Gıda Yardımları',   'Food Aid',          'utensils',       '#16A34A', 1],
            ['su-kuyusu',       'Su Kuyusu',         'Water Wells',       'droplets',       '#0EA5E9', 2],
            ['yetim',           'Yetim Desteği',     'Orphan Sponsorship','baby',           '#F59E0B', 3],
            ['egitim',          'Eğitim',            'Education',         'graduation-cap', '#2B448C', 4],
            ['saglik',          'Sağlık',            'Healthcare',        'stethoscope',    '#DC2626', 5],
            ['kurban',          'Kurban',            'Qurbani',           'cookie',         '#C09740', 6],
            ['zekat',           'Zekat',             'Zakat',             'hand-coins',     '#0B295C', 7],
            ['sadaka-i-cariye', 'Sadaka-i Cariye',   'Sadaqah Jariyah',   'infinity',       '#10B981', 8],
            ['gazze-acil',      'Gazze Acil',        'Gaza Emergency',    'siren',          '#D52B52', 9],
            ['sudan',           'Sudan',             'Sudan',             'flag',           '#A16207', 10],
            ['suriye',          'Suriye',            'Syria',             'flag',           '#7C3AED', 11],
            ['barinma',         'Barınma',           'Shelter',           'home',           '#0F766E', 12],
            ['giyim',           'Giyim',             'Clothing',          'shirt',          '#9333EA', 13],
            ['orman',           'Orman Bağışı',      'Forest Donation',   'trees',          '#15803D', 14],
        ];

        foreach ($rows as [$slug, $tr, $en, $icon, $color, $order]) {
            CampaignCategory::updateOrCreate(
                ['slug' => $slug],
                [
                    'name_tr'   => $tr,
                    'name_en'   => $en,
                    'icon'      => $icon,
                    'color'     => $color,
                    'order'     => $order,
                    'is_active' => true,
                ],
            );
        }
    }
}
