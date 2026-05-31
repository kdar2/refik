<?php

namespace App\Support;

class Money
{
    /**
     * Tutar + para kodunu lokalize edip string'e çevirir.
     * Örn: Money::format(15000, 'TRY') => "15.000 ₺"
     */
    public static function format(float|int|string $amount, string $currency = 'TRY', bool $withSymbol = true): string
    {
        $meta = config("currencies.meta.{$currency}", config('currencies.meta.TRY'));

        $formatted = number_format(
            (float) $amount,
            $meta['decimals'] ?? 2,
            $meta['decimal_sep'] ?? ',',
            $meta['thousands_sep'] ?? '.',
        );

        if (!$withSymbol) {
            return $formatted;
        }

        $symbol = $meta['symbol'] ?? $currency;

        return ($meta['symbol_position'] ?? 'after') === 'before'
            ? $symbol . $formatted
            : $formatted . ' ' . $symbol;
    }

    /**
     * Sadece TL özet — kısa yol.
     */
    public static function tl(float|int|string $amount): string
    {
        return self::format($amount, 'TRY');
    }
}
