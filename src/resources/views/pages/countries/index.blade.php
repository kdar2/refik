@extends('layouts.app')

@section('title', 'Nerede Çalışıyoruz')

@section('content')

<section class="bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white relative">
    <div class="absolute inset-0 bg-grid-soft opacity-30 pointer-events-none"></div>
    <div class="container-x py-16 lg:py-20 relative">
        <p class="h-eyebrow text-brand-200">Nerede Çalışıyoruz</p>
        <h1 class="mt-2 text-4xl lg:text-5xl font-extrabold font-display tracking-tight text-balance">
            {{ $active->count() }}'den fazla ülke ve bölgede aktifiz
        </h1>
        <p class="mt-4 text-brand-100/90 max-w-2xl">
            Saha ekiplerimiz ve çözüm ortaklarımızla 4 kıtada eğitim, gıda, sağlık, barınma ve acil yardım çalışmalarını sürdürüyor.
        </p>
    </div>
</section>

<section class="section">
    <div class="container-x">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-extrabold font-display text-brand-900">Aktif Çalıştığımız Bölgeler</h2>
            <span class="text-sm text-slate-500">{{ $active->count() }} ülke</span>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($active as $c)
                <a href="{{ route('countries.show', strtolower($c->code)) }}"
                   class="group flex items-center gap-4 rounded-2xl bg-white border border-slate-100 hover:border-accent-300 hover:shadow-md p-5 transition">
                    <span class="text-4xl leading-none shrink-0">{{ $c->flag_emoji }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-brand-900 group-hover:text-accent-600 transition truncate">{{ $c->name_tr }}</p>
                        <p class="text-xs text-slate-500">{{ $c->code }}</p>
                    </div>
                    <span class="w-2 h-2 rounded-full bg-accent-500 animate-pulse shrink-0"></span>
                </a>
            @endforeach
        </div>

        @if ($others->count())
            <div class="mt-14">
                <h2 class="text-2xl font-extrabold font-display text-brand-900 mb-6">Bilgi Amaçlı Bölgeler</h2>
                <div class="grid sm:grid-cols-3 lg:grid-cols-5 gap-3">
                    @foreach ($others as $c)
                        <div class="flex items-center gap-2 rounded-xl bg-slate-50 border border-slate-100 px-3 py-2 text-sm">
                            <span class="text-xl">{{ $c->flag_emoji }}</span>
                            <span class="font-semibold text-slate-700">{{ $c->name_tr }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
