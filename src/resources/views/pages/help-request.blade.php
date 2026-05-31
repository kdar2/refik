@extends('layouts.app')

@section('title', 'Yardım Talebi')

@section('content')

<section class="bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white relative">
    <div class="absolute inset-0 bg-grid-soft opacity-30"></div>
    <div class="container-x py-16 relative">
        <p class="h-eyebrow text-brand-200">Yanınızdayız</p>
        <h1 class="mt-2 text-4xl lg:text-5xl font-extrabold font-display tracking-tight">Yardım Talebi</h1>
        <p class="mt-4 text-brand-100/90 max-w-2xl">İhtiyacınızı bize iletin; ekibimiz uygunluk değerlendirmesi yaparak en kısa sürede sizinle iletişime geçecektir.</p>
    </div>
</section>

<section class="section">
    <div class="container-x max-w-3xl">
        @if (session('help_success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 mb-6 text-sm text-emerald-900 flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('help_success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('help-request.store') }}" class="rounded-2xl bg-white border border-slate-100 shadow-sm p-6 lg:p-8 space-y-5">
            @csrf
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Ad Soyad <span class="text-rose-500">*</span></label>
                    <input type="text" name="full_name" required maxlength="120" value="{{ old('full_name') }}" class="input">
                    @error('full_name') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Telefon <span class="text-rose-500">*</span></label>
                    <input type="tel" name="phone" required value="{{ old('phone') }}" class="input">
                    @error('phone') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">E-posta</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="input">
                </div>
                <div>
                    <label class="label">Yardım kategorisi <span class="text-rose-500">*</span></label>
                    <select name="category" required class="input">
                        <option value="">Seçin</option>
                        @foreach (['gida' => 'Gıda', 'saglik' => 'Sağlık', 'barinma' => 'Barınma', 'egitim' => 'Eğitim', 'giyim' => 'Giyim', 'diger' => 'Diğer'] as $val => $label)
                            <option value="{{ $val }}" @selected(old('category')===$val)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Şehir <span class="text-rose-500">*</span></label>
                    <input type="text" name="city" required maxlength="80" value="{{ old('city') }}" class="input">
                    @error('city') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">İlçe <span class="text-rose-500">*</span></label>
                    <input type="text" name="district" required maxlength="80" value="{{ old('district') }}" class="input">
                    @error('district') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="label">Durumunuzu kısaca anlatın <span class="text-rose-500">*</span></label>
                <textarea name="description" rows="6" required maxlength="3000" class="input resize-none"
                          placeholder="Hane büyüklüğünüz, ihtiyaç duyduğunuz desteğin niteliği ve aciliyet seviyesi.">{{ old('description') }}</textarea>
                @error('description') <p class="error">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-start gap-2 text-sm cursor-pointer">
                <input type="checkbox" name="kvkk" value="1" required {{ old('kvkk') ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-slate-300 text-brand-700 mt-0.5">
                <span class="text-slate-600"><a href="#" class="underline">KVKK aydınlatma metnini</a> okudum.</span>
            </label>
            @error('kvkk') <p class="error">{{ $message }}</p> @enderror

            <p class="text-xs text-slate-500 bg-slate-50 border border-slate-200 rounded-lg p-3">
                Tüm yardım talepleri sosyal yardım ekibimiz tarafından değerlendirilir. Acil durumlarda WhatsApp hattımızdan da bize ulaşabilirsiniz.
            </p>

            <button type="submit" class="btn-primary btn-md">
                Talep Oluştur <i data-lucide="hand-helping" class="w-4 h-4"></i>
            </button>
        </form>
    </div>
</section>
@endsection
