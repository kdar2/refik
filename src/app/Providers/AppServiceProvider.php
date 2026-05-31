<?php

namespace App\Providers;

use App\Services\Cart\DonationCart;
use App\Services\HijriDateService;
use App\Services\Payment\FakeGateway;
use App\Services\Payment\IyzicoGateway;
use App\Services\Payment\PaymentGatewayInterface;
use App\Services\Payment\PaytrGateway;
use App\Services\PrayerTimeService;
use App\View\Composers\TopbarComposer;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            return match (config('payment.gateway', 'fake')) {
                'iyzico' => $app->make(IyzicoGateway::class),
                'paytr'  => $app->make(PaytrGateway::class),
                default  => $app->make(FakeGateway::class),
            };
        });

        $this->app->singleton(PrayerTimeService::class, fn () => new PrayerTimeService(
            city:     config('site.prayer.city', 'Ankara'),
            country:  config('site.prayer.country', 'Turkey'),
            timezone: config('site.prayer.timezone', 'Europe/Istanbul'),
            method:   (int) config('site.prayer.method', 13),
        ));

        $this->app->singleton(HijriDateService::class, fn ($app) => new HijriDateService(
            timezone: config('site.prayer.timezone', 'Europe/Istanbul'),
            prayer:   $app->make(PrayerTimeService::class),
        ));

        $this->app->scoped(DonationCart::class, fn ($app) => new DonationCart(
            $app->make(Session::class),
        ));
    }

    public function boot(): void
    {
        View::composer('partials.topbar', TopbarComposer::class);
        View::composer('partials.quick-donate-bar', TopbarComposer::class);
    }
}
