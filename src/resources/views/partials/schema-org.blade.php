{{-- Organization schema — her sayfada head içinde --}}
@php
    // NOT: '@context' / '@type' anahtarlarını concat ile inşa ediyoruz çünkü
    // Blade '@context' literal'ini directive olarak yorumluyor ve JSON'u kırıyor.
    $atC = '@' . 'context';
    $atT = '@' . 'type';

    $orgSchema = [
        $atC            => 'https://schema.org',
        $atT            => 'NGO',
        'name'          => config('site.legal_name'),
        'alternateName' => config('site.name'),
        'url'           => url('/'),
        'logo'          => asset(config('site.logo')),
        'sameAs'        => array_values(array_filter(config('site.social', []))),
        'address'       => [
            $atT             => 'PostalAddress',
            'streetAddress'  => config('site.contact.address'),
            'addressCountry' => 'TR',
        ],
        'contactPoint'  => [
            $atT          => 'ContactPoint',
            'contactType' => 'customer service',
            'telephone'   => config('site.contact.phone'),
            'email'       => config('site.contact.email'),
            'areaServed'  => 'Worldwide',
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($orgSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>

{{-- Sayfaya özel schema — section'larda override edilir --}}
@stack('schema')
