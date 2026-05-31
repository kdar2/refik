@extends('layouts.app')

@section('title', 'Hakkımızda')

@section('content')

<section class="bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white relative">
    <div class="absolute inset-0 bg-grid-soft opacity-30"></div>
    <div class="container-x py-20 relative">
        <p class="h-eyebrow text-brand-200">Hakkımızda</p>
        <h1 class="mt-2 text-4xl lg:text-6xl font-extrabold font-display tracking-tight text-balance max-w-4xl">
            İyiliği yaymak için bir araya geldik
        </h1>
        <p class="mt-6 text-lg text-brand-100/90 max-w-2xl">
            {{ config('site.legal_name') }}, ihtiyaç sahiplerine umut olmak ve toplumsal dayanışmayı güçlendirmek amacıyla kurulmuş bir sivil toplum kuruluşudur.
        </p>
    </div>
</section>

{{-- Misyon / Vizyon --}}
<section class="section">
    <div class="container-x grid lg:grid-cols-2 gap-8">
        <div class="rounded-3xl bg-surface-alt p-8 lg:p-10">
            <span class="grid place-items-center w-12 h-12 rounded-2xl bg-accent-50 text-accent-600">
                <i data-lucide="target" class="w-6 h-6"></i>
            </span>
            <h2 class="mt-5 text-2xl font-extrabold font-display text-brand-900">Misyonumuz</h2>
            <p class="mt-3 text-slate-700 leading-relaxed">
                İhtiyaç sahiplerinin yanında olmak; eğitim, sağlık, gıda, barınma ve acil yardım alanlarında etki odaklı, şeffaf ve sürdürülebilir programlar yürütmek.
            </p>
        </div>
        <div class="rounded-3xl bg-surface-alt p-8 lg:p-10">
            <span class="grid place-items-center w-12 h-12 rounded-2xl bg-brand-50 text-brand-700">
                <i data-lucide="eye" class="w-6 h-6"></i>
            </span>
            <h2 class="mt-5 text-2xl font-extrabold font-display text-brand-900">Vizyonumuz</h2>
            <p class="mt-3 text-slate-700 leading-relaxed">
                Türkiye merkezli, küresel ölçekte güvenilirliğiyle tanınan; bağışçılarına şeffaflık, sahaya etki sunan öncü bir yardım kuruluşu olmak.
            </p>
        </div>
    </div>
</section>

{{-- Değerlerimiz --}}
<section class="section bg-surface-alt">
    <div class="container-x">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="h-eyebrow">Değerlerimiz</p>
            <h2 class="mt-2 h-section">Hayra Yoldaş ilkeleri</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ([
                ['shield-check','Güven','Bağımsız denetim ve tam şeffaflık.'],
                ['heart','Samimiyet','İhtiyaç sahibinin yanında, gösterişten uzak.'],
                ['users','Birlik','Bağışçı, gönüllü ve saha ekibiyle ortak yolculuk.'],
                ['leaf','Sürdürülebilirlik','Geçici çözümler değil, kalıcı etki.'],
            ] as [$icon,$title,$desc])
                <div class="rounded-2xl bg-white border border-slate-100 p-6 hover:shadow-md transition" data-rise>
                    <span class="grid place-items-center w-12 h-12 rounded-2xl bg-brand-50 text-brand-700">
                        <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                    </span>
                    <h3 class="mt-4 text-lg font-extrabold font-display text-brand-900">{{ $title }}</h3>
                    <p class="mt-1.5 text-sm text-slate-600">{{ $desc }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Sayılarla biz --}}
<section class="section">
    <div class="container-x">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <p class="h-eyebrow">Sayılarla Biz</p>
            <h2 class="mt-2 h-section">Etkinin ölçeği</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach ([
                ['120','Ülke ve Bölge'],
                ['450K','Ulaşılan Kişi'],
                ['1.2K','Aktif Kampanya'],
                ['18M','Yıllık Bağış (₺)'],
            ] as [$num,$label])
                <div class="rounded-2xl bg-brand-700 text-white p-6 text-center">
                    <p class="text-4xl font-extrabold font-display">{{ $num }}</p>
                    <p class="mt-2 text-sm text-brand-200 uppercase tracking-wider">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="section bg-brand-900 text-white">
    <div class="container-x text-center">
        <h2 class="text-3xl lg:text-4xl font-extrabold font-display">Hayra yoldaş olmaya ne dersin?</h2>
        <p class="mt-3 text-brand-100/90 max-w-xl mx-auto">Bağışçı, gönüllü veya saha ekibimizin parçası olarak iyilik yolculuğumuza katıl.</p>
        <div class="mt-7 flex flex-wrap items-center justify-center gap-3">
            <a href="{{ route('campaigns.index') }}" class="btn-accent btn-lg shadow-brand">
                <i data-lucide="heart" class="w-5 h-5"></i> Bağış Yap
            </a>
            <a href="{{ route('volunteer.show') }}" class="btn-gold btn-lg">
                <i data-lucide="heart-handshake" class="w-5 h-5"></i> Gönüllü Ol
            </a>
        </div>
    </div>
</section>
@endsection
