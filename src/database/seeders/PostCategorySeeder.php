<?php

namespace Database\Seeders;

use App\Models\PostCategory;
use Illuminate\Database\Seeder;

class PostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['saha-haberleri', 'Saha Haberleri', 'Field News'],
            ['duyurular',      'Duyurular',      'Announcements'],
            ['basinda-biz',    'Basında Biz',    'In the Press'],
            ['etkinlikler',    'Etkinlikler',    'Events'],
        ];

        foreach ($rows as [$slug, $tr, $en]) {
            PostCategory::updateOrCreate(
                ['slug' => $slug],
                ['name_tr' => $tr, 'name_en' => $en, 'is_active' => true],
            );
        }
    }
}
