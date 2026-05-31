@extends('layouts.app')

@section('title', 'Teşekkürler')

@section('content')

<section class="section bg-surface-alt min-h-[70vh]">
    <div class="container-x max-w-3xl">

        <div class="rounded-3xl bg-white border border-slate-100 shadow-brand overflow-hidden text-center">

            {{-- Üst banner — durum --}}
            @php($completed = $donation->payment_status === 'completed')
            @php($pending   = $donation->payment_method === 'bank_transfer')

            <div class="relative px-6 lg:px-10 py-12 lg:py-14
                @if($completed) bg-gradient-to-br from-emerald-500 to-emerald-700
                @elseif($pending) bg-gradient-to-br from-brand-700 to-brand-900
                @else bg-gradient-to-br from-rose-500 to-rose-700 @endif text-white">

                <div class="absolute inset-0 bg-grid-soft opacity-20"></div>

                <div class="relative">
                    <span class="grid place-items-center w-20 h-20 mx-auto rounded-full bg-white/15 backdrop-blur border-2 border-white/30">
                        <i data-lucide="@if($completed)check-circle-2 @elseif($pending)hourglass @else x-circle @endif" class="w-10 h-10"></i>
                    </span>

                    <h1 class="mt-6 text-3xl lg:text-4xl font-extrabold font-display tracking-tight text-balance">
                        @if ($completed) Bağışınız için teşekkürler!
                        @elseif ($pending) Havale bilgileri size iletildi
                        @else İşlem tamamlanamadı
                        @endif
                    </h1>

                    <p class="mt-3 text-white/90 max-w-xl mx-auto">
                        @if ($completed)
                            Cömertliğin bir hayatı daha aydınlatıyor. Hayra Yoldaş olduğun için teşekkür ederiz.
                        @elseif ($pending)
                            Banka hesap bilgilerimiz e-posta ile gönderildi. Havale açıklamasına referans numarasını eklemeyi unutmayın.
                        @else
                            Bağış işleminiz tamamlanamadı. Lütfen tekrar deneyin veya bizimle iletişime geçin.
                        @endif
                    </p>
                </div>
            </div>

            {{-- Detay --}}
            <dl class="grid sm:grid-cols-2 gap-y-3 gap-x-6 px-6 lg:px-10 py-8 text-left">
                <div>
                    <dt class="text-xs uppercase tracking-wider text-slate-500">Referans No</dt>
                    <dd class="mt-1 font-mono font-bold text-brand-900">{{ $donation->reference }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wider text-slate-500">Tutar</dt>
                    <dd class="mt-1 text-2xl font-extrabold font-display text-brand-900">
                        {{ App\Support\Money::format($donation->amount, $donation->currency) }}
                    </dd>
                </div>
                @if ($donation->campaign)
                    <div class="sm:col-span-2">
                        <dt class="text-xs uppercase tracking-wider text-slate-500">Kampanya</dt>
                        <dd class="mt-1 font-bold text-brand-900">{{ $donation->campaign->title_tr }}</dd>
                    </div>
                @endif
                <div>
                    <dt class="text-xs uppercase tracking-wider text-slate-500">Tür</dt>
                    <dd class="mt-1 font-semibold text-brand-900">{{ ucfirst($donation->type) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wider text-slate-500">Sıklık</dt>
                    <dd class="mt-1 font-semibold text-brand-900">
                        @switch($donation->frequency)
                            @case('monthly')Aylık@break
                            @case('quarterly')Üç Aylık@break
                            @case('yearly')Yıllık@break
                            @default Tek Sefer
                        @endswitch
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wider text-slate-500">Tarih</dt>
                    <dd class="mt-1 font-semibold text-brand-900">{{ $donation->created_at->translatedFormat('d F Y, H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wider text-slate-500">Durum</dt>
                    <dd class="mt-1 font-semibold">
                        @switch($donation->payment_status)
                            @case('completed')<span class="text-emerald-600">Tamamlandı</span>@break
                            @case('pending')  <span class="text-amber-600">Bekliyor</span>@break
                            @case('failed')   <span class="text-rose-600">Başarısız</span>@break
                            @default          {{ $donation->payment_status }}
                        @endswitch
                    </dd>
                </div>
            </dl>

            {{-- CTA --}}
            <div class="border-t border-slate-100 p-6 lg:p-8 grid sm:grid-cols-2 gap-3">
                @if ($completed && $donation->certificate_requested)
                    <button type="button" disabled class="btn-outline btn-md justify-center opacity-60 cursor-not-allowed">
                        <i data-lucide="file-down" class="w-4 h-4"></i> Sertifika Hazırlanıyor
                    </button>
                @else
                    <a href="{{ route('campaigns.index') }}" class="btn-outline btn-md justify-center">
                        <i data-lucide="search" class="w-4 h-4"></i> Diğer Kampanyalar
                    </a>
                @endif
                <a href="{{ route('home') }}" class="btn-primary btn-md justify-center">
                    Anasayfaya Dön <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            {{-- Sosyal paylaş --}}
            @if ($completed)
                @php($shareUrl = url('/'))
                @php($shareText = rawurlencode('Refik Derneği ile Hayra Yoldaş oldum. Sen de katılır mısın?'))
                <div class="border-t border-slate-100 p-6 lg:p-8 text-center">
                    <p class="text-xs uppercase tracking-wider text-brand-500 font-semibold mb-3">Paylaş</p>
                    <div class="flex items-center justify-center gap-2">
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ $shareText }}"
                           target="_blank" rel="noopener" class="grid place-items-center w-10 h-10 rounded-full bg-slate-100 hover:bg-brand-50 text-brand-700 transition" aria-label="Twitter / X">
                            <i data-lucide="twitter" class="w-4 h-4"></i>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}"
                           target="_blank" rel="noopener" class="grid place-items-center w-10 h-10 rounded-full bg-slate-100 hover:bg-brand-50 text-brand-700 transition" aria-label="Facebook">
                            <i data-lucide="facebook" class="w-4 h-4"></i>
                        </a>
                        <a href="https://wa.me/?text={{ $shareText }}%20{{ urlencode($shareUrl) }}"
                           target="_blank" rel="noopener" class="grid place-items-center w-10 h-10 rounded-full bg-slate-100 hover:bg-brand-50 text-brand-700 transition" aria-label="WhatsApp">
                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection
