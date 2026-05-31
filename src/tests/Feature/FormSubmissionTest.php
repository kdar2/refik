<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_newsletter_yeni_abone_kaydeder(): void
    {
        $this->post('/newsletter/subscribe', ['email' => 'yeni@refik.test'])
            ->assertRedirect()
            ->assertSessionHas('newsletter_success');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email'     => 'yeni@refik.test',
            'is_active' => true,
        ]);
    }

    public function test_newsletter_ayni_email_yeniden_kayit_olunca_aktif_kalir(): void
    {
        NewsletterSubscriber::create([
            'email'           => 'duplicate@refik.test',
            'is_active'       => false,
            'unsubscribed_at' => now()->subDay(),
        ]);

        $this->post('/newsletter/subscribe', ['email' => 'duplicate@refik.test'])
            ->assertSessionHas('newsletter_success');

        $sub = NewsletterSubscriber::where('email', 'duplicate@refik.test')->first();
        $this->assertTrue($sub->is_active);
        $this->assertNull($sub->unsubscribed_at);
        $this->assertEquals(1, NewsletterSubscriber::where('email', 'duplicate@refik.test')->count());
    }

    public function test_newsletter_invalid_email_validation_error_doner(): void
    {
        $this->post('/newsletter/subscribe', ['email' => 'notanemail'])
            ->assertSessionHasErrors('email');
    }

    public function test_iletisim_formu_kvkk_olmadan_reddedilir(): void
    {
        $this->post('/iletisim', [
            'full_name' => 'Test',
            'email'     => 'test@refik.test',
            'subject'   => 'Konu',
            'message'   => 'Mesaj',
        ])->assertSessionHasErrors('kvkk');
    }

    public function test_iletisim_formu_basarili_gonderim(): void
    {
        $this->post('/iletisim', [
            'full_name' => 'Test Kullanıcı',
            'email'     => 'test@refik.test',
            'subject'   => 'Bağış sorusu',
            'message'   => 'Merhaba, bir sorum var.',
            'kvkk'      => '1',
        ])->assertSessionHas('contact_success');

        $this->assertDatabaseCount('contact_messages', 1);
        $msg = ContactMessage::first();
        $this->assertEquals('test@refik.test', $msg->email);
        $this->assertFalse($msg->is_read);
    }

    public function test_yardim_talebi_telefon_zorunludur(): void
    {
        $this->post('/yardim-talebi', [
            'full_name'   => 'Test',
            'city'        => 'Ankara',
            'district'    => 'Çankaya',
            'category'    => 'gida',
            'description' => 'Açıklama',
            'kvkk'        => '1',
        ])->assertSessionHasErrors('phone');
    }

    public function test_appointment_olusturma_basarili(): void
    {
        $this->post('/appointments', [
            'full_name' => 'Randevu Test',
            'email'     => 'randevu@refik.test',
            'phone'     => '+905551234567',
            'date'      => now()->addDays(3)->toDateString(),
            'time'      => '14:00',
            'topic'     => 'donation_advisory',
        ])->assertSessionHas('appointment_success');

        $this->assertDatabaseHas('appointments', [
            'email'  => 'randevu@refik.test',
            'topic'  => 'donation_advisory',
            'status' => 'pending',
        ]);
    }

    public function test_gecmis_tarih_appointment_reddedilir(): void
    {
        $this->post('/appointments', [
            'full_name' => 'Geç',
            'email'     => 'gec@refik.test',
            'phone'     => '+905551234567',
            'date'      => now()->subDay()->toDateString(),
            'time'      => '14:00',
            'topic'     => 'general',
        ])->assertSessionHasErrors('date');
    }
}
