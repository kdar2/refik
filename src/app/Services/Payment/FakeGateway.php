<?php

namespace App\Services\Payment;

use App\Models\Donation;
use Illuminate\Support\Str;

/**
 * Geliştirme & test için: kart numarası "4111111111111111" → success,
 * geri kalan tüm denemeler de varsayılan olarak success döner (sandbox).
 * Faz 4'te gerçek iyzico/PayTR entegrasyonu yapılana kadar kullanılır.
 */
class FakeGateway implements PaymentGatewayInterface
{
    public function name(): string
    {
        return 'fake';
    }

    public function charge(Donation $donation, array $cardData = []): array
    {
        // Sahte kart "4242424242424242" → fail (test için).
        $cardNumber = preg_replace('/\s+/', '', (string) ($cardData['number'] ?? ''));
        if ($cardNumber === '4242424242424242') {
            return [
                'success'        => false,
                'transaction_id' => null,
                'response'       => ['simulated' => true, 'reason' => 'card_declined'],
                'error'          => 'Banka tarafından reddedildi (test).',
            ];
        }

        // Bank transfer yöntemi: pending kalır, gerçek ödeme bekleyecek.
        if ($donation->payment_method === 'bank_transfer') {
            return [
                'success'        => false,
                'transaction_id' => null,
                'response'       => ['simulated' => true, 'awaiting' => 'bank_transfer'],
                'error'          => null,
            ];
        }

        return [
            'success'        => true,
            'transaction_id' => 'FAKE_' . strtoupper(Str::random(12)),
            'response'       => ['simulated' => true, 'amount' => (float) $donation->amount],
            'error'          => null,
        ];
    }
}
