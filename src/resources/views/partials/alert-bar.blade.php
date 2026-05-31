{{-- Acil Duyuru Çubuğu (sayfa en üst) --}}
<div id="alert-bar" class="bg-amber-300 text-slate-900 transition-opacity duration-300">
    <div class="container-x flex items-center justify-center gap-3 py-2 text-sm font-medium">
        <span class="badge-emergency uppercase tracking-wider">ACİL</span>
        <span class="text-balance">
            Gazze'de yardıma ihtiyacı olan kardeşlerimize destek ol!
            <a href="{{ route('home') }}#kampanyalar" class="underline underline-offset-2 font-semibold hover:no-underline">
                Hemen yardım et →
            </a>
        </span>
        <button data-close class="ml-auto -mr-1 rounded-full p-1 hover:bg-amber-400 transition" aria-label="Duyuruyu kapat">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    </div>
</div>
