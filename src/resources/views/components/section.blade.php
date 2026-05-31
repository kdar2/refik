@props([
    'eyebrow'  => null,
    'title'    => null,
    'subtitle' => null,
    'align'    => 'left',     // left | center
    'tone'     => 'default',  // default | alt | dark
    'id'       => null,
    'cta'      => null,       // ['label' => '...', 'href' => '...']
])

@php
    $tones = [
        'default' => '',
        'alt'     => 'bg-surface-alt',
        'dark'    => 'bg-brand-900 text-white',
    ];
    $toneClass = $tones[$tone] ?? '';
    $alignClass = $align === 'center' ? 'text-center mx-auto max-w-3xl' : '';
@endphp

<section @if($id) id="{{ $id }}" @endif {{ $attributes->merge(['class' => 'section ' . $toneClass]) }}>
    <div class="container-x">
        @if ($eyebrow || $title || $subtitle)
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6 mb-10" data-rise>
                <div class="{{ $alignClass }}">
                    @if ($eyebrow)
                        <p class="h-eyebrow {{ $tone === 'dark' ? 'text-brand-300' : '' }}">{{ $eyebrow }}</p>
                    @endif
                    @if ($title)
                        <h2 class="mt-2 h-section {{ $tone === 'dark' ? '!text-white' : '' }}">{{ $title }}</h2>
                    @endif
                    @if ($subtitle)
                        <p class="mt-3 max-w-2xl {{ $tone === 'dark' ? 'text-brand-100/90' : 'text-slate-600' }}">
                            {{ $subtitle }}
                        </p>
                    @endif
                </div>
                @if ($cta)
                    <a href="{{ $cta['href'] }}" class="btn-outline btn-md {{ $tone === 'dark' ? '!border-white/30 !text-white hover:!bg-white/10' : '' }}">
                        {{ $cta['label'] }}
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                @endif
            </div>
        @endif

        {{ $slot }}
    </div>
</section>
