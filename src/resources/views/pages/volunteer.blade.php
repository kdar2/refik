@extends('layouts.app')

@section('title', 'Gönüllümüz Olun')

@section('content')

<section class="bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white relative">
    <div class="absolute inset-0 bg-grid-soft opacity-30"></div>
    <div class="container-x py-16 relative">
        <p class="h-eyebrow text-brand-200">Hayra Yoldaş</p>
        <h1 class="mt-2 text-4xl lg:text-5xl font-extrabold font-display tracking-tight">Gönüllümüz Olun</h1>
        <p class="mt-4 text-brand-100/90 max-w-2xl">Saha, eğitim, tercüme, iletişim ve daha fazlası. Sen de iyiliğin yolculuğuna katılabilirsin.</p>
    </div>
</section>

<section class="section">
    <div class="container-x max-w-3xl">
        @if (session('volunteer_success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 mb-6 text-sm text-emerald-900 flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('volunteer_success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('volunteer.store') }}" class="rounded-2xl bg-white border border-slate-100 shadow-sm p-6 lg:p-8 space-y-5">
            @csrf
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Ad Soyad <span class="text-rose-500">*</span></label>
                    <input type="text" name="full_name" required maxlength="120" value="{{ old('full_name') }}" class="input">
                    @error('full_name') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Doğum tarihi <span class="text-rose-500">*</span></label>
                    <input type="date" name="birth_date" required value="{{ old('birth_date') }}" class="input">
                    @error('birth_date') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">E-posta <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" required value="{{ old('email') }}" class="input">
                    @error('email') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Telefon <span class="text-rose-500">*</span></label>
                    <input type="tel" name="phone" required value="{{ old('phone') }}" class="input">
                    @error('phone') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div class="sm:col-span-2">
                    <label class="label">Şehir <span class="text-rose-500">*</span></label>
                    <input type="text" name="city" required maxlength="80" value="{{ old('city') }}" class="input">
                    @error('city') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="label">İlgilendiğin alanlar <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    @foreach (['saha' => 'Saha', 'egitim' => 'Eğitim', 'tercume' => 'Tercüme', 'iletisim' => 'İletişim & Medya', 'lojistik' => 'Lojistik', 'organizasyon' => 'Organizasyon'] as $val => $label)
                        <label class="flex items-center gap-2 rounded-lg bg-slate-50 px-3 py-2 cursor-pointer hover:bg-brand-50 text-sm">
                            <input type="checkbox" name="areas[]" value="{{ $val }}"
                                   {{ in_array($val, (array) old('areas', [])) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-slate-300 text-brand-700">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('areas') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="label">Deneyimleriniz</label>
                <textarea name="experience" rows="4" maxlength="2000" class="input resize-none"
                          placeholder="Daha önce yer aldığın gönüllü çalışmaları, yetkinliklerin ve katılım sıklığın hakkında bilgi ver.">{{ old('experience') }}</textarea>
            </div>

            <label class="flex items-start gap-2 text-sm cursor-pointer">
                <input type="checkbox" name="kvkk" value="1" required {{ old('kvkk') ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-slate-300 text-brand-700 mt-0.5">
                <span class="text-slate-600"><a href="#" class="underline">KVKK aydınlatma metnini</a> okudum.</span>
            </label>
            @error('kvkk') <p class="error">{{ $message }}</p> @enderror

            <button type="submit" class="btn-primary btn-md">
                Başvur <i data-lucide="heart-handshake" class="w-4 h-4"></i>
            </button>
        </form>
    </div>
</section>
@endsection
