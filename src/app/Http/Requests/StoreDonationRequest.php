<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Adım 1 — tutar & tür
            'campaign_id'   => ['nullable', 'integer'],
            'campaign_slug' => ['nullable', 'string', 'max:191'],
            'amount'        => ['required', 'numeric', 'min:1', 'max:1000000'],
            'currency'      => ['required', Rule::in(['TRY', 'USD', 'EUR'])],
            'type'          => ['required', Rule::in(['general', 'zakat', 'fitre', 'sadaka', 'kurban', 'adak', 'kefaret'])],
            'frequency'     => ['required', Rule::in(['one_time', 'monthly', 'quarterly', 'yearly'])],

            // Adım 2 — bağışçı bilgileri
            'donor_name'    => ['required', 'string', 'max:120'],
            'donor_email'   => ['required', 'email', 'max:255'],
            'donor_phone'   => ['nullable', 'string', 'max:30'],
            'tckn'          => ['nullable', 'string', 'size:11'],

            'is_corporate'  => ['nullable', 'boolean'],
            'company_name'  => ['nullable', 'required_if:is_corporate,1', 'string', 'max:255'],
            'tax_office'    => ['nullable', 'required_if:is_corporate,1', 'string', 'max:120'],
            'tax_no'        => ['nullable', 'required_if:is_corporate,1', 'string', 'max:20'],

            'kvkk'          => ['accepted'],

            // Adım 3 — niyet
            'intention'             => ['nullable', 'string', 'max:120'],
            'intention_for'         => ['nullable', 'string', 'max:120'],
            'message'               => ['nullable', 'string', 'max:1000'],
            'certificate_requested' => ['nullable', 'boolean'],

            // Adım 4 — ödeme
            'payment_method'        => ['required', Rule::in(['credit_card', 'bank_transfer'])],

            // Kart bilgileri sadece credit_card seçildiyse zorunlu
            'card_number'           => ['nullable', 'required_if:payment_method,credit_card', 'string', 'min:12', 'max:25'],
            'card_holder'           => ['nullable', 'required_if:payment_method,credit_card', 'string', 'max:120'],
            'card_expiry'           => ['nullable', 'required_if:payment_method,credit_card', 'string', 'regex:/^(0[1-9]|1[0-2])\/\d{2,4}$/'],
            'card_cvv'              => ['nullable', 'required_if:payment_method,credit_card', 'string', 'min:3', 'max:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'kvkk.accepted'  => 'Devam etmek için KVKK aydınlatma metnini onaylamanız gerekir.',
            'card_expiry.regex' => 'Son kullanma tarihi AA/YY veya AA/YYYY biçiminde olmalıdır.',
        ];
    }

    public function cardData(): array
    {
        return [
            'number' => $this->input('card_number'),
            'holder' => $this->input('card_holder'),
            'expiry' => $this->input('card_expiry'),
            'cvv'    => $this->input('card_cvv'),
        ];
    }
}
