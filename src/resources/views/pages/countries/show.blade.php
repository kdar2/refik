@extends('layouts.app')

@section('title', $country->name_tr)

@section('content')

<section class="relative overflow-hidden bg-brand-900 text-white">
    @if ($country->hero_image)
        <div class="absolute inset-0 opacity-40">
            <img src="{{ $country->hero_image }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-brand-900 via-brand-900/70 to-transparent"></div>
        </div>
    @else
        <div class="absolute inset-0 bg-grid-soft opacity-30"></div>
    @endif
    <div class="container-x py-20 relative">
        <a href="{{ route('countries.index') }}" class="inline-flex items-center gap-1.5 text-sm text-brand-200 hover:text-white">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> Tüm Bölgeler
        </a>
        <div class="flex items-center gap-4 mt-4">
            <span class="text-7xl">{{ $country->flag_emoji }}</span>
            <div>
                <p class="h-eyebrow text-brand-200">Çalışma Bölgesi</p>
                <h1 class="mt-1 text-4xl lg:text-5xl font-extrabold font-display">{{ $country->name_tr }}</h1>
            </div>
        </div>
        @if ($country->description_tr)
            <p class="mt-6 text-brand-100/90 max-w-2xl text-lg">{{ $country->description_tr }}</p>
        @endif
    </div>
</section>

<section class="section">
    <div class="container-x">
        @if ($campaigns->count())
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-extrabold font-display text-brand-900">Bu Bölgedeki Kampanyalar</h2>
                <a href="{{ route('campaigns.index') }}" class="text-sm text-accent-600 font-semibold hover:underline">Tümü</a>
            </div>
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($campaigns as $c)
                    <article class="card group">
                        <a href="{{ route('campaigns.show', $c->slug) }}" class="block">
                            <div class="card-media">
                                <img src="{{ $c->cover_image }}" alt="{{ $c->title_tr }}" loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            </div>
                            <div class="card-body">
                                <h3 class="card-title line-clamp-2">{{ $c->title_tr }}</h3>
                                <p class="card-text line-clamp-2">{{ $c->subtitle_tr }}</p>
                            </div>
                        </a>
                        <div class="px-5 pb-5">
                            <a href="{{ route('donate.show', ['campaign' => $c->slug]) }}" class="btn-accent btn-md w-full justify-center">
                                <i data-lucide="heart" class="w-4 h-4"></i> Bu Bölgede Bağış Yap
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-center text-slate-500 py-10">Bu bölgede şu an aktif kampanya bulunmuyor.</p>
        @endif
    </div>
</section>

@endsection
