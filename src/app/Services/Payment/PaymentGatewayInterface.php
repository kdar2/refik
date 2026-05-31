<?php

namespace App\Services\Payment;

use App\Models\Donation;

/**
 * Tüm ödeme sağlayıcıları (iyzico, PayTR, mock vb.) bu sözleşmeyi uygular.
 * `charge()` döndüreceği DTO şu yapıdadır:
 *   ['success' => bool, 'transaction_id' => ?string, 'response' => array, 'error' => ?string]
 */
interface PaymentGatewayInterface
{
    public function name(): string;

    public function charge(Donation $donation, array $cardData = []): array;
}
