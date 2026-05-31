<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giriş — Refik Yönetim</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,600,700,800|inter:400,500,600,700&display=swap">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 flex items-center justify-center p-6">
    <div class="absolute inset-0 bg-grid-soft opacity-20 pointer-events-none"></div>

    <div class="relative w-full max-w-md">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-3">
                <img src="{{ asset(config('site.logo')) }}" alt="{{ config('site.name') }}"
                     class="h-12 w-auto object-contain">
                <div class="text-left">
                    <p class="text-2xl font-extrabold font-display text-white tracking-tight leading-none">REFİK</p>
                    <p class="text-[10px] uppercase tracking-[0.25em] text-brand-300 font-semibold">Yönetim Paneli</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.login.post') }}" class="rounded-3xl bg-white shadow-brand p-8 lg:p-10">
            @csrf
            <h1 class="text-2xl font-extrabold font-display text-brand-900">Hoş geldin</h1>
            <p class="mt-1 text-sm text-slate-500">Yönetim paneline erişmek için giriş yap.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-900 flex items-start gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="mt-6 space-y-4">
                <div>
                    <label class="label">E-posta</label>
                    <input type="email" name="email" required autofocus value="{{ old('email') }}" class="input">
                </div>
                <div>
                    <label class="label">Şifre</label>
                    <input type="password" name="password" required class="input">
                </div>
                <label class="flex items-center gap-2 text-sm cursor-pointer text-slate-700">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-brand-700">
                    Beni hatırla
                </label>
            </div>

            <button type="submit" class="btn-primary btn-md w-full justify-center mt-6">
                Giriş Yap <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </button>

            <p class="mt-6 text-center text-xs text-slate-500">
                Refik Eğitim, Kültür ve Yardımlaşma Derneği &middot;
                <a href="{{ route('home') }}" class="hover:text-brand-700">Ana siteye dön</a>
            </p>
        </form>
    </div>
</body>
</html>
