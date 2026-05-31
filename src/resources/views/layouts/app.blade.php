<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0B295C">

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset(config('site.logo')) }}">
    <link rel="apple-touch-icon" href="{{ asset(config('site.logo')) }}">
    <link rel="shortcut icon" href="{{ asset(config('site.logo')) }}">

    <title>@yield('title', config('app.name', 'Refik Derneği')) — Refik Derneği</title>
    <meta name="description" content="@yield('description', 'Refik Derneği — Hayra Yoldaş. Bağışlarınızla dünyada ihtiyaç sahiplerine umut oluyoruz.')">

    {{-- Canonical & alternate dilller --}}
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="tr" href="{{ url()->current() }}?lang=tr">
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}?lang=en">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

    {{-- OpenGraph --}}
    <meta property="og:type"        content="@yield('og_type', 'website')">
    <meta property="og:site_name"   content="{{ config('site.name') }}">
    <meta property="og:locale"      content="{{ app()->getLocale() === 'tr' ? 'tr_TR' : 'en_US' }}">
    <meta property="og:title"       content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'Hayra Yoldaş — ' . config('site.legal_name'))">
    <meta property="og:image"       content="@yield('og_image', asset('images/og-default.jpg'))">
    <meta property="og:url"         content="{{ url()->current() }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="@yield('og_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('og_description', 'Hayra Yoldaş — ' . config('site.legal_name'))">
    <meta name="twitter:image"       content="@yield('og_image', asset('images/og-default.jpg'))">

    {{-- Robots --}}
    <meta name="robots" content="index, follow, max-image-preview:large">

    {{-- Fontlar --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800|inter:400,500,600,700&display=swap">

    {{-- Tailwind + JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Schema.org --}}
    @include('partials.schema-org')

    @stack('head')
</head>
<body class="min-h-screen flex flex-col">
    <a href="#main" class="skip-link">İçeriğe atla</a>

    @include('partials.alert-bar')
    @include('partials.topbar')
    @include('partials.header')

    <main id="main" class="flex-1">
        @yield('content')
    </main>

    @include('partials.newsletter')
    @include('partials.footer')
    @include('partials.quick-donate-bar')
    @include('partials.a11y-widget')

    @stack('scripts')
</body>
</html>
