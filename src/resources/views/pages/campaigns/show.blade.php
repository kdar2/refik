@extends('layouts.app')

@section('title',          $campaign->title_tr)
@section('description',    $campaign->subtitle_tr ?: \Illuminate\Support\Str::limit(strip_tags($campaign->description_tr), 160))
@section('og_title',       $campaign->title_tr . ' — ' . config('site.name'))
@section('og_description', $campaign->subtitle_tr ?: \Illuminate\Support\Str::limit(strip_tags($campaign->description_tr), 200))
@section('og_image',       $campaign->cover_image)
@section('og_type',        'website')

@push('schema')
@php
    $_atC = '@' . 'context'; $_atT = '@' . 'type';
    $_donateSchema = [
        $_atC          => 'https://schema.org',
        $_atT          => 'DonateAction',
        'name'         => $campaign->title_tr,
        'description'  => $campaign->subtitle_tr,
        'image'        => $campaign->cover_image,
        'url'          => route('campaigns.show', $campaign->slug),
        'recipient'    => [
            $_atT  => 'NGO',
            'name' => config('site.legal_name'),
        ],
        'price'         => (float) $campaign->raised_amount,
        'priceCurrency' => $campaign->currency,
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($_donateSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden min-h-[460px] lg:min-h-[560px] flex">
    <div class="absolute inset-0">
        <img src="{{ $campaign->cover_image ?: asset(config('site.default_image')) }}" alt="{{ $campaign->title_tr }}"
             class="w-full h-full object-cover object-center" fetchpriority="high">
        <div class="absolute inset-0 bg-gradient-to-t from-brand-900/95 via-brand-900/55 to-brand-900/15"></div>
    </div>
    <div class="container-x relative w-full pt-20 pb-10 lg:pt-28 lg:pb-14 text-white flex flex-col justify-end">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            @if ($campaign->category)
                <span class="badge bg-white/15 text-white backdrop-blur border border-white/20">
                    <i data-lucide="{{ $campaign->category->icon ?? 'heart' }}" class="w-3.5 h-3.5"></i>
                    {{ $campaign->category->name_tr }}
                </span>
            @endif
            @if ($campaign->country)
                <span class="badge bg-white/15 text-white backdrop-blur border border-white/20">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                    {{ $campaign->country->name_tr }}
                </span>
            @endif
            @if ($campaign->is_emergency) <span class="badge-emergency">Acil</span> @endif
            @if ($campaign->zakat_eligible)  <span class="badge-z">Zekat</span> @endif
            @if ($campaign->sadaka_eligible) <span class="badge-sc">Sadaka-i Cariye</span> @endif
        </div>

        <h1 class="text-4xl lg:text-6xl font-extrabold font-display tracking-tight max-w-4xl text-balance">
            {{ $campaign->title_tr }}
        </h1>
        @if ($campaign->subtitle_tr)
            <p class="mt-4 text-lg text-brand-100/95 max-w-3xl">{{ $campaign->subtitle_tr }}</p>
        @endif
    </div>
</section>

{{-- İçerik + sticky bağış kartı --}}
<section class="section">
    <div class="container-x grid lg:grid-cols-[1fr_400px] gap-10">

        {{-- Sol: hikaye --}}
        <div data-rise>
            <div class="prose prose-slate max-w-none prose-headings:font-display prose-a:text-accent-600">
                {!! $campaign->description_tr !!}
            </div>

            {{-- Hızlı bilgi kartları --}}
            <div class="mt-10 grid sm:grid-cols-3 gap-3">
                <div class="rounded-2xl bg-surface-alt p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Toplanan</p>
                    <p class="mt-1 text-2xl font-extrabold text-emerald-600 font-display">
                        {{ App\Support\Money::format($campaign->raised_amount, $campaign->currency) }}
                    </p>
                </div>
                <div class="rounded-2xl bg-surface-alt p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Niyet</p>
                    <p class="mt-1 text-2xl font-extrabold text-brand-900 font-display">
                        {{ $campaign->goal_amount ? App\Support\Money::format($campaign->goal_amount, $campaign->currency) : '—' }}
                    </p>
                </div>
                <div class="rounded-2xl bg-surface-alt p-5">
                    <p class="text-xs uppercase tracking-wider text-slate-500">Bağışçı</p>
                    <p class="mt-1 text-2xl font-extrabold text-brand-900 font-display">
                        {{ number_format($campaign->donor_count, 0, ',', '.') }} kişi
                    </p>
                </div>
            </div>

            {{-- Benzer kampanyalar --}}
            @if ($similar->count())
                <div class="mt-14">
                    <h2 class="text-2xl font-extrabold font-display text-brand-900 mb-5">Benzer Kampanyalar</h2>
                    <div class="grid sm:grid-cols-3 gap-5">
                        @foreach ($similar as $s)
                            <a href="{{ route('campaigns.show', $s->slug) }}" class="card group block">
                                <div class="card-media">
                                    <img src="{{ $s->cover_image ?: asset(config('site.default_image')) }}" alt="{{ $s->title_tr }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                </div>
                                <div class="card-body">
                                    <h3 class="text-base font-bold text-brand-900 line-clamp-2">{{ $s->title_tr }}</h3>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sağ: sticky donate card --}}
        <aside class="lg:sticky lg:top-24 lg:self-start" data-rise>
            <form method="GET" action="{{ route('donate.show') }}"
                  x-data="{ amount: 250, freq: 'one_time', type: '{{ $campaign->zakat_eligible ? 'zakat' : 'general' }}' }"
                  class="rounded-3xl bg-white border border-slate-100 shadow-brand p-6 lg:p-7 space-y-5">

                <input type="hidden" name="campaign" value="{{ $campaign->slug }}">
                <input type="hidden" name="amount"   :value="amount">
                <input type="hidden" name="frequency":value="freq">
                <input type="hidden" name="type"     :value="type">

                {{-- Progress --}}
                @if ($campaign->goal_amount)
                    <div>
                        <div class="flex items-end justify-between mb-2">
                            <span class="text-xs text-slate-500">İlerleme</span>
                            <span class="font-extrabold text-emerald-600">%{{ $campaign->progress_percent }}</span>
                        </div>
                        <div class="progress h-2.5"><div class="bar" style="width: {{ $campaign->progress_percent }}%"></div></div>
                        <div class="mt-2 flex items-center justify-between text-xs">
                            <span class="text-slate-500">Toplanan: <strong class="text-emerald-700">{{ App\Support\Money::format($campaign->raised_amount, $campaign->currency) }}</strong></span>
                            <span class="text-slate-500">Niyet: <strong class="text-brand-900">{{ App\Support\Money::format($campaign->goal_amount, $campaign->currency) }}</strong></span>
                        </div>
                    </div>
                @endif

                {{-- Tutar preset --}}
                <div>
                    <label class="label">Tutar</label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach ([50, 100, 250, 500, 1000] as $val)
                            <button type="button" @click="amount = {{ $val }}"
                                    :class="amount === {{ $val }} ? 'border-brand-700 bg-brand-50 text-brand-900' : 'border-slate-200 hover:border-brand-300'"
                                    class="rounded-lg border-2 py-2 text-sm font-bold transition">
                                {{ number_format($val, 0, ',', '.') }} ₺
                            </button>
                        @endforeach
                        <input type="number" min="1" step="1" x-model.number="amount" placeholder="Diğer"
                               class="input col-span-1 !py-2 text-sm font-bold">
                    </div>
                </div>

                {{-- Düzenli toggle --}}
                <div>
                    <label class="label">Bağış sıklığı</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="freq = 'one_time'"
                                :class="freq === 'one_time' ? 'border-brand-700 bg-brand-50 text-brand-900' : 'border-slate-200'"
                                class="rounded-lg border-2 py-2 text-sm font-semibold transition">Tek Sefer</button>
                        <button type="button" @click="freq = 'monthly'"
                                :class="freq === 'monthly' ? 'border-brand-700 bg-brand-50 text-brand-900' : 'border-slate-200'"
                                class="rounded-lg border-2 py-2 text-sm font-semibold transition">Aylık</button>
                    </div>
                </div>

                {{-- Bağış türü --}}
                <div>
                    <label class="label">Bağış türü</label>
                    <select x-model="type" class="input !py-2.5">
                        <option value="general">Genel Bağış</option>
                        @if ($campaign->zakat_eligible)  <option value="zakat">Zekat</option>      @endif
                        @if ($campaign->sadaka_eligible) <option value="sadaka">Sadaka-i Cariye</option> @endif
                        @if ($campaign->fitre_eligible)  <option value="fitre">Fitre</option>      @endif
                        @if ($campaign->kurban_eligible) <option value="kurban">Kurban</option>    @endif
                    </select>
                </div>

                <button type="submit" class="btn-accent btn-lg w-full justify-center shadow-brand">
                    <i data-lucide="heart" class="w-5 h-5"></i>
                    Bağış Yap
                </button>

                <p class="text-[11px] text-center text-slate-500">
                    Bağışınız 256-bit SSL ile şifrelenir. Banka kartı bilgileri saklanmaz.
                </p>
            </form>
        </aside>
    </div>
</section>

@endsection
