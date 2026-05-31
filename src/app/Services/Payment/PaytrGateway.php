<?php

namespace App\Services\Payment;

use App\Models\Donation;

/**
 * PayTR entegrasyon stub'ı — Faz 4 sonunda iframe linki + IPN webhook ile doldurulacak.
 */
class PaytrGateway implements PaymentGatewayInterface
{
    public function name(): string
    {
        return 'paytr';
    }

    public function charge(Donation $donation, array $cardData = []): array
    {
        throw new \RuntimeException('PaytrGateway henüz uygulanmadı; payment.gateway=fake kullanın.');
    }
}
