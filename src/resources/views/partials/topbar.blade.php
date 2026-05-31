{{-- Üst bilgi çubuğu — hicri tarih, sıradaki namaz, son bağış, hesap & dil & sepet --}}
<div class="hidden lg:block border-b border-slate-200 bg-white text-xs text-slate-600">
    <div class="container-x flex items-center justify-between py-2">

        <div class="flex items-center gap-6">
            {{-- Hicri tarih (sunucu tarafı, Umm al-Qura) --}}
            <span class="flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-brand-500"></i>
                <span class="font-semibold text-slate-700">Hicri:</span>
                <span>{{ $topbarHijri ?: '—' }}</span>
            </span>

            {{-- Sıradaki namaz vakti (Diyanet, canlı geri sayım) --}}
            @if (!empty($topbarNextPrayer))
                <span class="flex items-center gap-2"
                      x-data="prayerCountdown('{{ $topbarNextPrayer['iso'] }}')"
                      x-init="start()">
                    <i data-lucide="clock" class="w-4 h-4 text-brand-500"></i>
                    <span class="font-semibold text-slate-700">Sıradaki Namaz:</span>
                    <span>
                        {{ $topbarNextPrayer['name'] }} — {{ $topbarNextPrayer['time'] }}@if (!empty($topbarNextPrayer['is_tomorrow'])) <span class="text-slate-400">(yarın)</span>@endif
                    </span>
                    <span class="hidden xl:inline text-slate-400" x-show="remaining" x-cloak>
                        (<span x-text="remaining"></span>)
                    </span>
                </span>
            @else
                <span class="flex items-center gap-2 text-slate-400">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                    <span class="font-semibold">Sıradaki Namaz:</span>
                    <span>Vakit alınamadı</span>
                </span>
            @endif

            {{-- En son bağış (DB'den, anonim/maskeli) --}}
            @if (!empty($topbarLastDonation))
                <span class="flex items-center gap-2">
                    <i data-lucide="heart-handshake" class="w-4 h-4 text-accent-500"></i>
                    <span class="font-semibold text-slate-700">Son Bağış:</span>
                    <span>
                        {{ $topbarLastDonation['donor'] }} — {{ $topbarLastDonation['amount'] }}@if (!empty($topbarLastDonation['campaign'])) — {{ $topbarLastDonation['campaign'] }}@endif
                    </span>
                </span>
            @endif
        </div>

        <div class="flex items-center gap-4">
            @php($locale = app()->getLocale())
            @php($currency = config('currencies.active', config('currencies.default', 'TRY')))

            <a href="#" class="flex items-center gap-1.5 hover:text-brand-700 transition">
                <i data-lucide="user" class="w-4 h-4"></i>
                {{ __('site.nav.login') }}
            </a>
            <span class="text-slate-300">|</span>

            {{-- Dil değiştirici --}}
            <div x-data="{ open: false }" class="relative">
                <button type="button" @click="open = !open" @click.away="open = false"
                        class="flex items-center gap-1.5 hover:text-brand-700 transition">
                    <i data-lucide="globe" class="w-4 h-4"></i>
                    <span class="uppercase">{{ $locale }}</span>
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </button>
                <div x-show="open" x-cloak x-transition.opacity
                     class="absolute right-0 top-full mt-2 z-50 min-w-[100px] rounded-lg bg-white border border-slate-200 shadow-lg py-1">
                    <a href="?lang=tr" class="block px-3 py-1.5 text-xs hover:bg-brand-50 {{ $locale === 'tr' ? 'font-bold text-brand-700' : '' }}">Türkçe</a>
                    <a href="?lang=en" class="block px-3 py-1.5 text-xs hover:bg-brand-50 {{ $locale === 'en' ? 'font-bold text-brand-700' : '' }}">English</a>
                </div>
            </div>

            <span class="text-slate-300">|</span>

            {{-- Para birimi --}}
            <div x-data="{ open: false }" class="relative">
                <button type="button" @click="open = !open" @click.away="open = false"
                        class="flex items-center gap-1.5 hover:text-brand-700 transition">
                    {{ $currency }}
                    <i data-lucide="chevron-down" class="w-3 h-3"></i>
                </button>
                <div x-show="open" x-cloak x-transition.opacity
                     class="absolute right-0 top-full mt-2 z-50 min-w-[100px] rounded-lg bg-white border border-slate-200 shadow-lg py-1">
                    @foreach (['TRY' => '₺ TRY', 'USD' => '$ USD', 'EUR' => '€ EUR'] as $code => $label)
                        <a href="?currency={{ $code }}" class="block px-3 py-1.5 text-xs hover:bg-brand-50 {{ $currency === $code ? 'font-bold text-brand-700' : '' }}">{{ $label }}</a>
                    @endforeach
                </div>
            </div>

            <span class="text-slate-300">|</span>

            {{-- Bağış sepeti — gerçek session sepeti --}}
            <a href="{{ route('cart.show') }}"
               class="flex items-center gap-1.5 font-semibold text-brand-700 hover:text-brand-900 transition relative">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                {{ __('site.nav.cart') }}
                @if ($topbarCartCount > 0)
                    <span class="absolute -top-1 -right-2 min-w-[18px] h-[18px] flex items-center justify-center rounded-full bg-accent-500 px-1 text-[10px] font-bold text-white">
                        {{ $topbarCartCount }}
                    </span>
                @endif
                <span class="ml-1 rounded-full bg-brand-50 px-2 py-0.5">{{ $topbarCartTotal }}</span>
            </a>
        </div>

    </div>
</div>
