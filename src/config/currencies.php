<?php

/*
|--------------------------------------------------------------------------
| Para Birimleri (Currency)
|--------------------------------------------------------------------------
| Faz 2'de `currency_rates` tablosundan canlı kurlar ile birleşecek.
| Şimdilik UI seçici ve formatlama için statik referans.
*/

return [
    'default' => 'TRY',

    'supported' => ['TRY', 'USD', 'EUR'],

    'meta' => [
        'TRY' => [
            'code'           => 'TRY',
            'symbol'         => '₺',
            'symbol_position'=> 'after',   // "100 ₺"
            'name_tr'        => 'Türk Lirası',
            'name_en'        => 'Turkish Lira',
            'decimals'       => 2,
            'thousands_sep'  => '.',
            'decimal_sep'    => ',',
            'locale'         => 'tr-TR',
        ],
        'USD' => [
            'code'           => 'USD',
            'symbol'         => '$',
            'symbol_position'=> 'before',  // "$100"
            'name_tr'        => 'Amerikan Doları',
            'name_en'        => 'US Dollar',
            'decimals'       => 2,
            'thousands_sep'  => ',',
            'decimal_sep'    => '.',
            'locale'         => 'en-US',
        ],
        'EUR' => [
            'code'           => 'EUR',
            'symbol'         => '€',
            'symbol_position'=> 'after',
            'name_tr'        => 'Euro',
            'name_en'        => 'Euro',
            'decimals'       => 2,
            'thousands_sep'  => '.',
            'decimal_sep'    => ',',
            'locale'         => 'de-DE',
        ],
    ],

    // Hızlı bağış butonlarındaki preset tutarlar (TRY bazlı).
    'donation_presets' => [
        'TRY' => [50, 100, 250, 500, 1000],
        'USD' => [10, 25, 50, 100, 250],
        'EUR' => [10, 25, 50, 100, 250],
    ],

    // Düzenli bağış sıklıkları
    'frequencies' => [
        'one_time'  => 'Tek Sefer',
        'monthly'   => 'Aylık',
        'quarterly' => 'Üç Aylık',
        'yearly'    => 'Yıllık',
    ],
];
