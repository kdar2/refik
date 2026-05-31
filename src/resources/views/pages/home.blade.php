@extends('layouts.app')

@section('title', 'Hayra Yoldaş')

@section('content')

{{-- ────────────────  B1 — HERO SLIDER  ──────────────── --}}
@php($slidesCount = $sliders->count() ?: 1)
<section class="relative isolate overflow-hidden bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white">
    <div class="absolute inset-0 bg-grid-soft opacity-40 pointer-events-none"></div>

    <div x-data="heroSlider({{ $slidesCount }}, 7000)"
         x-init="start()"
         @mouseenter="paused = true" @mouseleave="paused = false"
         class="container-x relative pt-16 pb-24 lg:pt-20 lg:pb-28">

        {{-- Slaytlar: CSS grid stack — hepsi aynı hücrede üst üste; yükseklik en uzun slayt'a göre otomatik --}}
        <div class="grid">
            @forelse ($sliders as $i => $slide)
                <div :class="active === {{ $i }}
                            ? 'opacity-100 translate-y-0 pointer-events-auto'
                            : 'opacity-0 translate-y-4 pointer-events-none'"
                     class="row-start-1 col-start-1 transition-all duration-[700ms] ease-[cubic-bezier(.22,.61,.36,1)] will-change-transform"
                     :aria-hidden="active !== {{ $i }}">
                    <div class="lg:max-w-2xl">
                        @if ($slide->eyebrow_tr)
                            <p class="h-eyebrow text-brand-200">{{ $slide->eyebrow_tr }}</p>
                        @endif
                        <h1 class="mt-3 text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold font-display leading-[1.05] tracking-tight text-balance">
                            {{ $slide->title_tr }}
                        </h1>
                        @if ($slide->subtitle_tr)
                            <p class="mt-6 text-base lg:text-lg text-brand-100/90 max-w-xl">{{ $slide->subtitle_tr }}</p>
                        @endif
                        @if ($slide->cta_text_tr && $slide->cta_url)
                            <div class="mt-8 flex flex-wrap gap-3">
                                <a href="{{ $slide->cta_url }}" class="btn-accent btn-lg shadow-brand">
                                    <i data-lucide="heart" class="w-5 h-5"></i> {{ $slide->cta_text_tr }}
                                </a>
                                <a href="#kampanyalar" class="btn-ghost btn-lg !text-white hover:!bg-white/10">
                                    Kampanyaları Gör
                                    <i data-lucide="arrow-right" class="w-5 h-5"></i>
                                </a>
                            </div>
                        @endif

                        {{-- Sayaç --}}
                        <div class="mt-10 flex items-center gap-3 text-sm">
                            <span class="font-mono font-bold text-2xl text-gold-300">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div class="h-1 w-32 rounded-full bg-white/20 overflow-hidden">
                                <div class="h-full bg-gold-300 origin-left"
                                     :style="active === {{ $i }}
                                        ? `transform: scaleX(${progress}); transition: transform ${tickMs}ms linear;`
                                        : 'transform: scaleX(0); transition: none;'"></div>
                            </div>
                            <span class="text-brand-200">/ {{ str_pad($slidesCount, 2, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Fallback (slider yoksa) --}}
                <div class="row-start-1 col-start-1" data-rise>
                    <div class="lg:max-w-2xl">
                        <p class="h-eyebrow text-brand-200">Hayra Yoldaş</p>
                        <h1 class="mt-3 text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold font-display leading-[1.05] tracking-tight text-balance">
                            Sadakan Sonsuz Olsun
                        </h1>
                        <p class="mt-6 text-base lg:text-lg text-brand-100/90 max-w-xl">
                            Bağışlarınızla dünyada ihtiyaç sahiplerine umut oluyoruz.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Sağ alt navigasyon --}}
        @if ($sliders->count() > 1)
            <div class="mt-10 lg:mt-12 flex items-center gap-3 justify-between">
                <div class="flex items-center gap-2">
                    @foreach ($sliders as $i => $_)
                        <button @click="goTo({{ $i }})"
                                :class="active === {{ $i }} ? 'w-8 bg-white' : 'w-2 bg-white/40 hover:bg-white/70'"
                                class="h-2 rounded-full transition-all duration-300" aria-label="Slayt {{ $i + 1 }}"></button>
                    @endforeach
                </div>
                <div class="flex items-center gap-2">
                    <button @click="prev()"
                            class="grid place-items-center w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur transition" aria-label="Önceki">
                        <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    </button>
                    <button @click="next()"
                            class="grid place-items-center w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur transition" aria-label="Sonraki">
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
  // Hero slider — class tabanlı crossfade + slaytlar arası senkron sayaç animasyonu
  function heroSlider(total, tickMs) {
    return {
      active: 0,
      total,
      tickMs,
      paused: false,
      progress: 0,
      _timer: null,
      _restartProgress() {
        this.progress = 0;
        // Bir sonraki frame'de scaleX'i 1'e taşı; CSS transition 7sn'de doldurur.
        requestAnimationFrame(() => requestAnimationFrame(() => { this.progress = 1; }));
      },
      start() {
        this._restartProgress();
        this._timer = setInterval(() => {
          if (!this.paused) this.next();
        }, this.tickMs);
      },
      next()  { this.active = (this.active + 1) % this.total; this._restartProgress(); },
      prev()  { this.active = (this.active - 1 + this.total) % this.total; this._restartProgress(); },
      goTo(i) { this.active = i; this._restartProgress(); },
    };
  }
</script>
@endpush

{{-- ────────────────  B2 — GÜVEN BANDI  ──────────────── --}}
<section class="bg-surface-alt">
    <div class="container-x py-8 grid lg:grid-cols-[auto_1fr] items-center gap-8">
        <div class="flex items-center gap-6 justify-center lg:justify-start">
            @foreach (['shield-check'=>'İlmi Kurul Onaylı','file-check-2'=>'Bağımsız Denetim','badge-percent'=>'%100 Zekat Politikası'] as $icon => $label)
                <div class="flex flex-col items-center text-center w-24">
                    <div class="grid place-items-center w-16 h-16 rounded-full bg-white shadow-soft border border-slate-100">
                        <i data-lucide="{{ $icon }}" class="w-7 h-7 text-brand-700"></i>
                    </div>
                    <span class="mt-2 text-xs font-semibold text-slate-700">{{ $label }}</span>
                </div>
            @endforeach
        </div>
        <p class="text-sm lg:text-base text-slate-600 leading-relaxed text-balance">
            <strong class="text-brand-900">{{ config('site.name') }}</strong> olarak emanetlerinizi güvenle yönetiyoruz.
            %100 Zekât politikamız doğrultusunda bağışlarınızı kesintisiz olarak doğrudan hak sahiplerine ulaştırıyoruz.
            Tüm süreçler ilmi kurul denetiminde yürütülür; mali şeffaflığımız bağımsız denetim raporlarıyla teyit edilir.
        </p>
    </div>
</section>

{{-- ────────────────  B3 — ÖNE ÇIKAN ÇAĞRILAR  ──────────────── --}}
<section id="kampanyalar" class="section">
    <div class="container-x">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10">
            <div data-rise>
                <p class="h-eyebrow">Kampanyalarımız</p>
                <h2 class="mt-2 h-section">Öne Çıkan Çağrılar</h2>
                <p class="mt-3 text-slate-600 max-w-2xl">
                    Birlikte hayatları iyi yöne değiştirmek için senin de katkın olsun.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('campaigns.index') }}" class="btn-outline btn-md">
                    Tüm Kampanyalar <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse ($featuredCampaigns as $c)
                <article class="card group" data-rise>
                    <div class="card-media">
                        <div class="absolute inset-0 bg-gradient-to-br from-brand-700/40 via-brand-700/10 to-transparent z-[1]"></div>
                        <img src="{{ $c->cover_image }}" alt="{{ $c->title_tr }}" loading="lazy"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-3 right-3 z-[2] flex gap-1.5">
                            @if ($c->zakat_eligible)  <span class="badge-z" title="Zekat için uygundur">Z</span> @endif
                            @if ($c->sadaka_eligible) <span class="badge-sc" title="Sadaka-i Cariye için uygundur">SC</span> @endif
                            @if ($c->fitre_eligible)  <span class="badge-f" title="Fitre">F</span> @endif
                            @if ($c->is_emergency)    <span class="badge-emergency">Acil</span> @endif
                        </div>
                        @if ($c->goal_amount)
                            <div class="absolute inset-x-3 bottom-3 z-[2] rounded-xl bg-white/95 backdrop-blur p-3 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Niyet: <strong class="text-brand-900">{{ App\Support\Money::format($c->goal_amount, $c->currency) }}</strong></span>
                                    <span class="font-bold text-emerald-600">%{{ $c->progress_percent }}</span>
                                </div>
                                <div class="progress mt-1.5"><div class="bar" style="width: {{ $c->progress_percent }}%"></div></div>
                                <div class="mt-1.5 text-slate-500">Toplanan: <strong class="text-emerald-600">{{ App\Support\Money::format($c->raised_amount, $c->currency) }}</strong></div>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h3 class="card-title line-clamp-2 min-h-[3.2rem]">{{ $c->title_tr }}</h3>
                        <p class="card-text line-clamp-2">{{ $c->subtitle_tr }}</p>
                        @if ($c->category)
                            <p class="mt-2 text-[11px] uppercase tracking-wider font-semibold text-brand-500">
                                {{ $c->category->name_tr }}
                                @if ($c->country)<span class="text-slate-400"> · {{ $c->country->name_tr }}</span>@endif
                            </p>
                        @endif
                        <a href="#bagis" class="btn-accent btn-md w-full mt-5 justify-center">
                            <i data-lucide="heart" class="w-4 h-4"></i> Bağış Yap
                        </a>
                    </div>
                </article>
            @empty
                <p class="col-span-full text-center text-slate-500 py-8">Şu anda öne çıkan kampanya yok.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- ────────────────  B4 — ETKİ BANDI  ──────────────── --}}
