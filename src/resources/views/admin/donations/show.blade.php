@extends('admin.layout')

@section('title', $donation->reference)
@section('header', 'Bağış: ' . $donation->reference)

@section('header_actions')
    <a href="{{ route('admin.donations.index') }}" class="btn-ghost btn-sm">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Geri
    </a>
@endsection

@section('content')

<div class="grid lg:grid-cols-[1fr_320px] gap-6">

    <div class="space-y-5">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6">
            <div class="flex items-center gap-4 mb-5">
                <div class="grid place-items-center w-14 h-14 rounded-2xl
                    @if($donation->payment_status==='completed') bg-emerald-100 text-emerald-700
                    @elseif($donation->payment_status==='pending') bg-amber-100 text-amber-700
                    @else bg-rose-100 text-rose-700 @endif">
                    <i data-lucide="hand-coins" class="w-6 h-6"></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold">Tutar</p>
                    <p class="text-3xl font-extrabold font-display text-brand-900">{{ App\Support\Money::format($donation->amount, $donation->currency) }}</p>
                    @if ($donation->amount_try != $donation->amount)
                        <p class="text-xs text-slate-500">≈ {{ App\Support\Money::tl($donation->amount_try) }}</p>
                    @endif
                </div>
            </div>
            <hr class="border-slate-100 my-4">

            <dl class="grid sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                <div><dt class="text-xs uppercase text-slate-500">Tür</dt><dd class="mt-0.5 font-semibold">{{ ucfirst($donation->type) }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Sıklık</dt><dd class="mt-0.5 font-semibold">{{ $donation->frequency }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Yöntem</dt><dd class="mt-0.5 font-semibold">{{ $donation->payment_method }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Sağlayıcı</dt><dd class="mt-0.5 font-semibold">{{ $donation->payment_provider ?? '—' }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Transaction ID</dt><dd class="mt-0.5 font-mono text-xs">{{ $donation->payment_transaction_id ?? '—' }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Sonraki çekim</dt><dd class="mt-0.5 font-semibold">{{ $donation->next_charge_at?->format('d M Y') ?? '—' }}</dd></div>
                @if ($donation->intention)
                    <div><dt class="text-xs uppercase text-slate-500">Niyet</dt><dd class="mt-0.5">{{ $donation->intention }}</dd></div>
                @endif
                @if ($donation->intention_for)
                    <div><dt class="text-xs uppercase text-slate-500">Niyet sahibi</dt><dd class="mt-0.5">{{ $donation->intention_for }}</dd></div>
                @endif
            </dl>

            @if ($donation->message)
                <div class="mt-5 rounded-xl bg-slate-50 border border-slate-200 p-4">
                    <p class="text-xs uppercase text-slate-500 font-semibold mb-1.5">Mesaj</p>
                    <p class="text-sm text-slate-700">{{ $donation->message }}</p>
                </div>
            @endif
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6">
            <h3 class="font-bold text-brand-900 mb-4">Bağışçı</h3>
            <dl class="grid sm:grid-cols-2 gap-y-3 gap-x-6 text-sm">
                <div><dt class="text-xs uppercase text-slate-500">Ad Soyad</dt><dd class="mt-0.5 font-semibold">{{ $donation->donor_name }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">E-posta</dt><dd class="mt-0.5 font-semibold">{{ $donation->donor_email }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">Telefon</dt><dd class="mt-0.5 font-semibold">{{ $donation->donor_phone ?? '—' }}</dd></div>
                <div><dt class="text-xs uppercase text-slate-500">TCKN</dt><dd class="mt-0.5 font-mono">{{ $donation->tckn ?? '—' }}</dd></div>
                @if ($donation->is_corporate)
                    <div><dt class="text-xs uppercase text-slate-500">Şirket</dt><dd class="mt-0.5 font-semibold">{{ $donation->company_name }}</dd></div>
                    <div><dt class="text-xs uppercase text-slate-500">Vergi</dt><dd class="mt-0.5 font-semibold">{{ $donation->tax_office }} / {{ $donation->tax_no }}</dd></div>
                @endif
            </dl>
        </div>

        @if ($donation->payment_response)
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6">
                <h3 class="font-bold text-brand-900 mb-3">Sağlayıcı Yanıtı (Raw)</h3>
                <pre class="text-xs bg-slate-900 text-slate-100 rounded-lg p-4 overflow-auto">{{ json_encode($donation->payment_response, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        @endif
    </div>

    <aside class="space-y-4 lg:sticky lg:top-20 lg:self-start">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <p class="text-xs uppercase text-slate-500 font-semibold">Durum</p>
            <p class="mt-1 font-bold text-base">
                @switch($donation->payment_status)
                    @case('completed') <span class="text-emerald-600">Tamamlandı</span>@break
                    @case('pending')   <span class="text-amber-600">Bekliyor</span>@break
                    @case('failed')    <span class="text-rose-600">Başarısız</span>@break
                    @default           {{ $donation->payment_status }}
                @endswitch
            </p>
            <p class="mt-3 text-xs uppercase text-slate-500 font-semibold">Tarih</p>
            <p class="mt-1 font-semibold">{{ $donation->created_at?->translatedFormat('d F Y, H:i') }}</p>
            @if ($donation->completed_at)
                <p class="mt-3 text-xs uppercase text-slate-500 font-semibold">Tamamlanma</p>
                <p class="mt-1 font-semibold">{{ $donation->completed_at->translatedFormat('d F Y, H:i') }}</p>
            @endif
        </div>

        @if ($donation->campaign)
            <a href="{{ route('admin.campaigns.edit', $donation->campaign) }}"
               class="block rounded-2xl bg-white border border-slate-200 hover:border-brand-300 hover:shadow-md p-5 transition">
                <p class="text-xs uppercase text-slate-500 font-semibold">Kampanya</p>
                <p class="mt-1 font-bold text-brand-900 group-hover:text-accent-600">{{ $donation->campaign->title_tr }}</p>
                <p class="mt-2 text-xs text-slate-500">{{ $donation->campaign->category?->name_tr }}</p>
            </a>
        @endif
    </aside>
</div>

@endsection
