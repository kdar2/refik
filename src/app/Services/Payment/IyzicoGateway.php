<?php

namespace App\Services\Payment;

use App\Models\Donation;

/**
 * iyzico entegrasyon stub'ı — Faz 4 sonunda iyzipay-php SDK ile doldurulacak.
 * Şu an exception fırlatır; production .env'de `payment.gateway=iyzico` ayarlanır.
 */
class IyzicoGateway implements PaymentGatewayInterface
{
    public function name(): string
    {
        return 'iyzico';
    }

    public function charge(Donation $donation, array $cardData = []): array
    {
        throw new \RuntimeException('IyzicoGateway henüz uygulanmadı; payment.gateway=fake kullanın.');
    }
}