<section class="bg-brand-700 text-white relative overflow-hidden">
    <div class="absolute inset-0 bg-grid-soft opacity-30"></div>
    <div class="container-x relative grid md:grid-cols-3 gap-10 py-16">
        @foreach ([
            ['count'=>19500,'title'=>'Gazze Sıcak Yemek Dağıtımı','desc'=>'Cömertliğiniz sayesinde her yıl binlerce hayata sıcak bir öğün ulaştırıyoruz.'],
            ['count'=>34000,'title'=>'Gazze İçme Suyu Dağıtımı','desc'=>'Temiz suya erişimi olmayan ailelere düzenli içme suyu sağlıyoruz.'],
            ['count'=>54000,'title'=>'Gıda Paketi Dağıtımı','desc'=>'Geçtiğimiz yıl yurt dışında 54.000\'den fazla kişiye gıda paketi ulaştırdık.'],
        ] as $stat)
            <div data-rise>
                <span class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-brand-700 text-2xl lg:text-3xl font-extrabold font-display tracking-tight">
                    <span data-counter="{{ $stat['count'] }}" data-suffix="+ kişi">0</span>
                </span>
                <h3 class="mt-4 text-xl font-bold text-white">{{ $stat['title'] }}</h3>
                <p class="mt-2 text-brand-100/90 text-sm leading-relaxed">{{ $stat['desc'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ────────────────  B5 — HAYRA YOLDAŞ  ──────────────── --}}
<section class="bg-surface-alt section">
    <div class="container-x grid lg:grid-cols-2 gap-12 items-center">
        <div data-rise>
            <p class="h-eyebrow">Bizi Tanı</p>
            <h2 class="mt-2 h-section">Hayra Yoldaş</h2>
            <div class="mt-6 space-y-4 text-slate-700 leading-relaxed">
                <p>
                    Uzun yıllardır insani yardım, eğitim ve sosyal dayanışma alanlarında aktif olarak çalışan,
                    tecrübeli ve profesyonel bir ekip tarafından kurulan
                    <strong class="text-brand-900">{{ config('site.legal_name') }}</strong>,
                    iyiliği yaymak ve ihtiyaç sahiplerine umut olmak amacıyla faaliyet göstermektedir.
                </p>
                <p>
                    "Hayra yoldaş ol" çağrımızla herkesi bu iyilik yolculuğuna davet ediyor;
                    destekçilerimizin yardımlarıyla mağdur ve mazlumlara ulaşıyoruz.
                </p>
            </div>
            <a href="{{ route('about') }}" class="btn-primary btn-md mt-8">
                Daha Fazla <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="relative aspect-square max-w-md mx-auto" data-rise>
            <div class="absolute inset-0 bg-gradient-to-br from-brand-300/20 to-brand-700/20 rounded-[3rem] -rotate-3"></div>
            <div class="relative h-full w-full rounded-[3rem] bg-white border border-slate-100 shadow-brand grid place-items-center">
                <div class="grid place-items-center w-32 h-32 rounded-full bg-gradient-to-br from-brand-700 to-brand-500 text-white">
                    <i data-lucide="heart-handshake" class="w-16 h-16"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- 7 hızlı erişim --}}
    <div class="container-x mt-14">
        <div class="flex gap-4 overflow-x-auto scrollbar-none scroll-smooth -mx-4 px-4">
            @foreach ([
                ['credit-card','Online Bağış',route('donate.show')],
                ['repeat-2','Düzenli Bağış',route('donate.show').'?frequency=monthly'],
                ['heart-handshake','Gönüllümüz Olun',route('volunteer.show')],
                ['hand-helping','Yardım Talebi',route('help-request.show')],
                ['landmark','Banka Hesapları',route('contact')],
                ['map-pin','Çalışma Bölgeleri',route('countries.index')],
                ['briefcase','İş Başvurusu',route('careers.show')],
            ] as [$icon, $label, $url])
                <a href="{{ $url }}" class="shrink-0 w-32 flex flex-col items-center text-center p-4 rounded-2xl bg-white border border-slate-100 hover:border-brand-300 hover:shadow-brand transition">
                    <span class="grid place-items-center w-14 h-14 rounded-2xl bg-brand-50 text-brand-700">
                        <i data-lucide="{{ $icon }}" class="w-6 h-6"></i>
                    </span>
                    <span class="mt-3 text-xs font-semibold text-slate-700">{{ $label }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ────────────────  B6 — ZEKAT VURGU  ──────────────── --}}
