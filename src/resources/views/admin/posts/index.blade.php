@extends('admin.layout')

@section('title', 'Haberler')
@section('header', 'Haberler')

@section('header_actions')
    <a href="{{ route('admin.posts.create') }}" class="btn-accent btn-sm">
        <i data-lucide="plus" class="w-4 h-4"></i> Yeni Haber
    </a>
@endsection

@section('content')

<div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">

    <form method="GET" action="{{ route('admin.posts.index') }}" class="p-4 border-b border-slate-100 grid sm:grid-cols-[1fr_220px_auto] gap-2">
        <input type="search" name="q" value="{{ $q }}" placeholder="Haber başlığı ara…" class="input !py-2">
        <select name="category" class="input !py-2">
            <option value="">Tüm kategoriler</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->slug }}" @selected($category===$cat->slug)>{{ $cat->name_tr }}</option>
            @endforeach
        </select>
        <button class="btn-primary btn-sm">Filtrele</button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="text-left px-4 py-3 font-semibold">Haber</th>
                    <th class="text-left px-4 py-3 font-semibold">Kategori</th>
                    <th class="text-left px-4 py-3 font-semibold">Yayın Tarihi</th>
                    <th class="text-center px-4 py-3 font-semibold">Görüntülenme</th>
                    <th class="text-center px-4 py-3 font-semibold">Durum</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($posts as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="{{ $p->cover_image }}" alt="" class="w-12 h-9 rounded object-cover shrink-0">
                                <div class="min-w-0">
                                    <p class="font-bold text-brand-900 truncate max-w-md">{{ $p->title_tr }}</p>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $p->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-700">{{ $p->category?->name_tr ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-slate-700">{{ $p->published_at?->translatedFormat('d M Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-center text-xs text-slate-600">{{ number_format($p->view_count, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($p->is_published)
                                <span class="badge bg-emerald-100 text-emerald-700">Yayında</span>
                            @else
                                <span class="badge bg-slate-100 text-slate-600">Taslak</span>
                            @endif
                            @if ($p->is_featured) <span class="badge bg-accent-50 text-accent-600 ml-1">★</span> @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                @if ($p->is_published)
                                    <a href="{{ route('posts.show', $p->slug) }}" target="_blank"
                                       class="grid place-items-center w-8 h-8 rounded-lg hover:bg-slate-100 text-slate-500" title="Görüntüle">
                                        <i data-lucide="external-link" class="w-4 h-4"></i>
                                    </a>
                                @endif
                                <a href="{{ route('admin.posts.edit', $p) }}"
                                   class="grid place-items-center w-8 h-8 rounded-lg hover:bg-brand-50 text-brand-700" title="Düzenle">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.posts.destroy', $p) }}" class="inline"
                                      onsubmit="return confirm('Haber silinecek. Devam edilsin mi?');">
                                    @csrf @method('DELETE')
                                    <button class="grid place-items-center w-8 h-8 rounded-lg hover:bg-rose-50 text-rose-600" title="Sil">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-slate-500 py-10">Kayıt yok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-4 py-3 border-t border-slate-100">{{ $posts->onEachSide(1)->links() }}</div>
</div>

@endsection
