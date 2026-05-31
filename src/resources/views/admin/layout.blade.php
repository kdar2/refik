<!DOCTYPE html>
<html lang="tr" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') · Refik Yönetim</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 text-slate-800 font-sans">
    <div x-data="{ sidebarOpen: false }" class="min-h-full flex">

        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky inset-y-0 left-0 z-40 w-64 bg-brand-900 text-brand-100 transform transition-transform">
            <div class="h-16 px-5 flex items-center gap-3 border-b border-white/10">
                <img src="{{ asset(config('site.logo')) }}" alt="{{ config('site.name') }}"
                     class="h-9 w-auto object-contain">
                <div>
                    <p class="text-sm font-extrabold text-white tracking-tight">REFİK</p>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-brand-300">Yönetim</p>
                </div>
            </div>

            <nav class="p-3 space-y-0.5 text-sm">
                @php($section = request()->segment(2))

                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ empty($section) ? 'bg-white/10 text-white' : 'text-brand-200 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Panel
                </a>

                <p class="mt-4 mb-1 px-3 text-[10px] uppercase tracking-wider text-brand-400 font-semibold">İçerik</p>

                <a href="{{ route('admin.campaigns.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $section === 'campaigns' ? 'bg-white/10 text-white' : 'text-brand-200 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="megaphone" class="w-4 h-4"></i> Kampanyalar
                </a>
                <a href="{{ route('admin.posts.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $section === 'posts' ? 'bg-white/10 text-white' : 'text-brand-200 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="newspaper" class="w-4 h-4"></i> Haberler
                </a>
                <a href="{{ route('admin.sliders.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $section === 'sliders' ? 'bg-white/10 text-white' : 'text-brand-200 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="images" class="w-4 h-4"></i> Slider
                </a>
                <a href="{{ route('admin.pages.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $section === 'pages' ? 'bg-white/10 text-white' : 'text-brand-200 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Sayfalar
                </a>

                <p class="mt-4 mb-1 px-3 text-[10px] uppercase tracking-wider text-brand-400 font-semibold">Bağışlar</p>

                <a href="{{ route('admin.donations.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $section === 'donations' ? 'bg-white/10 text-white' : 'text-brand-200 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="hand-coins" class="w-4 h-4"></i> Bağışlar
                </a>

                <p class="mt-4 mb-1 px-3 text-[10px] uppercase tracking-wider text-brand-400 font-semibold">Diğer</p>

                <a href="{{ route('admin.countries.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $section === 'countries' ? 'bg-white/10 text-white' : 'text-brand-200 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="globe-2" class="w-4 h-4"></i> Ülkeler
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $section === 'users' ? 'bg-white/10 text-white' : 'text-brand-200 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="users" class="w-4 h-4"></i> Kullanıcılar
                </a>
                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ $section === 'settings' ? 'bg-white/10 text-white' : 'text-brand-200 hover:bg-white/5 hover:text-white' }}">
                    <i data-lucide="settings-2" class="w-4 h-4"></i> Ayarlar
                </a>
            </nav>

            <div class="absolute bottom-0 inset-x-0 p-4 border-t border-white/10">
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-2 text-xs text-brand-300 hover:text-white transition">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Siteyi Görüntüle
                </a>
            </div>
        </aside>

        {{-- Mobil arka plan overlay --}}
        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
             class="fixed inset-0 bg-slate-900/50 z-30 lg:hidden"></div>

        {{-- Ana içerik --}}
        <div class="flex-1 min-w-0 flex flex-col">
            {{-- Üst çubuk --}}
            <header class="sticky top-0 z-20 bg-white border-b border-slate-200 h-16 flex items-center px-5 gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden grid place-items-center w-9 h-9 rounded-lg bg-slate-100 hover:bg-brand-50 text-slate-700" aria-label="Menü">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <h1 class="text-base lg:text-lg font-bold text-brand-900 truncate">@yield('header', 'Yönetim')</h1>

                <div class="ml-auto flex items-center gap-3">
                    @yield('header_actions')

                    <div x-data="{ open: false }" class="relative">
                        <button type="button" @click="open = !open" @click.away="open = false"
                                class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-100 transition">
                            <span class="grid place-items-center w-8 h-8 rounded-full bg-brand-100 text-brand-700 text-xs font-bold">
                                {{ strtoupper(mb_substr(auth()->user()->name ?? '?', 0, 1)) }}
                            </span>
                            <span class="hidden sm:flex flex-col items-start text-left">
                                <span class="text-xs font-bold text-brand-900 leading-tight">{{ auth()->user()->name }}</span>
                                <span class="text-[10px] uppercase tracking-wider text-brand-500">{{ auth()->user()->role }}</span>
                            </span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition.opacity
                             class="absolute right-0 top-full mt-2 z-50 w-48 rounded-xl bg-white border border-slate-200 shadow-lg py-1.5">
                            <p class="px-3 py-2 text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                            <hr class="border-slate-100 my-1">
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                    <i data-lucide="log-out" class="w-4 h-4"></i> Çıkış Yap
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash mesajlar --}}
            @if (session('success') || session('error'))
                <div class="px-5 lg:px-8 pt-4">
                    @if (session('success'))
                        <div class="rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 flex items-center gap-2 text-sm">
                            <i data-lucide="check-circle" class="w-4 h-4"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="rounded-xl bg-rose-50 border border-rose-200 text-rose-900 px-4 py-3 flex items-center gap-2 text-sm">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i> {{ session('error') }}
                        </div>
                    @endif
                </div>
            @endif

            <main class="flex-1 p-5 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