<section id="zekat" class="section">
    <div class="container-x">
        <div class="text-center max-w-3xl mx-auto mb-12" data-rise>
            <p class="h-eyebrow">Zekat</p>
            <h2 class="mt-2 h-section">Zekatınız Hayatları Değiştiriyor</h2>
        </div>

        <div class="grid lg:grid-cols-2 gap-6">
            @foreach ([
                ['title'=>'Zekat Rehberi','desc'=>'Zekat hakkında bilmeniz gereken her şeyi kapsayan rehberimiz.','icon'=>'book-open','img'=>1],
                ['title'=>'İlim Yolcusuna Zekat','desc'=>'Zekât, servetinizi arındırma ve cömertliğinizle hayatları dönüştürme fırsatınızdır.','icon'=>'graduation-cap','img'=>2],
            ] as $z)
                <article class="group relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-700 to-brand-900 text-white">
                    <img src="https://picsum.photos/seed/zekat-{{ $z['img'] }}/1200/600" alt=""
                         class="absolute inset-0 w-full h-full object-cover opacity-30 group-hover:scale-105 group-hover:opacity-40 transition duration-700">
                    <div class="relative p-8 lg:p-10 grid sm:grid-cols-[1fr_auto] items-center gap-6 min-h-[280px]">
                        <div>
                            <span class="grid place-items-center w-14 h-14 rounded-2xl bg-white/15 text-white border border-white/20 backdrop-blur">
                                <i data-lucide="{{ $z['icon'] }}" class="w-6 h-6"></i>
                            </span>
                            <h3 class="mt-5 text-2xl font-extrabold font-display text-white">{{ $z['title'] }}</h3>
                            <p class="mt-2 text-brand-100/90 max-w-md">{{ $z['desc'] }}</p>
                        </div>
                        <a href="#bagis" class="btn-accent btn-md whitespace-nowrap">Zekat Ver</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ────────────────  B7 — ZEKAT HESAPLAYICI + ONLİNE GÖRÜŞME  ──────────────── --}}
