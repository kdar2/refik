<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\Cart\DonationCart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private readonly DonationCart $cart)
    {
    }

    public function show(): View
    {
        return view('pages.cart', [
            'items' => $this->cart->items(),
            'total' => $this->cart->totalFormatted(),
            'count' => $this->cart->count(),
        ]);
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

        $this->cart->add($data);

        if ($request->wantsJson()) {
            return response()->json([
                'count' => $this->cart->count(),
                'total' => $this->cart->totalFormatted(),
            ]);
        }

        return back()->with('cart_status', 'Sepetinize eklendi.');
    }

    public function remove(string $itemId, Request $request): RedirectResponse|JsonResponse
    {
        $this->cart->remove($itemId);

        if ($request->wantsJson()) {
            return response()->json([
                'count' => $this->cart->count(),
                'total' => $this->cart->totalFormatted(),
            ]);
        }

        return back()->with('cart_status', 'Sepetten kaldırıldı.');
    }

    public function clear(Request $request): RedirectResponse|JsonResponse
    {
        $this->cart->clear();

        if ($request->wantsJson()) {
            return response()->json(['count' => 0, 'total' => $this->cart->totalFormatted()]);
        }

        return back()->with('cart_status', 'Sepet temizlendi.');
    }
}
