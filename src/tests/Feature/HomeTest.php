<?php

namespace Tests\Feature;

use Database\Seeders\CampaignCategorySeeder;
use Database\Seeders\CampaignSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\DonationIntentionSeeder;
use Database\Seeders\PostCategorySeeder;
use Database\Seeders\PostSeeder;
use Database\Seeders\SettingSeeder;
use Database\Seeders\SliderSeeder;
use Database\Seeders\SmsDonationCodeSeeder;
use Database\Seeders\ZekatNisabSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    protected $seeder = [
        CampaignCategorySeeder::class,
        CountrySeeder::class,
        SettingSeeder::class,
        SliderSeeder::class,
        SmsDonationCodeSeeder::class,
        ZekatNisabSeeder::class,
        DonationIntentionSeeder::class,
        PostCategorySeeder::class,
        PostSeeder::class,
        CampaignSeeder::class,
    ];

    public function test_anasayfa_render_olur(): void
    {
        $this->seed();

        $r = $this->get('/');

        $r->assertOk();
        $r->assertSee('Sadakan Sonsuz Olsun');             // slider
        $r->assertSee('Öne Çıkan Çağrılar');               // B3
        $r->assertSee('Gazze Acil Sıcak Yemek');           // featured campaign
        $r->assertSee('Zekat Hesaplayıcı');                // B7
        $r->assertSee('SMS Bağış');                        // B8
        $r->assertSee('Bağışlar Hangi Alanlarda');         // B9
        $r->assertSee('ülke ve bölgede');                  // B10
        $r->assertSee('Medya ve Duyurular');               // B11
    }

    public function test_anasayfa_eager_load_ile_n_plus_1_yok(): void
    {
        $this->seed();

        \DB::enableQueryLog();
        $this->get('/')->assertOk();
        $count = count(\DB::getQueryLog());
        \DB::disableQueryLog();

        // Bütün anasayfa için makul üst sınır (kategori, kampanya x10, ülke x20, post x10 vs.)
        // 30 sorgudan fazlaysa muhtemelen N+1 var.
        $this->assertLessThan(30, $count, "Anasayfa {$count} sorgu yapıyor — N+1 problemi olabilir.");
    }

    public function test_dil_degisikligi_cookie_yazar(): void
    {
        $this->seed();

        $r = $this->get('/?lang=en');
        $r->assertOk();
        $r->assertCookie('site_locale', 'en');
    }

    public function test_para_birimi_degisikligi_cookie_yazar(): void
    {
        $this->seed();

        $r = $this->get('/?currency=USD');
        $r->assertOk();
        $r->assertCookie('site_currency', 'USD');
    }
}