<section id="zekat-hesapla" class="section bg-surface-alt">
    <div class="container-x grid lg:grid-cols-2 gap-8">

        {{-- Sol: Hesaplayıcı --}}
        <div data-rise data-zakat-calculator
             data-gold-price="{{ $nisab?->gold_price_per_gram ?? 4250 }}"
             data-silver-price="{{ $nisab?->silver_price_per_gram ?? 49.5 }}"
             data-nisab-gold="{{ $nisab?->nisab_gold_grams ?? 80.18 }}"
             data-nisab-silver="{{ $nisab?->nisab_silver_grams ?? 560 }}"
             class="rounded-3xl bg-white p-8 lg:p-10 shadow-brand border border-slate-100">

            <div class="flex items-start gap-4 mb-6">
                <span class="grid place-items-center w-12 h-12 rounded-2xl bg-brand-50 text-brand-700 shrink-0">
                    <i data-lucide="calculator" class="w-6 h-6"></i>
                </span>
                <div>
                    <p class="h-eyebrow">Zekat</p>
                    <h2 class="mt-1 text-2xl lg:text-3xl font-extrabold font-display text-brand-900">Zekat Hesaplayıcı</h2>
                    <p class="mt-1 text-sm text-slate-600">
                        Hesaplama tamamen tarayıcınızda yapılır; girdiğiniz hiçbir bilgi sunucuya gönderilmez.
                    </p>
                </div>
            </div>

            {{-- Privacy popup (ilk açılışta) --}}
            <div data-zakat-privacy class="hidden mb-5 rounded-2xl bg-amber-50 border border-amber-200 p-5 text-sm text-amber-900">
                <p class="font-semibold mb-2 flex items-center gap-2">
                    <i data-lucide="shield-check" class="w-4 h-4"></i> Gizlilik notu
                </p>
                <p>
                    Bu modülde girdiğiniz tüm finansal veriler yalnızca tarayıcınızda hesaplama için kullanılır,
                    hiçbir şekilde sunucuya gönderilmez veya saklanmaz.
                </p>
                <button type="button" data-zakat-privacy-accept class="btn-primary btn-sm mt-4">
                    Okudum, Hesaplamaya Başla <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>

            <form data-zakat-form class="space-y-4">
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="label">Nakit (₺)</label><input type="number" min="0" step="any" name="cash" class="input"></div>
                    <div><label class="label">Banka mevduatı (₺)</label><input type="number" min="0" step="any" name="bank" class="input"></div>
                    <div><label class="label">Altın (gr)</label><input type="number" min="0" step="any" name="gold_grams" class="input"></div>
                    <div><label class="label">Gümüş (gr)</label><input type="number" min="0" step="any" name="silver_grams" class="input"></div>
                    <div><label class="label">Hisse / fonlar (₺)</label><input type="number" min="0" step="any" name="stocks" class="input"></div>
                    <div><label class="label">Alacaklar (₺)</label><input type="number" min="0" step="any" name="receivables" class="input"></div>
                    <div class="col-span-2 border-t border-slate-200 pt-3">
                        <label class="label">Borçlar (eksiltme) (₺)</label>
                        <input type="number" min="0" step="any" name="debts" class="input">
                    </div>
                </div>

                <div class="rounded-xl bg-brand-50 border border-brand-100 p-4 text-xs text-brand-700">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2"><i data-lucide="badge-info" class="w-4 h-4"></i> Nisap (altın eşdeğeri)</span>
                        <strong data-zakat-nisab>—</strong>
                    </div>
                    <div class="flex items-center justify-between mt-1">
                        <span>Toplam zekat verilebilir varlık</span>
                        <strong data-zakat-net>—</strong>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <button type="submit" class="btn-primary btn-md">
                        <i data-lucide="calculator" class="w-4 h-4"></i> Zekatı Hesapla
                    </button>
                    <button type="reset" class="btn-ghost btn-md">
                        <i data-lucide="rotate-ccw" class="w-4 h-4"></i> Temizle
                    </button>
                </div>
            </form>

            {{-- Sonuç --}}
            <div data-zakat-result class="hidden mt-6 rounded-2xl border-2 border-emerald-200 bg-emerald-50 p-5">
                <div class="flex items-start gap-3">
                    <i data-lucide="circle-check-big" class="w-6 h-6 text-emerald-600 shrink-0 mt-0.5"></i>
                    <div class="flex-1">
                        <p class="text-sm text-emerald-900" data-zakat-status></p>
                        <p class="mt-2 text-3xl font-extrabold font-display text-brand-900" data-zakat-amount>0 ₺</p>
                        <p class="text-xs text-emerald-700 mt-1" data-zakat-explain></p>
                    </div>
                </div>
                <a href="#bagis" class="btn-accent btn-md mt-4 w-full justify-center">
                    <i data-lucide="heart" class="w-4 h-4"></i> Zekatımı Şimdi Ver
                </a>
            </div>
        </div>

        {{-- Sağ: Online Görüşme --}}
        <div data-rise class="rounded-3xl bg-gradient-to-br from-brand-700 to-brand-900 text-white p-8 lg:p-10 relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-soft opacity-30 pointer-events-none"></div>
            <div class="relative">
                <span class="grid place-items-center w-12 h-12 rounded-2xl bg-white/15 text-white border border-white/20 backdrop-blur">
                    <i data-lucide="calendar-days" class="w-6 h-6"></i>
                </span>
                <p class="h-eyebrow text-brand-300 mt-5">Bize Sor</p>
                <h2 class="mt-1 text-2xl lg:text-3xl font-extrabold font-display text-white">Online Görüşme</h2>
                <p class="mt-2 text-brand-100/90 text-sm max-w-md">
                    Zekat, bağış veya vasiyet konularında uzman ekibimizden randevu alabilirsiniz. Görüşme online yapılır.
                </p>

                {{-- Mini takvim — Alpine ile dinamik --}}
                <div x-data="appointmentCalendar()" x-init="init()" class="mt-6">
                    <div class="flex items-center justify-between mb-3 text-sm">
                        <button @click="prevWeek()" class="grid place-items-center w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 transition" aria-label="Önceki hafta">
                            <i data-lucide="chevron-left" class="w-4 h-4"></i>
                        </button>
                        <span class="font-semibold" x-text="weekLabel"></span>
                        <button @click="nextWeek()" class="grid place-items-center w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 transition" aria-label="Sonraki hafta">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                    <div class="grid grid-cols-7 gap-1.5">
                        <template x-for="day in days" :key="day.iso">
                            <button type="button"
                                    :disabled="day.disabled"
                                    @click="select(day)"
                                    :class="[
                                        day.disabled ? 'opacity-40 cursor-not-allowed bg-white/5' : 'bg-white/10 hover:bg-white/25',
                                        selected?.iso === day.iso ? '!bg-accent-500 !text-white shadow-brand' : ''
                                    ]"
                                    class="aspect-square rounded-xl flex flex-col items-center justify-center text-center transition">
                                <span class="text-[10px] uppercase tracking-wider opacity-80" x-text="day.dow"></span>
                                <span class="text-lg font-bold" x-text="day.dom"></span>
                            </button>
                        </template>
                    </div>

                    {{-- Saat slotları --}}
                    <div class="mt-5" x-show="selected" x-cloak>
                        <p class="text-xs uppercase tracking-wider text-brand-300 mb-2">Saat seçin</p>
                        <div class="grid grid-cols-4 gap-2">
                            <template x-for="slot in slots" :key="slot">
                                <button type="button"
                                        @click="selectedSlot = slot"
                                        :class="selectedSlot === slot ? '!bg-gold-500 !text-brand-900' : 'bg-white/10 hover:bg-white/20'"
                                        class="rounded-lg py-2 text-sm font-semibold transition"
                                        x-text="slot"></button>
                            </template>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form method="POST" action="{{ route('appointments.store') }}" class="mt-5 space-y-3" x-show="selected && selectedSlot" x-cloak>
                        @csrf
                        <input type="hidden" name="date" :value="selected?.iso">
                        <input type="hidden" name="time" :value="selectedSlot">
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="full_name" required placeholder="Ad Soyad"
                                   class="input !bg-white/10 !border-white/20 !text-white placeholder:text-white/60">
                            <input type="tel" name="phone" required placeholder="Telefon"
                                   class="input !bg-white/10 !border-white/20 !text-white placeholder:text-white/60">
                        </div>
                        <input type="email" name="email" required placeholder="E-posta"
                               class="input !bg-white/10 !border-white/20 !text-white placeholder:text-white/60">
                        <select name="topic" required class="input !bg-white/10 !border-white/20 !text-white">
                            <option value="" class="text-slate-700">Konu seçin</option>
                            <option value="donation_advisory" class="text-slate-700">Bağış Danışmanlığı</option>
                            <option value="zakat_advisory"    class="text-slate-700">Zekat Danışmanlığı</option>
                            <option value="will"              class="text-slate-700">Vasiyet</option>
                            <option value="general"           class="text-slate-700">Genel</option>
                        </select>
                        <button type="submit" class="btn-gold btn-md w-full justify-center">
                            <i data-lucide="check" class="w-4 h-4"></i> Randevu Al
                        </button>
                    </form>

                    {{-- Flash --}}
                    @if (session('appointment_success'))
                        <div class="mt-4 rounded-xl bg-emerald-500/20 border border-emerald-300/40 p-3 text-sm">
                            <i data-lucide="check-circle" class="inline w-4 h-4 mr-1"></i>
                            {{ session('appointment_success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</section>

@push('scripts')
<script>
  // Alpine component for the appointment calendar (B7 sağ).
  // 14 gün ileri görünür, hafta sonu (cuma haricinde Pazar) pasif değil — sadece geçmiş günler pasif.
  // Saat slotları: 10:00, 11:00, 14:00, 15:00, 16:00, 17:00.
  function appointmentCalendar() {
    return {
      offset: 0,
      selected: null,
      selectedSlot: null,
      slots: ['10:00', '11:00', '14:00', '15:00', '16:00', '17:00'],
      days: [],
      weekLabel: '',
      init() {
        this.buildWeek();
      },
      buildWeek() {
        const fmt = new Intl.DateTimeFormat('tr-TR', { day: 'numeric', month: 'long' });
        const dowFmt = new Intl.DateTimeFormat('tr-TR', { weekday: 'short' });
        const today = new Date();
        today.setHours(0,0,0,0);
        const start = new Date(today);
        start.setDate(start.getDate() + this.offset);
        const days = [];
        for (let i = 0; i < 7; i++) {
          const d = new Date(start);
          d.setDate(start.getDate() + i);
          const isPast = d < today;
          const isSunday = d.getDay() === 0;
          days.push({
            iso: d.toISOString().slice(0,10),
            dom: d.getDate(),
            dow: dowFmt.format(d).replace('.', ''),
            disabled: isPast || isSunday,
          });
        }
        this.days = days;
        this.weekLabel = `${fmt.format(start)} — ${fmt.format(new Date(start.getFullYear(), start.getMonth(), start.getDate() + 6))}`;
      },
      prevWeek() { if (this.offset > 0) { this.offset -= 7; this.buildWeek(); this.selected = null; this.selectedSlot = null; } },
      nextWeek() { if (this.offset < 21) { this.offset += 7; this.buildWeek(); this.selected = null; this.selectedSlot = null; } },
      select(day) { if (!day.disabled) { this.selected = day; this.selectedSlot = null; } },
    };
  }
</script>
@endpush

{{-- ────────────────  B8 — SMS BAĞIŞ  ──────────────── --}}
<section class="section">
    <div class="container-x">
        <div class="rounded-3xl bg-brand-900 text-white overflow-hidden">
            <div class="grid lg:grid-cols-[auto_1fr] gap-8 p-8 lg:p-12 items-center">
                <div class="lg:w-64">
                    <p class="h-eyebrow text-brand-300">Hızlı Yöntem</p>
                    <h3 class="mt-2 text-3xl lg:text-4xl font-extrabold font-display text-white">SMS Bağış</h3>
                    <p class="mt-3 text-sm text-brand-200">Cep telefonunuzdan SMS göndererek hemen bağışta bulunabilirsiniz.</p>
                </div>
                <div class="grid sm:grid-cols-3 gap-6">
                    @foreach ($smsCodes as $sms)
                        <div class="rounded-2xl bg-white/5 border border-white/10 p-5">
                            <div class="flex items-baseline gap-2 flex-wrap">
                                <span class="text-lg font-bold">{{ $sms->label_tr }}</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 text-brand-300"></i>
                                <span class="px-2.5 py-1 rounded-md bg-accent-500 text-white font-bold text-sm">{{ $sms->short_code }}</span>
                                <i data-lucide="arrow-right" class="w-4 h-4 text-brand-300"></i>
                                <span class="font-bold text-gold-300">{{ App\Support\Money::format($sms->amount, $sms->currency) }}</span>
                            </div>
                            @if ($sms->keyword)
                                <p class="mt-2 text-xs text-brand-200">Anahtar kelime: <code class="font-mono">{{ $sms->keyword }}</code></p>
                            @endif
                            <div class="mt-4 flex items-center gap-3 text-xs text-brand-200">
                                <span class="grid place-items-center w-12 h-12 rounded-lg bg-white text-brand-900">
                                    <i data-lucide="qr-code" class="w-7 h-7"></i>
                                </span>
                                <span>QR kodu okutarak da bağış yapabilirsin.</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ────────────────  B9 — KATEGORİ SLIDER (Bağışlar Hangi Alanlarda Kullanılıyor)  ──────────────── --}}
<section class="section bg-surface-alt">
    <div class="container-x">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8" data-rise>
            <div>
                <p class="h-eyebrow">Çalışmalarımız</p>
                <h2 class="mt-2 h-section">Bağışlar Hangi Alanlarda Kullanılıyor?</h2>
                <p class="mt-3 text-slate-600 max-w-xl">14 farklı çalışma alanında ihtiyaç sahiplerine umut oluyoruz.</p>
            </div>
            <a href="{{ route('campaigns.index') }}" class="btn-outline btn-md self-start sm:self-end">
                Tüm Kategoriler <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        <div class="-mx-4 px-4 overflow-x-auto scrollbar-none scroll-smooth snap-x snap-mandatory">
            <div class="flex gap-5 pb-2">
                @foreach ($categories as $cat)
                    <a href="{{ route('campaigns.index') }}?category={{ $cat->slug }}"
                       class="snap-start group relative shrink-0 w-72 aspect-[4/5] rounded-3xl overflow-hidden shadow-md hover:shadow-brand transition-all duration-300 hover:-translate-y-1 text-white">

                        <img src="https://picsum.photos/seed/cat-{{ $cat->slug }}/700/900" alt="{{ $cat->name_tr }}"
                             loading="lazy"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition duration-700">

                        {{-- Çift katmanlı koyulaştırma: alt bölge tamamen okunaklı --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-900/95 via-brand-900/55 to-brand-900/10"></div>
                        <div class="absolute inset-0 bg-gradient-to-tr from-brand-700/30 via-transparent to-transparent mix-blend-multiply"></div>

                        <div class="relative h-full flex flex-col justify-end p-6">
                            <span class="grid place-items-center w-12 h-12 rounded-2xl bg-white/15 backdrop-blur-sm border border-white/25 mb-4 group-hover:bg-gold-500 group-hover:border-gold-300 group-hover:text-brand-900 transition">
                                <i data-lucide="{{ $cat->icon ?? 'heart' }}" class="w-5 h-5"></i>
                            </span>
                            <h3 class="text-xl lg:text-2xl font-extrabold leading-tight text-balance text-white drop-shadow-md">
                                {{ $cat->name_tr }}
                            </h3>
                            @if ($cat->description_tr)
                                <p class="mt-2 text-sm leading-relaxed text-white/90 line-clamp-2">{{ $cat->description_tr }}</p>
                            @endif
                            <span class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-gold-300 group-hover:gap-3 group-hover:text-gold-100 transition-all">
                                Daha Fazla <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ────────────────  B10 — NEREDE ÇALIŞIYORUZ  ──────────────── --}}
<section id="nerede" class="section">
    <div class="container-x grid lg:grid-cols-[1fr_1.4fr] gap-10 items-center">
        <div data-rise>
            <p class="h-eyebrow">Nerede Çalışıyoruz</p>
            <h2 class="mt-2 h-section">{{ $activeCountries->count() }}+ ülke ve bölgede yardımdayız</h2>
            <p class="mt-4 text-slate-600 max-w-md">
                Refik Derneği saha ekipleri ve çözüm ortaklarıyla birlikte 4 kıtada eğitim, gıda, sağlık, barınma ve acil yardım çalışmalarını sürdürüyor.
            </p>
            <a href="{{ route('countries.index') }}" class="btn-primary btn-md mt-6">
                Tüm Bölgeleri Gör <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>

            <div class="mt-8 flex items-center gap-4 text-sm text-slate-500">
                <span class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-accent-500 animate-pulse"></span>
                    Aktif saha
                </span>
                <span class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-brand-300"></span>
                    Bilgi amaçlı
                </span>
            </div>
        </div>

        <div data-rise class="rounded-3xl bg-brand-50 border border-brand-100 p-6 lg:p-8 relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-soft opacity-50 pointer-events-none"></div>

            {{-- Ülke kartları grid'i — interaktif harita kontrolüne ek olarak liste deneyimi --}}
            <div class="relative grid grid-cols-2 md:grid-cols-3 gap-3 max-h-[460px] overflow-y-auto scrollbar-none pr-1">
                @foreach ($activeCountries as $country)
                    <button type="button"
                            class="group flex items-center gap-3 rounded-xl bg-white border border-slate-100 hover:border-accent-300 hover:shadow-md p-3 text-left transition">
                        <span class="text-2xl leading-none">{{ $country->flag_emoji }}</span>
                        <span class="flex-1 min-w-0">
                            <span class="block text-sm font-bold text-brand-900 truncate">{{ $country->name_tr }}</span>
                            <span class="block text-[11px] text-slate-500 truncate">{{ $country->code }}</span>
                        </span>
                        <span class="w-2 h-2 rounded-full bg-accent-500 animate-pulse shrink-0"></span>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ────────────────  B11 — MEDYA & DUYURULAR  ──────────────── --}}
<section class="section bg-surface-alt">
    <div class="container-x">
        <div class="flex items-end justify-between mb-10" data-rise>
            <div>
                <p class="h-eyebrow">Saha Haberleri</p>
                <h2 class="mt-2 h-section">Medya ve Duyurular</h2>
            </div>
            <a href="{{ route('posts.index') }}" class="btn-ghost btn-md">
                Tüm Haberler <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>

        @if ($featuredPost)
            <div class="grid lg:grid-cols-3 gap-6">
                <article class="lg:col-span-2 relative rounded-3xl overflow-hidden min-h-[480px] group">
                    <img src="{{ $featuredPost->cover_image }}" alt="{{ $featuredPost->title_tr }}"
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                    <div class="relative h-full flex flex-col justify-end p-8 lg:p-10 text-white">
                        @if ($featuredPost->category)
                            <span class="badge bg-accent-500 text-white">{{ $featuredPost->category->name_tr }}</span>
                        @endif
                        <h3 class="mt-3 text-2xl lg:text-4xl font-extrabold font-display max-w-2xl text-balance">
                            {{ $featuredPost->title_tr }}
                        </h3>
                        <p class="mt-3 text-white/85 max-w-2xl line-clamp-2">{{ $featuredPost->excerpt_tr }}</p>
                        <div class="mt-5 flex items-center gap-4 text-sm">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                                {{ $featuredPost->published_at?->translatedFormat('d F Y') }}
                            </span>
                            <a href="#" class="btn-accent btn-sm">Daha Fazla <i data-lucide="arrow-right" class="w-4 h-4"></i></a>
                        </div>
                    </div>
                </article>

                <div class="space-y-6">
                    @foreach ($sidePosts as $p)
                        <article class="relative rounded-2xl overflow-hidden h-44 group cursor-pointer">
                            <img src="{{ $p->cover_image }}" alt="{{ $p->title_tr }}"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                            <div class="relative h-full flex flex-col justify-end p-4 text-white">
                                <h4 class="font-bold leading-snug text-balance line-clamp-2">{{ $p->title_tr }}</h4>
                                <span class="mt-1 text-xs text-white/80">{{ $p->published_at?->translatedFormat('d F Y') }}</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            @if ($thumbPosts->count())
                <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    @foreach ($thumbPosts as $p)
                        <a href="#" class="relative aspect-[4/3] rounded-xl overflow-hidden group block">
                            <img src="{{ $p->cover_image }}" alt="{{ $p->title_tr }}"
                                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
                            <span class="absolute inset-x-2 bottom-2 text-white text-[11px] font-semibold leading-snug line-clamp-2">{{ $p->title_tr }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        @endif
    </div>
</section>

@endsection
