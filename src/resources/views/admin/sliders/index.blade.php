@extends('admin.layout')

@section('title', 'Slider')
@section('header', 'Hero Slider')

@section('header_actions')
    <a href="{{ route('admin.sliders.create') }}" class="btn-accent btn-sm">
        <i data-lucide="plus" class="w-4 h-4"></i> Yeni Slayt
    </a>
@endsection

@section('content')

<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse ($sliders as $s)
        <article class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
            <div class="relative aspect-[16/9] bg-slate-100">
                <img src="{{ $s->image }}" alt="{{ $s->title_tr }}" class="w-full h-full object-cover">
                <div class="absolute inset-0" style="background: {{ $s->overlay_color }}{{ str_pad(dechex((int) round($s->overlay_opacity * 2.55)), 2, '0', STR_PAD_LEFT) }};"></div>
                <span class="absolute top-2 left-2 badge bg-white/90 text-brand-900">#{{ $s->order }}</span>
                @if (!$s->is_active)
                    <span class="absolute top-2 right-2 badge bg-slate-900/80 text-white">Pasif</span>
                @endif
            </div>
            <div class="p-4">
                @if ($s->eyebrow_tr)
                    <p class="text-[10px] uppercase tracking-wider text-brand-500 font-semibold">{{ $s->eyebrow_tr }}</p>
                @endif
                <h3 class="mt-1 font-bold text-brand-900 line-clamp-2">{{ $s->title_tr }}</h3>
                @if ($s->cta_text_tr)
                    <p class="mt-2 text-xs text-slate-500 truncate">CTA: <strong>{{ $s->cta_text_tr }}</strong> → {{ $s->cta_url }}</p>
                @endif
                <div class="mt-4 flex items-center gap-1">
                    <a href="{{ route('admin.sliders.edit', $s) }}" class="btn-outline btn-sm flex-1 justify-center">
                        <i data-lucide="pencil" class="w-4 h-4"></i> Düzenle
                    </a>
                    <form method="POST" action="{{ route('admin.sliders.destroy', $s) }}"
                          onsubmit="return confirm('Slayt silinecek. Devam edilsin mi?');">
                        @csrf @method('DELETE')
                        <button class="grid place-items-center w-9 h-9 rounded-lg hover:bg-rose-50 text-rose-600 border border-slate-200" title="Sil">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>
            </div>
        </article>
    @empty
        <p class="col-span-full text-center text-slate-500 py-10">Henüz slayt yok.</p>
    @endforelse
</div>

@endsection
