@extends('admin.layout')

@section('title', 'Kampanyalar')
@section('header', 'Kampanyalar')

@section('header_actions')
    <a href="{{ route('admin.campaigns.create') }}" class="btn-accent btn-sm">
        <i data-lucide="plus" class="w-4 h-4"></i> Yeni Kampanya
    </a>
@endsection

@section('content')

<div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">

    {{-- Filtreler --}}
    <form method="GET" action="{{ route('admin.campaigns.index') }}" class="p-4 border-b border-slate-100 grid sm:grid-cols-[1fr_200px_200px_auto] gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="Kampanya başlığı ara…" class="input !py-2">
        <select name="category" class="input !py-2">
            <option value="">Tüm kategoriler</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->slug }}" @selected($category===$cat->slug)>{{ $cat->name_tr }}</option>
            @endforeach
        </select>
        <select name="status" class="input !py-2">
            <option value="">Tüm durumlar</option>
            <option value="active"  @selected($status==='active')>Aktif</option>
            <option value="passive" @selected($status==='passive')>Pasif</option>
        </select>
        <button class="btn-primary btn-sm">Filtrele</button>
    </form>

    {{-- Tablo --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold">Kampanya</th>
                    <th class="text-left px-4 py-3 font-semibold">Kategori</th>
                    <th class="text-right px-4 py-3 font-semibold">Toplanan</th>
                    <th class="text-right px-4 py-3 font-semibold">İlerleme</th>
                    <th class="text-center px-4 py-3 font-semibold">Bayraklar</th>
                    <th class="text-center px-4 py-3 font-semibold">Durum</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($campaigns as $c)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $c->cover_image }}" alt="" class="w-12 h-12 rounded-lg object-cover shrink-0">
                                <div class="min-w-0">
                                    <p class="font-bold text-brand-900 truncate max-w-xs">{{ $c->title_tr }}</p>
                                    <p class="text-[11px] text-slate-500 truncate max-w-xs">{{ $c->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-700 text-xs">{{ $c->category?->name_tr ?? '—' }}</td>
                        <td class="px-4 py-3 text-right font-bold text-emerald-600">
                            {{ App\Support\Money::format($c->raised_amount, $c->currency) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <div class="w-20 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500" style="width: {{ $c->progress_percent }}%"></div>
                                </div>
                                <span class="text-xs text-slate-600 w-9">%{{ $c->progress_percent }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                @if ($c->zakat_eligible)  <span class="badge-z">Z</span> @endif
                                @if ($c->sadaka_eligible) <span class="badge-sc">SC</span> @endif
                                @if ($c->fitre_eligible)  <span class="badge-f">F</span> @endif
                                @if ($c->is_emergency)    <span class="badge-emergency">Acil</span> @endif
                                @if ($c->is_featured)
                                    <span class="badge bg-accent-50 text-accent-600">★</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($c->is_active)
                                <span class="badge bg-emerald-100 text-emerald-700">Aktif</span>
                            @else
                                <span class="badge bg-slate-100 text-slate-600">Pasif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('campaigns.show', $c->slug) }}" target="_blank"
                                   class="grid place-items-center w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-500" title="Görüntüle">
                                    <i data-lucide="external-link" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.campaigns.edit', $c) }}"
                                   class="grid place-items-center w-8 h-8 rounded-lg hover:bg-brand-50 text-brand-700" title="Düzenle">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.campaigns.destroy', $c) }}" class="inline"
                                      onsubmit="return confirm('Kampanya silinecek. Devam edilsin mi?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="grid place-items-center w-8 h-8 rounded-lg hover:bg-rose-50 text-rose-600" title="Sil">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-slate-500 py-10">Kayıt yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 border-t border-slate-100">
        {{ $campaigns->onEachSide(1)->links() }}
    </div>
</div>

@endsection
