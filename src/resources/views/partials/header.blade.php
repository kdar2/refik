{{-- Ana Menü (Header) --}}
<header id="site-header"
        x-data="{ mobileOpen: false, searchOpen: false }"
        class="sticky top-0 z-30 bg-white transition-all duration-200 border-b border-slate-100">

    <div class="container-x flex items-center gap-6 py-4">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 group" aria-label="{{ config('site.legal_name') }}">
            <img src="{{ asset(config('site.logo')) }}" alt="{{ config('site.name') }}"
                 class="h-10 w-auto object-contain group-hover:scale-105 transition">
        </a>

        {{-- Desktop nav --}}
        <nav class="hidden lg:flex items-center gap-7 ml-6 text-sm font-semibold text-slate-700">
            <a href="{{ route('campaigns.index') }}" class="hover:text-brand-700 transition">Çalışmalarımız</a>
            <a href="{{ route('countries.index') }}" class="hover:text-brand-700 transition">Nerede Çalışıyoruz</a>
            <a href="{{ route('impact') }}" class="hover:text-brand-700 transition">Etki & Güvence</a>
            <a href="{{ route('about') }}" class="hover:text-brand-700 transition">Hakkımızda</a>
        </nav>

        {{-- Sağ aksiyonlar --}}
        <div class="hidden lg:flex items-center gap-3 ml-auto">
            <button @click="searchOpen = !searchOpen"
                    class="grid place-items-center w-10 h-10 rounded-full hover:bg-brand-50 text-brand-700 transition"
                    aria-label="Ara">
                <i data-lucide="search" class="w-5 h-5"></i>
            </button>

            <a href="{{ route('campaigns.index') }}?category=kurban" class="btn-gold btn-sm shadow-soft">
                <i data-lucide="cookie" class="w-4 h-4"></i>
                Kurban 2026
            </a>
            <a href="{{ route('donate.show') }}?type=zakat" class="btn-primary btn-sm">
                <i data-lucide="hand-coins" class="w-4 h-4"></i>
                Zekat Ver
            </a>
            <a href="{{ route('donate.show') }}" class="btn-accent btn-sm">
                <i data-lucide="heart" class="w-4 h-4"></i>
                Bağış Yap
            </a>
        </div>

        {{-- Mobil hamburger --}}
        <button class="lg:hidden ml-auto grid place-items-center w-10 h-10 rounded-lg bg-brand-50 text-brand-700"
                @click="mobileOpen = !mobileOpen" aria-label="Menü">
            <i data-lucide="menu" class="w-6 h-6" x-show="!mobileOpen"></i>
            <i data-lucide="x" class="w-6 h-6" x-show="mobileOpen" x-cloak></i>
        </button>
    </div>

    {{-- Arama paneli --}}
    <div x-show="searchOpen" x-cloak x-transition class="border-t border-slate-100 bg-surface-alt">
        <div class="container-x py-5">
            <form action="#" method="GET" class="flex gap-3">
                <input type="search" name="q" placeholder="Kampanya, haber veya bölge ara…" class="input">
                <button class="btn-primary btn-md">Ara</button>
            </form>
        </div>
    </div>

    {{-- Mobil menü --}}
    <div x-show="mobileOpen" x-cloak x-transition.opacity class="lg:hidden border-t border-slate-100 bg-white">
        <nav class="container-x py-4 flex flex-col gap-1 text-sm font-semibold">
            <a href="{{ route('campaigns.index') }}" class="px-3 py-2.5 rounded-lg hover:bg-brand-50 text-slate-700">Çalışmalarımız</a>
            <a href="{{ route('countries.index') }}" class="px-3 py-2.5 rounded-lg hover:bg-brand-50 text-slate-700">Nerede Çalışıyoruz</a>
            <a href="{{ route('impact') }}" class="px-3 py-2.5 rounded-lg hover:bg-brand-50 text-slate-700">Etki & Güvence</a>
            <a href="{{ route('about') }}" class="px-3 py-2.5 rounded-lg hover:bg-brand-50 text-slate-700">Hakkımızda</a>
            <div class="grid grid-cols-3 gap-2 mt-3">
                <a href="{{ route('campaigns.index') }}?category=kurban" class="btn-gold btn-sm justify-center">Kurban</a>
                <a href="{{ route('donate.show') }}?type=zakat" class="btn-primary btn-sm justify-center">Zekat</a>
                <a href="{{ route('donate.show') }}" class="btn-accent btn-sm justify-center">Bağış</a>
            </div>
        </nav>
    </div>
</header>
