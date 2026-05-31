@extends('admin.layout')

@section('title', 'Sayfalar')
@section('header', 'İçerik Sayfaları')

@section('header_actions')
    <a href="{{ route('admin.pages.create') }}" class="btn-accent btn-sm">
        <i data-lucide="plus" class="w-4 h-4"></i> Yeni Sayfa
    </a>
@endsection

@section('content')

<div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
            <tr>
                <th class="text-left px-4 py-3 font-semibold">Başlık</th>
                <th class="text-left px-4 py-3 font-semibold">Slug</th>
                <th class="text-center px-4 py-3 font-semibold">Durum</th>
                <th class="text-left px-4 py-3 font-semibold">Güncelleme</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($pages as $p)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-bold text-brand-900">{{ $p->title_tr }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-slate-500">{{ $p->slug }}</td>
                    <td class="px-4 py-3 text-center">
                        @if ($p->is_published)
                            <span class="badge bg-emerald-100 text-emerald-700">Yayında</span>
                        @else
                            <span class="badge bg-slate-100 text-slate-600">Taslak</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-xs text-slate-500">{{ $p->updated_at?->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.pages.edit', $p) }}" class="grid place-items-center w-8 h-8 rounded-lg hover:bg-brand-50 text-brand-700">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.pages.destroy', $p) }}"
                                  onsubmit="return confirm('Sayfa silinecek. Devam edilsin mi?');">
                                @csrf @method('DELETE')
                                <button class="grid place-items-center w-8 h-8 rounded-lg hover:bg-rose-50 text-rose-600">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-slate-500 py-10">Henüz sayfa yok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
