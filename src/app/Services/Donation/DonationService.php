<?php

namespace App\Services\Donation;

use App\Models\Campaign;
use App\Models\Donation;
use App\Services\CurrencyConverter;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Support\Facades\DB;

class DonationService
{
    public function __construct(private readonly PaymentGatewayInterface $gateway)
    {
    }

    /**
     * Bağışı kaydet, ödemeyi al, kampanya toplamlarını güncelle.
     * Validated $payload bekler — sanitize edilmiş, fakat amount_try, currency hesabı burada yapılır.
     */
    public function process(array $payload, ?array $cardData = null): Donation
    {
        return DB::transaction(function () use ($payload, $cardData) {
            $currency = strtoupper($payload['currency'] ?? 'TRY');
            $amount   = (float) $payload['amount'];

            $donation = Donation::create([
                'user_id'       => auth()->id(),
                'campaign_id'   => $payload['campaign_id'] ?? null,

                'donor_name'    => $payload['donor_name'],
                'donor_email'   => $payload['donor_email'],
                'donor_phone'   => $payload['donor_phone'] ?? null,
                'tckn'          => $payload['tckn'] ?? null,

                'company_name'  => $payload['company_name'] ?? null,
                'tax_office'    => $payload['tax_office'] ?? null,
                'tax_no'        => $payload['tax_no'] ?? null,
                'is_corporate'  => !empty($payload['is_corporate']),

                'amount'        => $amount,
                'currency'      => $currency,
                'amount_try'    => CurrencyConverter::toTry($amount, $currency),

                'type'          => $payload['type']      ?? 'general',
                'frequency'     => $payload['frequency'] ?? 'one_time',
                'is_recurring'  => ($payload['frequency'] ?? 'one_time') !== 'one_time',
                'next_charge_at'=> $this->nextChargeDate($payload['frequency'] ?? 'one_time'),

                'intention'     => $payload['intention']     ?? null,
                'intention_for' => $payload['intention_for'] ?? null,
                'message'       => $payload['message']       ?? null,

                'payment_method'   => $payload['payment_method'] ?? 'credit_card',
                'payment_status'   => 'pending',
                'payment_provider' => $this->gateway->name(),

                'certificate_requested' => !empty($payload['certificate_requested']),
            ]);

            // Banka havalesi seçildiyse pending bırak.
            if ($donation->payment_method === 'bank_transfer') {
                return $donation;
            }

            $result = $this->gateway->charge($donation, $cardData ?? []);

            $donation->fill([
                'payment_status'         => $result['success'] ? 'completed' : 'failed',
                'payment_transaction_id' => $result['transaction_id'] ?? null,
                'payment_response'       => $result['response'] ?? null,
                'completed_at'           => $result['success'] ? now() : null,
            ])->save();

            // Kampanya toplamını güncelle (yalnızca tamamlanan bağışlarda).
            if ($result['success'] && $donation->campaign_id) {
                Campaign::where('id', $donation->campaign_id)->update([
                    'raised_amount' => DB::raw("raised_amount + " . (float) $donation->amount_try),
                    'donor_count'   => DB::raw('donor_count + 1'),
                ]);
            }

            return $donation;
        });
    }

    private function nextChargeDate(string $frequency): ?string
    {
        return match ($frequency) {
            'monthly'   => now()->addMonth()->toDateString(),
            'quarterly' => now()->addMonths(3)->toDateString(),
            'yearly'    => now()->addYear()->toDateString(),
            default     => null,
        };
    }
}
