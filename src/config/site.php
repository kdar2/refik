<?php

/*
|--------------------------------------------------------------------------
| Site (Refik Derneği) Genel Ayarları
|--------------------------------------------------------------------------
| Faz 2'de bu değerlerin çoğu `settings` tablosundan okunacak.
| Şimdilik statik fallback olarak burada tutulur.
*/

return [
    'name'          => 'Refik Derneği',
    'legal_name'    => 'Refik Eğitim, Kültür ve Yardımlaşma Derneği',
    'tagline'       => 'Hayra Yoldaş',
    'logo'           => '/storage/refik_full_logo.png',
    'default_image'  => '/storage/refik_logo_only.png',

    'contact' => [
        'phone'   => '+90 501 567 33 33',
        'email'   => 'info@refikdernegi.org',
        'address' => 'Dumlupınar Blv. No:274/6-65 Çankaya/Ankara',
        'whatsapp'=> '+905015673333',
    ],

    'legal' => [
        'iban'                  => 'TR44 0020 9000 0208 1561 0000 10',
        'help_collection_no'    => '12.09.2025 - 475213',
        'registry_no'           => '06-157-152',
    ],

    'social' => [
        'instagram' => 'https://instagram.com/refikdernegi',
        'twitter'   => 'https://x.com/refikdernegi',
        'facebook'  => 'https://facebook.com/refikdernegi',
        'youtube'   => 'https://youtube.com/@refikdernegi',
        'linkedin'  => 'https://linkedin.com/company/refikdernegi',
    ],

    'efficiency' => [
        'programs'    => 80,
        'fundraising' => 12,
        'management'  => 8,
    ],

    'currencies' => ['TRY', 'USD', 'EUR'],
    'default_currency' => 'TRY',

    /*
    | Aladhan namaz vakti API parametreleri.
    | method=13 → Diyanet İşleri Başkanlığı (Türkiye).
    */
    'prayer' => [
        'city'     => env('PRAYER_CITY', 'Ankara'),
        'country'  => env('PRAYER_COUNTRY', 'Turkey'),
        'timezone' => env('PRAYER_TIMEZONE', 'Europe/Istanbul'),
        'method'   => (int) env('PRAYER_METHOD', 13),
    ],
];
