<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

/**
 * Aktif dili sırayla şu kaynaklardan belirler:
 *   1) ?lang=xx query string  (1 yıllık cookie kaydedilir)
 *   2) "site_locale" cookie
 *   3) Accept-Language header'ı (sadece desteklenenler)
 *   4) config('app.locale') varsayılanı
 *
 * Faz 5: para birimi de aynı kalıba göre `site_currency` cookie üzerinden taşınır
 * (Money helper config('currencies.default') yerine session/cookie değerini de okuyabilir).
 */
class SetLocale
{
    public const SUPPORTED = ['tr', 'en'];
    public const SUPPORTED_CURRENCIES = ['TRY', 'USD', 'EUR'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);
        app()->setLocale($locale);
        Carbon::setLocale($locale);

        $currency = $this->resolveCurrency($request);
        config(['currencies.active' => $currency]);

        $response = $next($request);

        // Cookie güncelle (?lang= veya ?currency= geldiyse)
        if ($request->has('lang')) {
            cookie()->queue('site_locale', $locale, 60 * 24 * 365);
        }
        if ($request->has('currency')) {
            cookie()->queue('site_currency', $currency, 60 * 24 * 365);
        }

        return $response;
    }

    private function resolveLocale(Request $request): string
    {
        $candidates = [
            $request->query('lang'),
            $request->cookie('site_locale'),
            $request->getPreferredLanguage(self::SUPPORTED),
            config('app.locale'),
        ];

        foreach ($candidates as $c) {
            $c = strtolower((string) $c);
            if (in_array($c, self::SUPPORTED, true)) {
                return $c;
            }
        }

        return 'tr';
    }

    private function resolveCurrency(Request $request): string
    {
        $candidates = [
            $request->query('currency'),
            $request->cookie('site_currency'),
            config('currencies.default'),
        ];

        foreach ($candidates as $c) {
            $c = strtoupper((string) $c);
            if (in_array($c, self::SUPPORTED_CURRENCIES, true)) {
                return $c;
            }
        }

        return 'TRY';
    }
}
