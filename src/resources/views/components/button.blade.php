@props([
    'variant' => 'primary',  // primary | accent | gold | outline | ghost
    'size'    => 'md',       // sm | md | lg
    'href'    => null,
    'icon'    => null,
    'iconRight' => null,
    'type'    => 'button',
])

@php
    $variants = [
        'primary' => 'btn-primary',
        'accent'  => 'btn-accent',
        'gold'    => 'btn-gold',
        'outline' => 'btn-outline',
        'ghost'   => 'btn-ghost',
    ];
    $sizes = [
        'sm' => 'btn-sm',
        'md' => 'btn-md',
        'lg' => 'btn-lg',
    ];
    $variantClass = $variants[$variant] ?? 'btn-primary';
    $sizeClass    = $sizes[$size]       ?? 'btn-md';
    // .btn-primary vs zaten btn-md içeriyor; yine de size override edebilmek için manuel ekleriz.
    $classes = $variantClass . ($size !== 'md' ? ' ' . $sizeClass : '');
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
        @endif
        {{ $slot }}
        @if ($iconRight)
            <i data-lucide="{{ $iconRight }}" class="w-4 h-4"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($icon)
            <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
        @endif
        {{ $slot }}
        @if ($iconRight)
            <i data-lucide="{{ $iconRight }}" class="w-4 h-4"></i>
        @endif
    </button>
@endif
