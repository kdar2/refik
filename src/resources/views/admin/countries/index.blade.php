@extends('admin.layout')

@section('title', 'Ülkeler')
@section('header', 'Ülkeler')

@section('header_actions')
    <a href="{{ route('admin.countries.create') }}" class="btn-accent btn-sm">
        <i data-lucide="plus" class="w-4 h-4"></i> Yeni Ülke
    </a>
@endsection

@section('content')

<div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
    <form method="GET" action="{{ route('admin.countries.index') }}" class="p-4 border-b border-slate-100 flex gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="Ülke adı ara…" class="input !py-2 flex-1">
        <button class="btn-primary btn-sm">Ara</button>
    </form>
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
            <tr>
                <th class="text-left px-4 py-3 font-semibold">Bayrak</th>
                <th class="text-left px-4 py-3 font-semibold">Kod</th>
                <th class="text-left px-4 py-3 font-semibold">Adı (TR)</th>
                <th class="text-left px-4 py-3 font-semibold">Adı (EN)</th>
                <th class="text-center px-4 py-3 font-semibold">Aktif Bölge</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach ($countries as $c)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 text-2xl">{{ $c->flag_emoji }}</td>
                    <td class="px-4 py-3 font-mono font-bold text-brand-900 text-xs">{{ $c->code }}</td>
                    <td class="px-4 py-3 text-sm">{{ $c->name_tr }}</td>
                    <td class="px-4 py-3 text-sm text-slate-600">{{ $c->name_en }}</td>
                    <td class="px-4 py-3 text-center">
                        @if ($c->is_active_region)
                            <span class="badge bg-emerald-100 text-emerald-700">Aktif</span>
                        @else
                            <span class="badge bg-slate-100 text-slate-600">Bilgi</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.countries.edit', $c) }}" class="grid place-items-center w-8 h-8 rounded-lg hover:bg-brand-50 text-brand-700">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.countries.destroy', $c) }}"
                                  onsubmit="return confirm('Ülke silinecek. Devam edilsin mi?');">
                                @csrf @method('DELETE')
                                <button class="grid place-items-center w-8 h-8 rounded-lg hover:bg-rose-50 text-rose-600">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-4 py-3 border-t border-slate-100">{{ $countries->onEachSide(1)->links() }}</div>
</div>

@endsection
