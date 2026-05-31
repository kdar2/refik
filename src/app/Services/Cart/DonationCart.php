<?php

namespace App\Services\Cart;

use App\Models\Campaign;
use App\Services\CurrencyConverter;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Str;

/**
 * Session içinde tutulan bağış sepeti.
 * Sepetteki her satır bir kalemdir (kampanya/tür + tutar + sıklık).
 */
class DonationCart
{
    private const SESSION_KEY = 'donation_cart';

    public function __construct(private readonly Session $session)
    {
    }

    /**
     * Sepete kalem ekler ve oluşturulan kalem id'sini döner.
     *
     * @param array{
     *     amount: float|int|string,
     *     currency?: string,
     *     type?: string,
     *     frequency?: string,
     *     campaign_id?: int|null,
     *     campaign_slug?: string|null,
     *     intention?: string|null,
     *     intention_for?: string|null,
     *     note?: string|null,
     * } $payload
     */
    public function add(array $payload): string
    {
        $amount   = max(1, (float) ($payload['amount'] ?? 0));
        $currency = strtoupper($payload['currency'] ?? 'TRY');

        $campaignTitle = null;
        $campaignSlug  = $payload['campaign_slug'] ?? null;
        $campaignId    = $payload['campaign_id']   ?? null;

        if ($campaignSlug && !$campaignId) {
            $campaign = Campaign::active()->where('slug', $campaignSlug)->first();
            if ($campaign) {
                $campaignId    = $campaign->id;
                $campaignTitle = $campaign->title_tr;
            }
        } elseif ($campaignId) {
            $campaign = Campaign::find($campaignId);
            $campaignTitle = $campaign?->title_tr;
            $campaignSlug  = $campaign?->slug;
        }

        $item = [
            'id'             => (string) Str::uuid(),
            'amount'         => $amount,
            'currency'       => $currency,
            'amount_try'     => CurrencyConverter::toTry($amount, $currency),
            'type'           => $payload['type']      ?? 'general',
            'frequency'      => $payload['frequency'] ?? 'one_time',
            'campaign_id'    => $campaignId,
            'campaign_slug'  => $campaignSlug,
            'campaign_title' => $campaignTitle,
            'intention'      => $payload['intention']     ?? null,
            'intention_for'  => $payload['intention_for'] ?? null,
            'note'           => $payload['note']          ?? null,
            'added_at'       => now()->toIso8601String(),
        ];

        $items = $this->items();
        $items[$item['id']] = $item;
        $this->session->put(self::SESSION_KEY, $items);

        return $item['id'];
    }

    public function remove(string $itemId): void
    {
        $items = $this->items();
        unset($items[$itemId]);
        $this->session->put(self::SESSION_KEY, $items);
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    /** @return array<string,array<string,mixed>> */
    public function items(): array
    {
        $items = $this->session->get(self::SESSION_KEY, []);
        return is_array($items) ? $items : [];
    }

    public function count(): int
    {
        return count($this->items());
    }

    public function totalTry(): float
    {
        return array_reduce(
            $this->items(),
            fn (float $carry, array $item) => $carry + (float) ($item['amount_try'] ?? 0),
            0.0,
        );
    }

    public function totalFormatted(string $currency = 'TRY'): string
    {
        $total = $this->totalTry();
        if ($currency !== 'TRY') {
            $total = CurrencyConverter::convert($total, 'TRY', $currency);
        }

        $meta   = config("currencies.meta.$currency", config('currencies.meta.TRY'));
        $number = number_format(
            $total,
            $meta['decimals']      ?? 2,
            $meta['decimal_sep']   ?? ',',
            $meta['thousands_sep'] ?? '.',
        );

        $symbol = $meta['symbol'] ?? '₺';
        return ($meta['symbol_position'] ?? 'after') === 'before'
            ? $symbol . $number
            : $number . ' ' . $symbol;
    }
}
