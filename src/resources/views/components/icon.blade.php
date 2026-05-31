@props([
    'name',
    'size' => 5,
    'strokeWidth' => 1.75,
])

{{-- Lucide ikon wrapper. JS tarafı `createIcons` ile data-lucide attr'larını render eder. --}}
<i {{ $attributes->merge(['class' => 'inline-block w-' . $size . ' h-' . $size]) }}
   data-lucide="{{ $name }}"
   stroke-width="{{ $strokeWidth }}"></i>
