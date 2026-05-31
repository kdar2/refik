<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CampaignCategorySeeder::class,
            CountrySeeder::class,
            SettingSeeder::class,
            SliderSeeder::class,
            SmsDonationCodeSeeder::class,
            ZekatNisabSeeder::class,
            DonationIntentionSeeder::class,
            PostCategorySeeder::class,
            PostSeeder::class,
            CampaignSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}
