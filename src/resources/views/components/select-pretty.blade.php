@props([
    'name',
    'options' => [],          // ['value' => 'label'] formatı
    'selected' => null,       // başlangıçta seçili value
    'placeholder' => null,    // value="" için gösterilecek metin (opsiyonel)
    'icon' => null,           // tetikleyicinin solundaki lucide ikon
    'openUp' => true,         // panel yukarı mı açılsın (sticky bottom bar için true)
])

@php
    $normalized = [];
    foreach ($options as $key => $val) {
        $normalized[] = ['value' => (string) $key, 'label' => (string) $val];
    }

    $selectedValue = $selected !== null ? (string) $selected : '';
    $selectedLabel = $placeholder;
    $matched = false;
    foreach ($normalized as $opt) {
        if ($opt['value'] === $selectedValue) {
            $selectedLabel = $opt['label'];
            $matched = true;
            break;
        }
    }
    if (!$matched && $selectedValue === '' && $placeholder === null && count($normalized) > 0) {
        $selectedValue = $normalized[0]['value'];
        $selectedLabel = $normalized[0]['label'];
    }
    if ($selectedLabel === null) {
        $selectedLabel = '—';
    }
@endphp

<div
    x-data='{
        open: false,
        value: @json($selectedValue),
        label: @json($selectedLabel),
        options: @json($normalized),
        focusIndex: -1,
        init() { this.value = @json($selectedValue); this.label = @json($selectedLabel); },
        select(opt) { this.value = opt.value; this.label = opt.label; this.open = false; },
        toggle() { this.open = !this.open; if (this.open) this.focusIndex = this.options.findIndex(o => o.value === this.value); },
        move(delta) {
            if (!this.open) { this.open = true; return; }
            const n = this.options.length;
            this.focusIndex = ((this.focusIndex < 0 ? 0 : this.focusIndex + delta) + n) % n;
        },
        commit() {
            if (this.open && this.focusIndex >= 0) this.select(this.options[this.focusIndex]);
        },
    }'
    @keydown.escape.window="open = false"
    @click.outside="open = false"
    {{ $attributes->merge(['class' => 'relative']) }}>

    {{-- Form'a giden gerçek değer --}}
    <input type="hidden" name="{{ $name }}" :value="value">

    {{-- Tetikleyici --}}
    <button type="button"
            @click="toggle()"
            @keydown.arrow-down.prevent="move(1)"
            @keydown.arrow-up.prevent="move(-1)"
            @keydown.enter.prevent="open ? commit() : (open = true)"
            :aria-expanded="open"
            aria-haspopup="listbox"
            class="w-full inline-flex items-center justify-between gap-2 px-4 py-2.5 rounded-lg bg-white border border-slate-200 hover:border-brand-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-200 focus:outline-none text-sm font-medium text-slate-800 shadow-sm transition">
        <span class="flex items-center gap-2 min-w-0">
            @if ($icon)
                <i data-lucide="{{ $icon }}" class="w-4 h-4 text-brand-600 shrink-0"></i>
            @endif
            <span class="truncate" x-text="label"></span>
        </span>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 transition duration-200"
             :class="open ? '-rotate-180 text-brand-700' : ''"
             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="6 9 12 15 18 9"></polyline>
        </svg>
    </button>

    {{-- Açılır panel --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 {{ $openUp ? 'translate-y-2' : '-translate-y-2' }} scale-[0.98]"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 {{ $openUp ? 'translate-y-2' : '-translate-y-2' }} scale-[0.98]"
         class="absolute left-0 right-0 min-w-full z-50 origin-{{ $openUp ? 'bottom' : 'top' }} {{ $openUp ? 'bottom-full mb-2' : 'top-full mt-2' }} rounded-xl bg-white shadow-xl ring-1 ring-slate-900/5 border border-slate-100 overflow-hidden"
         role="listbox">
        <ul class="max-h-72 overflow-y-auto py-1.5">
            <template x-for="(opt, idx) in options" :key="opt.value">
                <li>
                    <button type="button"
                            @click="select(opt)"
                            @mouseenter="focusIndex = idx"
                            :class="[
                                value === opt.value ? 'bg-brand-700 text-white font-semibold' : 'text-slate-700',
                                value !== opt.value && focusIndex === idx ? 'bg-brand-50 text-brand-900' : '',
                                value !== opt.value && focusIndex !== idx ? 'hover:bg-brand-50 hover:text-brand-900' : '',
                            ]"
                            class="w-full text-left px-4 py-2.5 text-sm flex items-center justify-between gap-3 transition-colors">
                        <span x-text="opt.label" class="truncate"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0"
                             x-show="value === opt.value" x-cloak
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.25"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </button>
                </li>
            </template>
        </ul>
    </div>
</div>
