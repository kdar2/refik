@extends('admin.layout')

@section('title', 'Bağışlar')
@section('header', 'Bağışlar')

@section('header_actions')
    <a href="{{ route('admin.donations.export', request()->query()) }}" class="btn-outline btn-sm">
        <i data-lucide="download" class="w-4 h-4"></i> CSV İndir
    </a>
@endsection

@section('content')

{{-- KPI özet --}}
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
        <p class="text-xs uppercase text-emerald-700 font-semibold">Toplam (Filtre)</p>
        <p class="mt-1 text-2xl font-extrabold font-display text-emerald-700">{{ App\Support\Money::tl($stats['total_completed']) }}</p>
    </div>
    <div class="rounded-2xl bg-brand-50 border border-brand-100 p-4">
        <p class="text-xs uppercase text-brand-700 font-semibold">Tüm İşlem</p>
        <p class="mt-1 text-2xl font-extrabold font-display text-brand-900">{{ number_format($stats['count_total']) }}</p>
    </div>
    <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-4">
        <p class="text-xs uppercase text-emerald-700 font-semibold">Tamamlanan</p>
        <p class="mt-1 text-2xl font-extrabold font-display text-emerald-700">{{ number_format($stats['count_completed']) }}</p>
    </div>
    <div class="rounded-2xl bg-amber-50 border border-amber-100 p-4">
        <p class="text-xs uppercase text-amber-700 font-semibold">Bekleyen</p>
        <p class="mt-1 text-2xl font-extrabold font-display text-amber-700">{{ number_format($stats['count_pending']) }}</p>
    </div>
</div>

<div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">

    {{-- Filtreler --}}
    <form method="GET" action="{{ route('admin.donations.index') }}" class="p-4 border-b border-slate-100 grid lg:grid-cols-6 gap-2">
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Referans, isim, e-posta…" class="input !py-2 lg:col-span-2">
        <select name="status" class="input !py-2">
            <option value="">Tüm durumlar</option>
            @foreach (['completed' => 'Tamamlandı','pending' => 'Bekliyor','failed' => 'Başarısız','cancelled' => 'İptal','refunded' => 'İade'] as $val => $label)
                <option value="{{ $val }}" @selected(($filters['status'] ?? '')===$val)>{{ $label }}</option>
            @endforeach
        </select>
        <select name="type" class="input !py-2">
            <option value="">Tüm türler</option>
            @foreach (['general','zakat','fitre','sadaka','kurban','adak','kefaret'] as $t)
                <option value="{{ $t }}" @selected(($filters['type'] ?? '')===$t)>{{ ucfirst($t) }}</option>
            @endforeach
        </select>
        <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="input !py-2">
        <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="input !py-2">
        <button class="btn-primary btn-sm lg:col-span-6">Filtrele</button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold">Referans</th>
                    <th class="text-left px-4 py-3 font-semibold">Bağışçı</th>
                    <th class="text-left px-4 py-3 font-semibold">Kampanya</th>
                    <th class="text-right px-4 py-3 font-semibold">Tutar</th>
                    <th class="text-center px-4 py-3 font-semibold">Tür</th>
                    <th class="text-center px-4 py-3 font-semibold">Sıklık</th>
                    <th class="text-center px-4 py-3 font-semibold">Durum</th>
                    <th class="text-left px-4 py-3 font-semibold">Tarih</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($donations as $d)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs font-bold text-brand-900">{{ $d->reference }}</td>
                        <td class="px-4 py-3">
                            <p class="font-bold text-brand-900 text-xs">{{ $d->donor_name }}</p>
                            <p class="text-[11px] text-slate-500 truncate max-w-[160px]">{{ $d->donor_email }}</p>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-700 truncate max-w-[180px]">{{ $d->campaign?->title_tr ?? '—' }}</td>
                        <td class="px-4 py-3 text-right font-bold text-brand-900 text-xs">{{ App\Support\Money::format($d->amount, $d->currency) }}</td>
                        <td class="px-4 py-3 text-center text-xs">{{ ucfirst($d->type) }}</td>
                        <td class="px-4 py-3 text-center text-xs text-slate-600">
                            @switch($d->frequency)
                                @case('monthly') Aylık @break
                                @case('quarterly') 3 Aylık @break
                                @case('yearly') Yıllık @break
                                @default Tek
                            @endswitch
                        </td>
                        <td class="px-4 py-3 text-center text-xs">
                            @switch($d->payment_status)
                                @case('completed') <span class="badge bg-emerald-100 text-emerald-700">Tamam</span>@break
                                @case('pending')   <span class="badge bg-amber-100 text-amber-700">Bekliyor</span>@break
                                @case('failed')    <span class="badge bg-rose-100 text-rose-700">Başarısız</span>@break
                                @default           <span class="badge bg-slate-100 text-slate-700">{{ $d->payment_status }}</span>
                            @endswitch
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">{{ $d->created_at?->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.donations.show', $d) }}" class="grid place-items-center w-8 h-8 rounded-lg hover:bg-brand-50 text-brand-700" title="Detay">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-slate-500 py-10">Kayıt yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-slate-100">{{ $donations->onEachSide(1)->links() }}</div>
</div>

@endsection
