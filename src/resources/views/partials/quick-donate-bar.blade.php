{{-- Sticky alt bağış çubuğu (her sayfada) --}}
<div class="sticky-bottom-bar pb-safe">
    <form action="{{ route('donate.show') }}" method="GET" class="container-x py-3">
        <div class="flex flex-wrap lg:flex-nowrap items-center gap-2">

            <x-select-pretty
                name="campaign"
                icon="heart-handshake"
                class="w-full sm:max-w-xs sm:flex-1"
                :selected="''"
                :options="[
                    ''                          => 'Genel Bağış',
                    'gazze-acil-sicak-yemek'    => 'Gazze Acil — Sıcak Yemek',
                    'ramazan-gida-paketi'       => 'Ramazan Gıda Paketi',
                    'yetim-sponsorlugu'         => 'Yetim Sponsorluğu',
                    'su-kuyusu-ac'              => 'Su Kuyusu Aç',
                    'ilim-yolcusuna-destek'     => 'İlim Yolcusuna Destek',
                ]"
            />

            <x-select-pretty
                name="frequency"
                icon="repeat-2"
                class="w-full sm:max-w-[180px]"
                :selected="'recurring'"
                :options="[
                    'recurring' => 'Düzenli Ödeme',
                    'one_time'  => 'Tek Sefer',
                ]"
            />

            <x-select-pretty
                name="amount"
                icon="banknote"
                class="w-full sm:max-w-[140px]"
                :selected="'100'"
                :options="[
                    '50'   => '50 TL',
                    '100'  => '100 TL',
                    '250'  => '250 TL',
                    '500'  => '500 TL',
                    '1000' => '1.000 TL',
                ]"
            />

            <x-select-pretty
                name="type"
                icon="bookmark"
                class="w-full sm:max-w-[140px]"
                :selected="'zakat'"
                :options="[
                    'zakat'  => 'Zekat',
                    'fitre'  => 'Fitre',
                    'kurban' => 'Kurban',
                    'sadaka' => 'Sadaka',
                    'genel'  => 'Genel',
                ]"
            />

            <a href="{{ route('cart.show') }}"
               class="hidden md:flex items-center gap-2 px-3 py-2 rounded-lg bg-brand-50 text-brand-700 text-sm font-semibold ml-auto hover:bg-brand-100 transition">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                Sepet
                @if (($topbarCartCount ?? 0) > 0)
                    <span class="rounded-full bg-accent-500 text-white text-[11px] px-2 py-0.5">{{ $topbarCartCount }}</span>
                @endif
                <span class="font-extrabold">{{ $topbarCartTotal ?? '0,00 ₺' }}</span>
            </a>

            <button type="submit" class="btn-accent btn-md w-full md:w-auto !rounded-lg">
                <i data-lucide="heart" class="w-4 h-4"></i>
                Hızlı Bağış Yap
            </button>
        </div>
    </form>
</div>
