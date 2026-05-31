@extends('layouts.app')

@section('title', 'İletişim')

@section('content')

<section class="bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white relative">
    <div class="absolute inset-0 bg-grid-soft opacity-30"></div>
    <div class="container-x py-16 relative">
        <p class="h-eyebrow text-brand-200">Bize Ulaşın</p>
        <h1 class="mt-2 text-4xl lg:text-5xl font-extrabold font-display tracking-tight">İletişim</h1>
        <p class="mt-4 text-brand-100/90 max-w-2xl">Sorularınız, önerileriniz ve iş birliği talepleriniz için bizimle iletişime geçebilirsiniz.</p>
    </div>
</section>

<section class="section">
    <div class="container-x grid lg:grid-cols-[1fr_380px] gap-10">
        {{-- Form --}}
        <div data-rise>
            @if (session('contact_success'))
                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 mb-6 text-sm text-emerald-900 flex items-start gap-3">
                    <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                    <span>{{ session('contact_success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('contact.store') }}" class="rounded-2xl bg-white border border-slate-100 shadow-sm p-6 lg:p-8 space-y-4">
                @csrf
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Ad Soyad <span class="text-rose-500">*</span></label>
                        <input type="text" name="full_name" required maxlength="120" value="{{ old('full_name') }}" class="input">
                        @error('full_name') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="label">E-posta <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" required value="{{ old('email') }}" class="input">
                        @error('email') <p class="error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="label">Telefon</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="input">
                    </div>
                    <div>
                        <label class="label">Konu <span class="text-rose-500">*</span></label>
                        <input type="text" name="subject" required maxlength="200" value="{{ old('subject') }}" class="input">
                        @error('subject') <p class="error">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="label">Mesajınız <span class="text-rose-500">*</span></label>
                    <textarea name="message" rows="6" required maxlength="3000" class="input resize-none">{{ old('message') }}</textarea>
                    @error('message') <p class="error">{{ $message }}</p> @enderror
                </div>
                <label class="flex items-start gap-2 text-sm cursor-pointer">
                    <input type="checkbox" name="kvkk" value="1" required {{ old('kvkk') ? 'checked' : '' }}
                           class="h-4 w-4 rounded border-slate-300 text-brand-700 mt-0.5">
                    <span class="text-slate-600"><a href="#" class="underline hover:text-brand-700">KVKK aydınlatma metnini</a> okudum.</span>
                </label>
                @error('kvkk') <p class="error">{{ $message }}</p> @enderror

                <button type="submit" class="btn-primary btn-md">
                    Gönder <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </form>
        </div>

        {{-- İletişim bilgileri --}}
        <aside class="space-y-4" data-rise>
            <div class="rounded-2xl bg-brand-700 text-white p-6">
                <p class="h-eyebrow text-brand-200">Adres</p>
                <p class="mt-1 text-base font-semibold leading-relaxed">{{ config('site.contact.address') }}</p>
            </div>
            <a href="tel:{{ str_replace(' ', '', config('site.contact.phone')) }}" class="block rounded-2xl bg-white border border-slate-100 p-5 hover:border-brand-300 hover:shadow-md transition group">
                <p class="h-eyebrow">Telefon</p>
                <p class="mt-1 text-base font-bold text-brand-900 group-hover:text-accent-600 transition">{{ config('site.contact.phone') }}</p>
            </a>
            <a href="mailto:{{ config('site.contact.email') }}" class="block rounded-2xl bg-white border border-slate-100 p-5 hover:border-brand-300 hover:shadow-md transition group">
                <p class="h-eyebrow">E-posta</p>
                <p class="mt-1 text-base font-bold text-brand-900 group-hover:text-accent-600 transition">{{ config('site.contact.email') }}</p>
            </a>
            <a href="https://wa.me/{{ ltrim(config('site.contact.whatsapp'), '+') }}" target="_blank" rel="noopener" class="block rounded-2xl bg-emerald-50 border border-emerald-200 p-5 hover:bg-emerald-100 transition">
                <p class="text-xs uppercase tracking-wider font-semibold text-emerald-700">WhatsApp Hattı</p>
                <p class="mt-1 text-base font-bold text-emerald-800 flex items-center gap-2">
                    <i data-lucide="message-circle" class="w-5 h-5"></i> Bize hemen yazın
                </p>
            </a>
            <div class="rounded-2xl bg-white border border-slate-100 p-5">
                <p class="h-eyebrow">Çalışma Saatleri</p>
                <p class="mt-1 text-sm text-slate-700">Pazartesi – Cuma: 09:00 – 18:00</p>
                <p class="text-sm text-slate-700">Cumartesi: 10:00 – 14:00</p>
            </div>
        </aside>
    </div>
</section>
@endsection
