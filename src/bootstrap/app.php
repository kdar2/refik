<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Admin route grubunu yükle (prefix/name/middleware'i kendi içinde tanımlar)
            require __DIR__.'/../routes/admin.php';
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // nginx-proxy arkasında çalışırken HTTPS header'larına güven
        $middleware->trustProxies(at: '*');

        // Ödeme sağlayıcı webhookları CSRF'den muaf
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
        ]);

        // Tüm web isteklerinde dili & para birimini ayarla + güvenlik header'larını ekle
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
