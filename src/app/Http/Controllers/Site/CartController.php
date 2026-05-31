<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly ApiService $api)
    {
    }

    public function show(): View
    {
        $this->ensureToken();

        $raw   = $this->api->get('/api/v1/cart/');
        $items = collect(data_get($raw, 'items', data_get($raw, 'results', [])));
        $total = data_get($raw, 'total_formatted', data_get($raw, 'total', '0 ₺'));
        $count = $items->count();

        return view('pages.cart', compact('items', 'total', 'count'));
    }

    public function add(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'amount'        => ['required', 'numeric', 'min:1', 'max:1000000'],
            'currency'      => ['nullable', 'string', 'in:TRY,USD,EUR'],
            'type'          => ['nullable', 'string', 'max:32'],
            'frequency'     => ['nullable', 'string', 'in:one_time,monthly,quarterly,yearly'],
            'campaign_slug' => ['nullable', 'string', 'max:191'],
            'campaign_id'   => ['nullable', 'integer'],
            'intention'     => ['nullable', 'string', 'max:64'],
            'intention_for' => ['nullable', 'string', 'max:191'],
            'note'          => ['nullable', 'string', 'max:500'],
        ]);

        $this->ensureToken();

        $payload = array_filter([
            'project_slug'  => $data['campaign_slug'] ?? null,
            'project_id'    => $data['campaign_id']   ?? null,
            'amount'        => $data['amount'],
            'currency'      => $data['currency']  ?? 'TRY',
            'type'          => $data['type']       ?? 'general',
            'frequency'     => $data['frequency']  ?? 'one_time',
            'intention'     => $data['intention']  ?? null,
            'intention_for' => $data['intention_for'] ?? null,
            'note'          => $data['note']       ?? null,
        ], fn ($v) => $v !== null);

        $result = $this->api->post('/api/v1/cart/add/', $payload);

        $count = (int) data_get($result, 'count', data_get($result, 'items_count', 0));
        $total = data_get($result, 'total_formatted', data_get($result, 'total', '0 ₺'));

        if ($request->wantsJson()) {
            return response()->json(compact('count', 'total'));
        }

        return back()->with('cart_status', 'Sepetinize eklendi.');
    }

    public function remove(string $itemId, Request $request): RedirectResponse|JsonResponse
    {
        $this->ensureToken();

        $result = $this->api->delete("/api/v1/cart/items/{$itemId}/remove/");

        $count = (int) data_get($result, 'count', data_get($result, 'items_count', 0));
        $total = data_get($result, 'total_formatted', data_get($result, 'total', '0 ₺'));

        if ($request->wantsJson()) {
            return response()->json(compact('count', 'total'));
        }

        return back()->with('cart_status', 'Sepetten kaldırıldı.');
    }

    public function clear(Request $request): RedirectResponse|JsonResponse
    {
        $this->ensureToken();

        $result = $this->api->post('/api/v1/cart/clear/');

        $count = 0;
        $total = data_get($result, 'total_formatted', '0 ₺');

        if ($request->wantsJson()) {
            return response()->json(compact('count', 'total'));
        }

        return back()->with('cart_status', 'Sepet temizlendi.');
    }

    /**
     * Cart işlemleri için token gerekiyor.
     * Kullanıcı giriş yapmamışsa guest token al.
     */
    private function ensureToken(): void
    {
        $this->api->ensureGuestToken();
    }
}
