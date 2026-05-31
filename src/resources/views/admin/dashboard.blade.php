@extends('admin.layout')

@section('title', 'Panel')
@section('header', 'Panel')

@section('content')

{{-- Üst KPI kartları --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold">Toplam Bağış (TL)</p>
        <p class="mt-2 text-2xl lg:text-3xl font-extrabold font-display text-emerald-600">
            {{ App\Support\Money::tl($totalRaised) }}
        </p>
        <p class="mt-1 text-xs text-slate-500">{{ number_format($totalDonations) }} işlem</p>
    </div>
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold">Son 7 Gün</p>
        <p class="mt-2 text-2xl lg:text-3xl font-extrabold font-display text-brand-700">
            {{ App\Support\Money::tl($weeklyRaised) }}
        </p>
        <p class="mt-1 text-xs text-slate-500">{{ $weeklyDonations }} bağış</p>
    </div>
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold">Bekleyen İletişim</p>
        <p class="mt-2 text-2xl lg:text-3xl font-extrabold font-display text-amber-600">
            {{ $pendingItems['contact_messages'] }}
        </p>
        <p class="mt-1 text-xs text-slate-500">okunmamış mesaj</p>
    </div>
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <p class="text-xs uppercase tracking-wider text-slate-500 font-semibold">Bültene Abone</p>
        <p class="mt-2 text-2xl lg:text-3xl font-extrabold font-display text-brand-900">
            {{ number_format($pendingItems['newsletter_subs']) }}
        </p>
        <p class="mt-1 text-xs text-slate-500">aktif abone</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">

    {{-- Son 7 gün — basit bar chart (saf SVG) --}}
    <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-brand-900">Son 7 Gün — Günlük Bağış Toplamı</h2>
            <span class="text-xs text-slate-500">TL</span>
        </div>
        @php($maxVal = max($daily->pluck('total')->max(), 1))
        <div class="grid grid-cols-7 gap-2 items-end h-44">
            @foreach ($daily as $d)
                @php($pct = (int) round(($d['total'] / $maxVal) * 100))
                <div class="flex flex-col items-center justify-end h-full gap-1.5">
                    <div class="w-full bg-gradient-to-t from-accent-500 to-accent-300 rounded-t-md relative group transition-all"
                         style="height: {{ max(4, $pct) }}%;">
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-1 px-2 py-1 rounded bg-brand-900 text-white text-[10px] whitespace-nowrap opacity-0 group-hover:opacity-100 transition pointer-events-none">
                            {{ App\Support\Money::tl($d['total']) }} ({{ $d['count'] }} bağış)
                        </div>
                    </div>
                    <span class="text-[10px] text-slate-500">{{ $d['date'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Bekleyen aksiyonlar --}}
    <div class="rounded-2xl bg-white border border-slate-200 p-5 shadow-sm">
        <h2 class="font-bold text-brand-900 mb-3">Bekleyen Aksiyonlar</h2>
        <ul class="space-y-2 text-sm">
            <li class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2.5">
                <span class="flex items-center gap-2 text-slate-700">
                    <i data-lucide="mail" class="w-4 h-4 text-brand-500"></i> İletişim mesajları
                </span>
                <span class="font-bold text-brand-900">{{ $pendingItems['contact_messages'] }}</span>
            </li>
            <li class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2.5">
                <span class="flex items-center gap-2 text-slate-700">
                    <i data-lucide="heart-handshake" class="w-4 h-4 text-brand-500"></i> Gönüllü başvuruları
                </span>
                <span class="font-bold text-brand-900">{{ $pendingItems['volunteer_applications'] }}</span>
            </li>
            <li class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2.5">
                <span class="flex items-center gap-2 text-slate-700">
                    <i data-lucide="hand-helping" class="w-4 h-4 text-brand-500"></i> Yardım talepleri
                </span>
                <span class="font-bold text-brand-900">{{ $pendingItems['help_requests'] }}</span>
            </li>
            <li class="flex items-center justify-between rounded-lg bg-slate-50 px-3 py-2.5">
                <span class="flex items-center gap-2 text-slate-700">
                    <i data-lucide="calendar-clock" class="w-4 h-4 text-brand-500"></i> Randevular
                </span>
                <span class="font-bold text-brand-900">{{ $pendingItems['appointments'] }}</span>
            </li>
        </ul>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mt-6">

    {{-- Son bağışlar --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-brand-900">Son Bağışlar</h2>
            <a href="{{ route('admin.donations.index') }}" class="text-xs font-semibold text-accent-600 hover:underline">Tümü</a>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="text-left px-5 py-2 font-semibold">Bağışçı</th>
                    <th class="text-left px-5 py-2 font-semibold">Kampanya</th>
                    <th class="text-right px-5 py-2 font-semibold">Tutar</th>
                    <th class="text-right px-5 py-2 font-semibold">Durum</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($recentDonations as $d)
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-3">
                            <p class="font-bold text-brand-900 text-xs">{{ $d->donor_name }}</p>
                            <p class="text-[11px] text-slate-500">{{ $d->reference }}</p>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-700 truncate max-w-[180px]">
                            {{ $d->campaign?->title_tr ?? '—' }}
                        </td>
                        <td class="px-5 py-3 text-right font-bold text-brand-900 text-xs">
                            {{ App\Support\Money::format($d->amount, $d->currency) }}
                        </td>
                        <td class="px-5 py-3 text-right text-xs">
                            @switch($d->payment_status)
                                @case('completed') <span class="badge bg-emerald-100 text-emerald-700">Tamam</span>@break
                                @case('pending')   <span class="badge bg-amber-100 text-amber-700">Bekliyor</span>@break
                                @case('failed')    <span class="badge bg-rose-100 text-rose-700">Başarısız</span>@break
                                @default           <span class="badge bg-slate-100 text-slate-700">{{ $d->payment_status }}</span>
                            @endswitch
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-slate-500 py-6">Henüz bağış yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- En çok bağış toplayan kampanyalar --}}
    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-brand-900">En Çok Bağış Toplayan Kampanyalar</h2>
            <a href="{{ route('admin.campaigns.index') }}" class="text-xs font-semibold text-accent-600 hover:underline">Tümü</a>
        </div>
        <ul class="divide-y divide-slate-100">
            @forelse ($topCampaigns as $c)
                <li class="px-5 py-3 flex items-center gap-3">
                    <img src="{{ $c->cover_image }}" alt="" class="w-12 h-12 rounded-lg object-cover shrink-0">
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-brand-900 text-sm truncate">{{ $c->title_tr }}</p>
                        <div class="mt-1.5 flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $c->progress_percent }}%"></div>
                            </div>
                            <span class="text-[11px] text-slate-500 shrink-0">%{{ $c->progress_percent }}</span>
                        </div>
                    </div>
                    <p class="text-xs font-bold text-emerald-600 shrink-0">
                        {{ App\Support\Money::format($c->raised_amount, $c->currency) }}
                    </p>
                </li>
            @empty
                <li class="text-center text-slate-500 py-6">Aktif kampanya yok.</li>
            @endforelse
        </ul>
    </div>
</div>

@endsection
