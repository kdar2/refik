<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        // Çalıştığımız aktif bölgeler + bilgi amaçlı diğer ülkeler.
        // [code, name_tr, name_en, lat, lng, flag, active]
        $rows = [
            ['TUR', 'Türkiye',      'Türkiye',      39.0,    35.0,    '🇹🇷', true],
            ['PSE', 'Filistin',     'Palestine',    31.95,   35.21,   '🇵🇸', true],
            ['SYR', 'Suriye',       'Syria',        34.80,   38.99,   '🇸🇾', true],
            ['SDN', 'Sudan',        'Sudan',        12.86,   30.22,   '🇸🇩', true],
            ['SOM', 'Somali',       'Somalia',      5.15,    46.20,   '🇸🇴', true],
            ['YEM', 'Yemen',        'Yemen',        15.55,   48.52,   '🇾🇪', true],
            ['MMR', 'Myanmar',      'Myanmar',      21.91,   95.96,   '🇲🇲', true],
            ['BGD', 'Bangladeş',    'Bangladesh',   23.68,   90.36,   '🇧🇩', true],
            ['PAK', 'Pakistan',     'Pakistan',     30.37,   69.34,   '🇵🇰', true],
            ['AFG', 'Afganistan',   'Afghanistan',  33.93,   67.71,   '🇦🇫', true],
            ['LBN', 'Lübnan',       'Lebanon',      33.85,   35.86,   '🇱🇧', true],
            ['IRQ', 'Irak',         'Iraq',         33.22,   43.68,   '🇮🇶', true],
            ['NER', 'Nijer',        'Niger',        17.61,   8.08,    '🇳🇪', true],
            ['MLI', 'Mali',         'Mali',         17.57,   -3.99,   '🇲🇱', true],
            ['TCD', 'Çad',          'Chad',         15.45,   18.73,   '🇹🇩', true],
            ['ETH', 'Etiyopya',     'Ethiopia',     9.14,    40.49,   '🇪🇹', true],
            ['KEN', 'Kenya',        'Kenya',        -0.02,   37.91,   '🇰🇪', true],
            ['UGA', 'Uganda',       'Uganda',       1.37,    32.29,   '🇺🇬', true],
            ['TZA', 'Tanzanya',     'Tanzania',     -6.37,   34.89,   '🇹🇿', true],
            ['MOZ', 'Mozambik',     'Mozambique',   -18.66,  35.53,   '🇲🇿', true],
            ['EGY', 'Mısır',        'Egypt',        26.82,   30.80,   '🇪🇬', false],
            ['IDN', 'Endonezya',    'Indonesia',    -0.79,   113.92,  '🇮🇩', false],
            ['IND', 'Hindistan',    'India',        20.59,   78.96,   '🇮🇳', false],
            ['NGA', 'Nijerya',      'Nigeria',      9.08,    8.68,    '🇳🇬', false],
            ['SEN', 'Senegal',      'Senegal',      14.50,   -14.45,  '🇸🇳', false],
            ['MAR', 'Fas',          'Morocco',      31.79,   -7.09,   '🇲🇦', false],
            ['DZA', 'Cezayir',      'Algeria',      28.03,   1.66,    '🇩🇿', false],
            ['TUN', 'Tunus',        'Tunisia',      33.89,   9.54,    '🇹🇳', false],
            ['LBY', 'Libya',        'Libya',        26.34,   17.23,   '🇱🇾', false],
            ['JOR', 'Ürdün',        'Jordan',       30.59,   36.24,   '🇯🇴', false],
        ];

        foreach ($rows as [$code, $tr, $en, $lat, $lng, $flag, $active]) {
            Country::updateOrCreate(
                ['code' => $code],
                [
                    'name_tr'          => $tr,
                    'name_en'          => $en,
                    'lat'              => $lat,
                    'lng'              => $lng,
                    'flag_emoji'       => $flag,
                    'is_active_region' => $active,
                ],
            );
        }
    }
}
