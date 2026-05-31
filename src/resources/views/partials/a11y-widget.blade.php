{{-- Erişilebilirlik widget'ı (sağ kenar sticky) --}}
<div x-data="{ open: false, hc: false, big: false }"
     class="fixed right-3 top-1/2 -translate-y-1/2 z-30">
    <button @click="open = !open"
            class="grid place-items-center w-12 h-12 rounded-full bg-brand-700 text-white shadow-brand hover:bg-brand-900 transition"
            aria-label="Erişilebilirlik">
        <i data-lucide="accessibility" class="w-5 h-5"></i>
    </button>

    <div x-show="open" x-cloak x-transition
         class="absolute right-14 top-1/2 -translate-y-1/2 w-64 rounded-2xl bg-white shadow-xl border border-slate-100 p-4">
        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Erişilebilirlik</p>

        <button class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-lg hover:bg-brand-50 text-sm"
                @click="hc = !hc; document.documentElement.classList.toggle('high-contrast', hc)">
            <span class="flex items-center gap-2"><i data-lucide="contrast" class="w-4 h-4"></i> Yüksek Kontrast</span>
            <span class="text-xs font-semibold" x-text="hc ? 'AÇIK' : 'KAPALI'"></span>
        </button>

        <button class="w-full flex items-center justify-between gap-2 px-3 py-2 rounded-lg hover:bg-brand-50 text-sm"
                @click="big = !big; document.documentElement.style.fontSize = big ? '112%' : '100%'">
            <span class="flex items-center gap-2"><i data-lucide="type" class="w-4 h-4"></i> Yazıyı Büyüt</span>
            <span class="text-xs font-semibold" x-text="big ? 'AÇIK' : 'KAPALI'"></span>
        </button>
    </div>
</div>
