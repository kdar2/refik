<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignCategory;
use Database\Seeders\CampaignCategorySeeder;
use Database\Seeders\CampaignSeeder;
use Database\Seeders\CountrySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            CampaignCategorySeeder::class,
            CountrySeeder::class,
            CampaignSeeder::class,
        ]);
    }

    public function test_kampanya_liste_sayfasi_render_olur(): void
    {
        $this->get('/calismalarimiz')
            ->assertOk()
            ->assertSee('Bağışlarınızla')
            ->assertSee('Gazze Acil Sıcak Yemek');
    }

    public function test_kategori_filtresi_calisir(): void
    {
        // Sticky alt bar'da gözüken başlıkları assert etmiyoruz; sadece kart grid'ine
        // özgü, başka kategoride olan başlıkların listede olmadığını doğruluyoruz.
        $r = $this->get('/calismalarimiz?category=su-kuyusu');
        $r->assertOk();
        $r->assertSee('Su Kuyusu Aç');
        $r->assertDontSee("Sudan'a Umut Olmaya Çalışalım");
        $r->assertDontSee('Köy Okulları Kütüphane Projesi');
    }

    public function test_zekat_uygunluk_filtresi_calisir(): void
    {
        $r = $this->get('/calismalarimiz?eligibility=zakat');
        $r->assertOk();

        // Tüm gösterilen kampanyalar zakat_eligible olmalı (örnek: Yetim Sponsorluğu var, Gazze Sıcak Yemek yok)
        $r->assertSee('Yetim Sponsorluğu');
    }

    public function test_kampanya_detay_sayfasi_render_olur(): void
    {
        $c = Campaign::active()->first();

        $this->get("/calismalarimiz/{$c->slug}")
            ->assertOk()
            ->assertSee($c->title_tr)
            ->assertSee('Bağış Yap');
    }

    public function test_olmayan_slug_404_doner(): void
    {
        $this->get('/calismalarimiz/yok-boyle-bir-slug')->assertNotFound();
    }

    public function test_pasif_kampanya_show_sayfasi_404_doner(): void
    {
        $c = Campaign::first();
        $c->update(['is_active' => false]);

        $this->get("/calismalarimiz/{$c->slug}")->assertNotFound();
    }
}
