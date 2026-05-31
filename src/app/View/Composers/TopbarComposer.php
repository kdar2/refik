<?php

namespace App\View\Composers;

use App\Models\Donation;
use App\Services\Cart\DonationCart;
use App\Services\HijriDateService;
use App\Services\PrayerTimeService;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TopbarComposer
{
    public function __construct(
        private readonly HijriDateService $hijri,
        private readonly PrayerTimeService $prayer,
        private readonly DonationCart $cart,
    ) {
    }

    public function compose(View $view): void
    {
        $currency = config('currencies.active', config('currencies.default', 'TRY'));

        $view->with([
            'topbarHijri'         => $this->hijri->formatted(),
            'topbarNextPrayer'    => $this->prayer->next(),
            'topbarLastDonation'  => $this->lastDonation(),
            'topbarCartCount'     => $this->cart->count(),
            'topbarCartTotal'     => $this->cart->totalFormatted($currency),
        ]);
    }

    /**
     * @return array{donor:string,amount:string,campaign:?string}|null
     */
    private function lastDonation(): ?array
    {
        return Cache::remember('topbar:last-donation', now()->addMinutes(2), function () {
            $donation = Donation::query()
                ->completed()
                ->with('campaign:id,title_tr')
                ->latest('completed_at')
                ->first();

            if (!$donation) {
                return null;
            }

            $donor = trim((string) $donation->donor_name) !== ''
                ? $this->maskName((string) $donation->donor_name)
                : 'Anonim';

            $amount = number_format((float) $donation->amount, 0, ',', '.') . ' ' . $this->symbol((string) $donation->currency);

            return [
                'donor'    => $donor,
                'amount'   => $amount,
                'campaign' => $donation->campaign?->title_tr,
            ];
        });
    }

    /**
     * "Furkan Soydaş" -> "Furkan S." gibi maskeleyerek gizlilik sağlar.
     */
    private function maskName(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        if (count($parts) < 2) {
            return $name;
        }
        $last = array_pop($parts);
        return implode(' ', $parts) . ' ' . mb_substr($last, 0, 1) . '.';
    }

    private function symbol(string $currency): string
    {
        return config("currencies.meta.$currency.symbol", '₺');
    }
}
