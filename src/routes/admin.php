<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes — /admin altında, AdminAuth middleware ile korunur
|--------------------------------------------------------------------------
*/

// Auth (admin login/logout — sadece web middleware, AdminAuth yok)
Route::prefix('admin')->name('admin.')->middleware('web')->group(function () {
    Route::get('login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

// Korunan admin grubu
Route::prefix('admin')->name('admin.')->middleware(['web', \App\Http\Middleware\AdminAuth::class])->group(function () {

    Route::get('/', [DashboardController::class, 'show'])->name('dashboard');

    // Kampanyalar
    Route::resource('campaigns', CampaignController::class)->except('show');

    // Haberler
    Route::resource('posts', PostController::class)->except('show');

    // Slider
    Route::resource('sliders', SliderController::class)->except('show');

    // Bağışlar (read-only + export)
    Route::get('donations',                 [DonationController::class, 'index'])->name('donations.index');
    Route::get('donations/export',          [DonationController::class, 'export'])->name('donations.export');
    Route::get('donations/{donation}',      [DonationController::class, 'show'])->name('donations.show');

    // Settings
    Route::get('settings',  [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings',  [SettingController::class, 'update'])->name('settings.update');

    // Kullanıcılar
    Route::resource('users', UserController::class)->except('show');

    // Ülkeler
    Route::resource('countries', CountryController::class)->except('show');

    // Sayfalar
    Route::resource('pages', PageController::class)->except('show');
});
