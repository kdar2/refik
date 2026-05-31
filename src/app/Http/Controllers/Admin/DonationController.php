<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DonationController extends Controller
{
    public function index(Request $request): View
    {
        $q = $this->buildQuery($request);

        $stats = [
            'total_completed' => (float) (clone $q)->where('payment_status', 'completed')->sum('amount_try'),
            'count_total'     => (clone $q)->count(),
            'count_completed' => (clone $q)->where('payment_status', 'completed')->count(),
            'count_pending'   => (clone $q)->where('payment_status', 'pending')->count(),
        ];

        return view('admin.donations.index', [
            'donations' => $q->latest('id')->paginate(20)->withQueryString(),
            'filters'   => $request->only(['q', 'status', 'type', 'frequency', 'from', 'to']),
            'stats'     => $stats,
        ]);
    }

    public function show(Donation $donation): View
    {
        $donation->load(['campaign', 'user']);
        return view('admin.donations.show', compact('donation'));
    }

    public function export(Request $request): StreamedResponse
    {
        $q = $this->buildQuery($request)->with('campaign');
        $filename = 'bagislar-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($q) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, [
                'Referans', 'Tarih', 'Bağışçı', 'Email', 'Telefon', 'TCKN',
                'Kampanya', 'Tür', 'Sıklık', 'Tutar', 'Para', 'TL Karşılığı',
                'Yöntem', 'Durum', 'Sağlayıcı', 'Transaction ID',
            ]);

            $q->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $d) {
                    fputcsv($out, [
                        $d->reference,
                        $d->created_at?->format('Y-m-d H:i'),
                        $d->donor_name,
                        $d->donor_email,
                        $d->donor_phone,
                        $d->tckn,
                        $d->campaign?->title_tr,
                        $d->type,
                        $d->frequency,
                        $d->amount,
                        $d->currency,
                        $d->amount_try,
                        $d->payment_method,
                        $d->payment_status,
                        $d->payment_provider,
                        $d->payment_transaction_id,
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function buildQuery(Request $request)
    {
        $q = Donation::with('campaign');

        if ($s = $request->query('q')) {
            $q->where(function ($qq) use ($s) {
                $qq->where('reference', 'like', "%{$s}%")
                   ->orWhere('donor_name', 'like', "%{$s}%")
                   ->orWhere('donor_email', 'like', "%{$s}%");
            });
        }
        if ($status = $request->query('status'))   $q->where('payment_status', $status);
        if ($type = $request->query('type'))       $q->where('type', $type);
        if ($freq = $request->query('frequency'))  $q->where('frequency', $freq);
        if ($from = $request->query('from'))       $q->whereDate('created_at', '>=', $from);
        if ($to = $request->query('to'))           $q->whereDate('created_at', '<=', $to);

        return $q;
    }
}
