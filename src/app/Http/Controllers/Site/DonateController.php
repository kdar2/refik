<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonationRequest;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\DonationIntention;
use App\Services\Donation\DonationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DonateController extends Controller
{
    public function show(Request $request): View
    {
        $campaign = null;
        if ($slug = $request->query('campaign')) {
            $campaign = Campaign::active()->with(['category', 'country'])->where('slug', $slug)->first();
        }

        return view('pages.donate.index', [
            'campaign'   => $campaign,
            'intentions' => DonationIntention::active()->ordered()->get(),
            'presets'    => config('currencies.donation_presets.TRY'),
            'defaults'   => [
                'amount'    => (int) $request->query('amount', 100),
                'type'      => $request->query('type', 'general'),
                'frequency' => $request->query('frequency', 'one_time'),
            ],
        ]);
    }

    public function store(StoreDonationRequest $request, DonationService $service): RedirectResponse
    {
        $donation = $service->process(
            $request->validated(),
            $request->cardData(),
        );

        if ($donation->payment_status === 'completed' || $donation->payment_method === 'bank_transfer') {
            return redirect()->route('donate.thank-you', $donation->reference);
        }

        // Başarısız ödeme — formla geri dön
        return back()
            ->withInput($request->except(['card_number', 'card_cvv']))
            ->with('payment_error', 'Ödeme alınamadı. Lütfen kart bilgilerinizi kontrol edip tekrar deneyin.');
    }

    public function thankYou(string $reference): View
    {
        $donation = Donation::where('reference', $reference)
            ->with('campaign')
            ->firstOrFail();

        return view('pages.donate.thank-you', compact('donation'));
    }
}
