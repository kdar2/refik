@extends('layouts.app')

@section('title', 'Etki & Güvence')

@section('content')

<section class="bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white relative">
    <div class="absolute inset-0 bg-grid-soft opacity-30"></div>
    <div class="container-x py-20 relative">
        <p class="h-eyebrow text-brand-200">Etki & Güvence</p>
        <h1 class="mt-2 text-4xl lg:text-5xl font-extrabold font-display tracking-tight text-balance max-w-3xl">
            Şeffaf finansman, ölçülebilir etki
        </h1>
        <p class="mt-4 text-brand-100/90 max-w-2xl">
            Tüm bağış akışımız bağımsız denetim altındadır; çalışma alanlarımızdaki etkimiz kamuya açık raporlarla belgelenir.
        </p>
    </div>
</section>

{{-- Genel rakamlar --}}
<section class="section">
    <div class="container-x grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="rounded-2xl bg-emerald-50 border border-emerald-100 p-6">
            <p class="text-xs uppercase tracking-wider text-emerald-700 font-semibold">Toplam Bağış</p>
            <p class="mt-2 text-3xl font-extrabold font-display text-emerald-700">
                {{ App\Support\Money::tl($totalRaised) }}
            </p>
        </div>
        <div class="rounded-2xl bg-brand-50 border border-brand-100 p-6">
            <p class="text-xs uppercase tracking-wider text-brand-700 font-semibold">Bağışçı</p>
            <p class="mt-2 text-3xl font-extrabold font-display text-brand-900">
                {{ number_format($totalDonors, 0, ',', '.') }}
            </p>
        </div>
        <div class="rounded-2xl bg-gold-100 border border-gold-300/40 p-6">
            <p class="text-xs uppercase tracking-wider text-gold-700 font-semibold">Aktif Kampanya</p>
            <p class="mt-2 text-3xl font-extrabold font-display text-gold-700">{{ $totalCampaigns }}</p>
        </div>
        <div class="rounded-2xl bg-accent-50 border border-accent-100 p-6">
            <p class="text-xs uppercase tracking-wider text-accent-600 font-semibold">İşlem Adedi</p>
            <p class="mt-2 text-3xl font-extrabold font-display text-accent-600">
                {{ number_format($completedDonations, 0, ',', '.') }}
            </p>
        </div>
    </div>
</section>

{{-- Verimlilik --}}
<section class="section bg-surface-alt">
    <div class="container-x grid lg:grid-cols-[1fr_auto] gap-10 items-center">
        <div>
            <p class="h-eyebrow">Bağışın Yolculuğu</p>
            <h2 class="mt-2 h-section">Bağışlarınız nereye gidiyor?</h2>
            <p class="mt-4 text-slate-700 max-w-xl">
                Her 100 ₺ bağışın
                <strong>{{ App\Support\Settings::get('efficiency.programs') }} ₺'sini</strong>
                doğrudan saha programlarına,
                <strong>{{ App\Support\Settings::get('efficiency.fundraising') }} ₺'sini</strong>
                bağış toplama altyapısına,
                <strong>{{ App\Support\Settings::get('efficiency.management') }} ₺'sini</strong>
                kurumsal yönetim faaliyetlerine ayırıyoruz.
            </p>
        </div>
        <div class="grid grid-cols-3 gap-4 lg:gap-6">
            @foreach ([
                ['programs','Programlar','#16A34A'],
                ['fundraising','Bağış Toplama','#0B295C'],
                ['management','Yönetim','#C09740'],
            ] as [$key,$label,$color])
                @php($val = (int) App\Support\Settings::get("efficiency.{$key}", 0))
                <div class="text-center w-32">
                    <div class="relative w-32 h-32">
                        <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90">
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="#E2E7F0" stroke-width="3"/>
                            <circle cx="18" cy="18" r="15.9155" fill="none" stroke="{{ $color }}" stroke-width="3"
                                    stroke-dasharray="{{ $val }} 100" stroke-linecap="round"/>
                        </svg>
                        <span class="absolute inset-0 grid place-items-center text-xl font-extrabold text-brand-900">%{{ $val }}</span>
                    </div>
                    <p class="mt-2 text-xs font-semibold text-slate-700">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Denetim raporları --}}
<section class="section">
    <div class="container-x">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <p class="h-eyebrow">Şeffaflık</p>
            <h2 class="mt-2 h-section">Yıllık Faaliyet & Denetim Raporları</h2>
        </div>

        @if ($reports->count())
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($reports as $r)
                    <a href="{{ asset('storage/' . $r->file_path) }}" target="_blank"
                       class="card group block p-5 hover:shadow-md transition">
                        <div class="flex items-start gap-4">
                            <span class="grid place-items-center w-12 h-12 rounded-xl bg-accent-50 text-accent-600 shrink-0">
                                <i data-lucide="file-down" class="w-6 h-6"></i>
                            </span>
                            <div>
                                <p class="text-xs uppercase tracking-wider text-brand-500 font-semibold">{{ ucfirst($r->type) }} · {{ $r->year }}</p>
                                <h3 class="mt-1 font-bold text-brand-900 group-hover:text-accent-600 transition">{{ $r->title_tr }}</h3>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="text-center text-slate-500 py-10">Yakında eklenecek.</p>
        @endif
    </div>
</section>
@endsection
