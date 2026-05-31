<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Aktif Ödeme Sağlayıcı
    |--------------------------------------------------------------------------
    | "fake" => geliştirme & demo (FakeGateway)
    | "iyzico" / "paytr" => Faz 4'te gerçek SDK entegrasyonları doldurulacak
    */
    'gateway' => env('PAYMENT_GATEWAY', 'fake'),

    /*
    |--------------------------------------------------------------------------
    | Sağlayıcı kimlik bilgileri
    |--------------------------------------------------------------------------
    */
    'iyzico' => [
        'api_key'    => env('IYZICO_API_KEY'),
        'secret_key' => env('IYZICO_SECRET_KEY'),
        'base_url'   => env('IYZICO_BASE_URL', 'https://sandbox-api.iyzipay.com'),
    ],

    'paytr' => [
        'merchant_id'    => env('PAYTR_MERCHANT_ID'),
        'merchant_key'   => env('PAYTR_MERCHANT_KEY'),
        'merchant_salt'  => env('PAYTR_MERCHANT_SALT'),
    ],
];
