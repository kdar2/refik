<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            // Site
            ['site.phone',    '+90 501 567 33 33',                                  'string', 'site'],
            ['site.email',    'info@refikdernegi.org',                              'string', 'site'],
            ['site.address',  'Dumlupınar Blv. No:274/6-65 Çankaya/Ankara',         'string', 'site'],
            ['site.iban',     'TR44 0020 9000 0208 1561 0000 10',                   'string', 'site'],
            ['site.whatsapp', '+905015673333',                                      'string', 'site'],

            // Sosyal
            ['social.instagram', 'https://instagram.com/refikdernegi',              'string', 'social'],
            ['social.twitter',   'https://x.com/refikdernegi',                      'string', 'social'],
            ['social.facebook',  'https://facebook.com/refikdernegi',               'string', 'social'],
            ['social.youtube',   'https://youtube.com/@refikdernegi',               'string', 'social'],
            ['social.linkedin',  'https://linkedin.com/company/refikdernegi',       'string', 'social'],

            // Verimlilik
            ['efficiency.programs',    '80', 'int', 'efficiency'],
            ['efficiency.fundraising', '12', 'int', 'efficiency'],
            ['efficiency.management',  '8',  'int', 'efficiency'],

            // Acil duyuru
            ['alert.enabled', '1',                                                                            'bool',   'alert'],
            ['alert.text',    "Gazze'de yardıma ihtiyacı olan kardeşlerimize destek ol!",                     'string', 'alert'],
            ['alert.link',    '/calismalarimiz?category=gazze-acil',                                          'string', 'alert'],

            // Yasal
            ['permission.help_collection_no', '12.09.2025 - 475213', 'string', 'legal'],
            ['permission.registry_no',        '06-157-152',          'string', 'legal'],

            // Para birimi
            ['currency.default',         'TRY',                'string', 'currency'],
            ['currency.rates_provider',  'manual',             'string', 'currency'],
        ];

        foreach ($rows as [$key, $value, $type, $group]) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $group],
            );
        }
    }
}
