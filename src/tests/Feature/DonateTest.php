<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Donation;
use Database\Seeders\CampaignCategorySeeder;
use Database\Seeders\CampaignSeeder;
use Database\Seeders\CountrySeeder;
use Database\Seeders\DonationIntentionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            CampaignCategorySeeder::class,
            CountrySeeder::class,
            CampaignSeeder::class,
            DonationIntentionSeeder::class,
        ]);
    }

    public function test_donate_sayfasi_render_olur(): void
    {
        $this->get('/donate')->assertOk()->assertSee('Tutar ve Tür');
    }

    public function test_donate_sayfasi_kampanya_query_string_ile_render_olur(): void
    {
        $c = Campaign::first();
        $r = $this->get("/donate?campaign={$c->slug}&amount=250&type=zakat");
        $r->assertOk();
        $r->assertSee($c->title_tr);
    }

    public function test_kvkk_olmadan_bagis_reddedilir(): void
    {
        $c = Campaign::first();

        $r = $this->post('/donate', [
            'campaign_id'    => $c->id,
            'amount'         => 100,
            'currency'       => 'TRY',
            'type'           => 'general',
            'frequency'      => 'one_time',
            'donor_name'     => 'Test Kullanıcı',
            'donor_email'    => 'test@refik.test',
            'payment_method' => 'credit_card',
            'card_number'    => '5526080000000006',
            'card_holder'    => 'Test',
            'card_expiry'    => '12/30',
            'card_cvv'       => '123',
        ]);

        $r->assertSessionHasErrors('kvkk');
        $this->assertDatabaseCount('donations', 0);
    }

    public function test_basarili_kart_bagisi_kampanya_toplamini_artirir(): void
    {
        $c = Campaign::first();
        $beforeRaised = (float) $c->raised_amount;
        $beforeDonors = $c->donor_count;

        $r = $this->post('/donate', [
            'campaign_id'    => $c->id,
            'amount'         => 500,
            'currency'       => 'TRY',
            'type'           => 'zakat',
            'frequency'      => 'one_time',
            'donor_name'     => 'Test Kullanıcı',
            'donor_email'    => 'test@refik.test',
            'kvkk'           => '1',
            'payment_method' => 'credit_card',
            'card_number'    => '5526080000000006',
            'card_holder'    => 'Test',
            'card_expiry'    => '12/30',
            'card_cvv'       => '123',
        ]);

        $r->assertRedirect();

        $donation = Donation::latest()->first();
        $this->assertNotNull($donation);
        $this->assertEquals('completed', $donation->payment_status);
        $this->assertStringStartsWith('RFK-', $donation->reference);

        $c->refresh();
        $this->assertEquals($beforeRaised + 500, (float) $c->raised_amount);
        $this->assertEquals($beforeDonors + 1, $c->donor_count);
    }

    public function test_red_kart_bagis_yapmaz(): void
    {
        $c = Campaign::first();

        $this->post('/donate', [
            'campaign_id'    => $c->id,
            'amount'         => 100,
            'currency'       => 'TRY',
            'type'           => 'general',
            'frequency'      => 'one_time',
            'donor_name'     => 'Test',
            'donor_email'    => 'test@refik.test',
            'kvkk'           => '1',
            'payment_method' => 'credit_card',
            'card_number'    => '4242424242424242',
            'card_holder'    => 'Test',
            'card_expiry'    => '12/30',
            'card_cvv'       => '000',
        ])->assertSessionHas('payment_error');

        $this->assertDatabaseHas('donations', ['payment_status' => 'failed']);
    }

    public function test_havale_bagis_pending_kalir_ve_thank_you_sayfasi_acilir(): void
    {
        $c = Campaign::first();

        $r = $this->post('/donate', [
            'campaign_id'    => $c->id,
            'amount'         => 1000,
            'currency'       => 'TRY',
            'type'           => 'general',
            'frequency'      => 'one_time',
            'donor_name'     => 'Havale Test',
            'donor_email'    => 'havale@refik.test',
            'kvkk'           => '1',
            'payment_method' => 'bank_transfer',
        ]);

        $donation = Donation::latest()->first();
        $r->assertRedirect("/donate/thank-you/{$donation->reference}");
        $this->assertEquals('pending', $donation->payment_status);

        $this->get("/donate/thank-you/{$donation->reference}")
            ->assertOk()
            ->assertSee('Havale bilgileri size iletildi');
    }

    public function test_aylik_bagis_next_charge_at_atanir(): void
    {
        $c = Campaign::first();

        $this->post('/donate', [
            'campaign_id'    => $c->id,
            'amount'         => 100,
            'currency'       => 'TRY',
            'type'           => 'general',
            'frequency'      => 'monthly',
            'donor_name'     => 'Düzenli',
            'donor_email'    => 'duzenli@refik.test',
            'kvkk'           => '1',
            'payment_method' => 'credit_card',
            'card_number'    => '5526080000000006',
            'card_holder'    => 'Test',
            'card_expiry'    => '12/30',
            'card_cvv'       => '123',
        ]);

        $donation = Donation::latest()->first();
        $this->assertTrue($donation->is_recurring);
        $this->assertNotNull($donation->next_charge_at);
    }
}
