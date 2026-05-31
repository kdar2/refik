<?php

use App\Http\Controllers\Site\AppointmentController;
use App\Http\Controllers\Site\CampaignController;
use App\Http\Controllers\Site\CartController;
use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\CountryController;
use App\Http\Controllers\Site\DonateController;
use App\Http\Controllers\Site\HelpRequestController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\ImpactController;
use App\Http\Controllers\Site\JobApplicationController;
use App\Http\Controllers\Site\NewsletterController;
use App\Http\Controllers\Site\PostController;
use App\Http\Controllers\Site\SitemapController;
use App\Http\Controllers\Site\VolunteerController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Site Routes
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)->name('home');

// Kampanyalar
Route::get('/calismalarimiz',          [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/calismalarimiz/{slug}',   [CampaignController::class, 'show'])->name('campaigns.show');

// Bağış akışı (POST → 5 istek/dakika spam'a karşı)
Route::get('/donate',                          [DonateController::class, 'show'])->name('donate.show');
Route::post('/donate',                         [DonateController::class, 'store'])->middleware('throttle:5,1')->name('donate.store');
Route::get('/donate/thank-you/{reference}',    [DonateController::class, 'thankYou'])->name('donate.thank-you');

// Bağış sepeti
Route::get('/sepet',           [CartController::class, 'show'])->name('cart.show');
Route::post('/sepet/ekle',     [CartController::class, 'add'])->middleware('throttle:30,1')->name('cart.add');
Route::delete('/sepet/{item}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/sepet/temizle',  [CartController::class, 'clear'])->name('cart.clear');

// Haberler & Medya
Route::get('/haberler',         [PostController::class, 'index'])->name('posts.index');
Route::get('/haberler/{slug}',  [PostController::class, 'show'])->name('posts.show');

// Ülkeler / Çalışma Bölgeleri
Route::get('/nerelerde-calisiyoruz',          [CountryController::class, 'index'])->name('countries.index');
Route::get('/nerelerde-calisiyoruz/{code}',   [CountryController::class, 'show'])->name('countries.show');

// İçerik sayfaları
Route::view('/hakkimizda',     'pages.about')->name('about');
Route::get('/etki-ve-guvence', [ImpactController::class, 'show'])->name('impact');

// İletişim
Route::get('/iletisim',  [ContactController::class, 'show'])->name('contact');
Route::post('/iletisim', [ContactController::class, 'store'])->middleware('throttle:3,1')->name('contact.store');

// Form sayfaları (POST'lara rate limit)
Route::get('/gonullu-ol',   [VolunteerController::class, 'show'])->name('volunteer.show');
Route::post('/gonullu-ol',  [VolunteerController::class, 'store'])->middleware('throttle:3,1')->name('volunteer.store');

Route::get('/yardim-talebi',  [HelpRequestController::class, 'show'])->name('help-request.show');
Route::post('/yardim-talebi', [HelpRequestController::class, 'store'])->middleware('throttle:3,1')->name('help-request.store');

Route::get('/insan-kaynaklari',  [JobApplicationController::class, 'show'])->name('careers.show');
Route::post('/insan-kaynaklari', [JobApplicationController::class, 'store'])->middleware('throttle:3,1')->name('careers.store');

// Form gönderim endpointleri (rate limited)
Route::post('/newsletter/subscribe', [NewsletterController::class, 'store'])->middleware('throttle:5,1')->name('newsletter.store');
Route::post('/appointments',         [AppointmentController::class, 'store'])->middleware('throttle:5,1')->name('appointments.store');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'show'])->name('sitemap');
Route::get('/robots.txt',  [SitemapController::class, 'robots'])->name('robots');

// Ödeme webhookları
Route::post('/webhooks/iyzico', [WebhookController::class, 'iyzico'])->name('webhooks.iyzico');
Route::post('/webhooks/paytr',  [WebhookController::class, 'paytr'])->name('webhooks.paytr');
