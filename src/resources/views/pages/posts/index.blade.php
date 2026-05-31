@extends('layouts.app')

@section('title', 'Medya ve Duyurular')

@section('content')

<section class="bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white relative">
    <div class="absolute inset-0 bg-grid-soft opacity-30 pointer-events-none"></div>
    <div class="container-x py-16 lg:py-20 relative">
        <p class="h-eyebrow text-brand-200">Saha Haberleri</p>
        <h1 class="mt-2 text-4xl lg:text-5xl font-extrabold font-display tracking-tight text-balance">
            Medya ve Duyurular
        </h1>
        <p class="mt-4 text-brand-100/90 max-w-2xl">
            Saha çalışmalarımızdan, etkinliklerimizden ve duyurularımızdan haberler.
        </p>
    </div>
</section>

<section class="section">
    <div class="container-x">
        {{-- Filtre çubuğu --}}
        <form method="GET" action="{{ route('posts.index') }}" class="flex flex-col lg:flex-row lg:items-center gap-3 mb-10">
            <div class="flex-1">
                <input type="search" name="q" value="{{ $search }}" placeholder="Haberlerde ara…" class="input">
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('posts.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-semibold {{ empty($activeCategory) ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-brand-50' }}">
                    Tümü
                </a>
                @foreach ($categories as $cat)
                    <a href="{{ route('posts.index', ['category' => $cat->slug] + ($search ? ['q' => $search] : [])) }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold {{ $activeCategory === $cat->slug ? 'bg-brand-700 text-white' : 'bg-slate-100 text-slate-700 hover:bg-brand-50' }}">
                        {{ $cat->name_tr }}
                    </a>
                @endforeach
            </div>
        </form>

        @if ($search)
            <p class="text-sm text-slate-500 mb-6">
                "<strong>{{ $search }}</strong>" araması için <strong>{{ $posts->total() }}</strong> sonuç.
                <a href="{{ route('posts.index') }}" class="text-accent-600 hover:underline">Aramayı temizle</a>
            </p>
        @endif

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($posts as $p)
                <article class="card group" data-rise>
                    <a href="{{ route('posts.show', $p->slug) }}" class="block">
                        <div class="card-media">
                            <img src="{{ $p->cover_image }}" alt="{{ $p->title_tr }}" loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        </div>
                        <div class="card-body">
                            @if ($p->category)
                                <span class="badge bg-brand-50 text-brand-700">{{ $p->category->name_tr }}</span>
                            @endif
                            <h3 class="mt-3 card-title line-clamp-2 group-hover:text-accent-600 transition">{{ $p->title_tr }}</h3>
                            <p class="card-text line-clamp-2">{{ $p->excerpt_tr }}</p>
                            <p class="mt-4 text-xs text-slate-500 flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                {{ $p->published_at?->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </a>
                </article>
            @empty
                <div class="col-span-full text-center py-16 text-slate-500">
                    <i data-lucide="inbox" class="w-12 h-12 mx-auto mb-3 text-slate-300"></i>
                    <p>Bu filtreye uygun haber bulunamadı.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">{{ $posts->onEachSide(1)->links() }}</div>
    </div>
</section>
@endsection
