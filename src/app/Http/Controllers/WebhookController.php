<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Ödeme sağlayıcı webhook (IPN) alıcısı — stub.
 * Gerçek doğrulama (HMAC, IP kontrolü) Faz 4 sonunda iyzico/PayTR entegrasyonu açıldığında doldurulacak.
 */
class WebhookController extends Controller
{
    public function iyzico(Request $request): JsonResponse
    {
        Log::info('iyzico webhook', $request->all());

        $reference = $request->input('reference');
        $status    = $request->input('status'); // success | failure

        if ($reference && $status) {
            Donation::where('reference', $reference)->update([
                'payment_status'   => $status === 'success' ? 'completed' : 'failed',
                'payment_response' => $request->all(),
                'completed_at'     => $status === 'success' ? now() : null,
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function paytr(Request $request): JsonResponse
    {
        Log::info('paytr webhook', $request->all());

        // PayTR IPN beklenen yanıt: "OK"
        return response()->json(['status' => 'OK']);
    }
}
