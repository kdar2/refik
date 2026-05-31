<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Services\ApiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonateController extends Controller
{
    public function __construct(private readonly ApiService $api)
    {
    }

    public function show(Request $request): View
    {
        // Niyet seçenekleri
        
        $intentions = $this->api->collect('/api/v1/donations/intentions/');

        // Hızlı bağış türleri
        
        $quickDonations = $this->api->collect('/api/v1/donations/quick-donations/');

        // Kampanya bilgisi (opsiyonel)
        $campaign = null;
        if ($slug = $request->query('campaign')) {
            $campaign = $this->api->get("/api/v1/donations/projects/{$slug}") ?: null;
        }

        return view('pages.donate.index', [
            'campaign'       => $campaign,
            'intentions'     => $intentions,
            'quickDonations' => $quickDonations,
            'presets'        => config('currencies.donation_presets.TRY', []),
            'defaults'       => [
                'amount'    => (int) $request->query('amount', 100),
                'type'      => $request->query('type', 'general'),
                'frequency' => $request->query('frequency', 'one_time'),
            ],
        ]);
    }

    public function store(StoreDonationRequest $request): RedirectResponse
    {
        // Guest token yoksa al
        $this->api->ensureGuestToken();

        $validated = $request->validated();

        // 1. Sepete ekle
        $cartPayload = array_filter([
            'project_slug'  => $validated['campaign_slug'] ?? null,
            'project_id'    => $validated['campaign_id']   ?? null,
            'amount'        => $validated['amount'],
            'currency'      => $validated['currency']  ?? 'TRY',
            'type'          => $validated['type']       ?? 'general',
            'frequency'     => $validated['frequency']  ?? 'one_time',
            'intention'     => $validated['intention']  ?? null,
            'intention_for' => $validated['intention_for'] ?? null,
            'note'          => $validated['message']    ?? null,
        ], fn ($v) => $v !== null);

        $cartResult = $this->api->post('/api/v1/cart/add/', $cartPayload);

        if (empty($cartResult) || isset($cartResult['error'])) {
            return back()
                ->withInput($request->except(['card_number', 'card_cvv']))
                ->with('payment_error', 'Sepet işlemi sırasında bir hata oluştu. Lütfen tekrar deneyin.');
        }

        // 2. Ödemeyi başlat
        $paymentPayload = array_merge($validated, [
            'cart_id'      => data_get($cartResult, 'cart_id') ?? data_get($cartResult, 'id'),
            'card_number'  => $request->input('card_number'),
            'card_expiry'  => $request->input('card_expiry'),
            'card_cvv'     => $request->input('card_cvv'),
            'card_holder'  => $request->input('card_holder'),
        ]);

        $paymentResult = $this->api->post('/api/v1/payments/initiate/', array_filter($paymentPayload));

        $reference = data_get($paymentResult, 'reference_number')
            ?? data_get($paymentResult, 'reference')
            ?? data_get($paymentResult, 'order_id');

        if (!empty($reference) && empty($paymentResult['error'])) {
            return redirect()->route('donate.thank-you', $reference);
        }

        $errorMsg = data_get($paymentResult, 'message')
            ?? data_get($paymentResult, 'error')
            ?? 'Ödeme alınamadı. Lütfen kart bilgilerinizi kontrol edip tekrar deneyin.';

        return back()
            ->withInput($request->except(['card_number', 'card_cvv']))
            ->with('payment_error', $errorMsg);
    }

    public function thankYou(string $reference): View
    {
        // API'den bağış detayını çek, bulunamazsa sadece reference göster
        $donation = $this->api->get("/api/v1/payments/{$reference}/") ?: ['reference' => $reference];

        return view('pages.donate.thank-you', compact('donation'));
    }
}
