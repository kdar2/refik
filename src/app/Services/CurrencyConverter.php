<?php

namespace App\Services;

use App\Models\CurrencyRate;
use Illuminate\Support\Facades\Cache;

class CurrencyConverter
{
    /**
     * `from` para biriminden `to` para birimine güncel kuru getirir.
     * `currency_rates` tablosunda en güncel veri kullanılır;
     * yoksa null döner ve UI fallback gösterebilir.
     */
    public static function rate(string $from, string $to): ?float
    {
        $from = strtoupper($from);
        $to   = strtoupper($to);

        if ($from === $to) {
            return 1.0;
        }

        return Cache::remember(
            "currency.rate.{$from}-{$to}",
            now()->addHour(),
            fn () => CurrencyRate::latestRate($from, $to),
        );
    }

    /**
     * `amount` tutarını çevirir. Kur yoksa orijinal tutarı geri verir.
     */
    public static function convert(float $amount, string $from, string $to): float
    {
        $rate = self::rate($from, $to);
        return $rate !== null ? round($amount * $rate, 2) : $amount;
    }

    /**
     * Bağışın TL karşılığını hesaplar — donations.amount_try alanına yazılır.
     */
    public static function toTry(float $amount, string $currency): float
    {
        return self::convert($amount, $currency, 'TRY');
    }
}
