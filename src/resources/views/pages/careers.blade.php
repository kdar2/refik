@extends('layouts.app')

@section('title', 'İnsan Kaynakları')

@section('content')

<section class="bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white relative">
    <div class="absolute inset-0 bg-grid-soft opacity-30"></div>
    <div class="container-x py-16 relative">
        <p class="h-eyebrow text-brand-200">İnsan Kaynakları</p>
        <h1 class="mt-2 text-4xl lg:text-5xl font-extrabold font-display tracking-tight">Refik'te Çalış</h1>
        <p class="mt-4 text-brand-100/90 max-w-2xl">Tutkulu, çözüm odaklı ve etki odaklı bir ekiple yer almak için başvurunu bize ilet.</p>
    </div>
</section>

<section class="section">
    <div class="container-x max-w-3xl">
        @if (session('career_success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 mb-6 text-sm text-emerald-900 flex items-start gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('career_success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('careers.store') }}" enctype="multipart/form-data"
              class="rounded-2xl bg-white border border-slate-100 shadow-sm p-6 lg:p-8 space-y-5">
            @csrf
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="label">Ad Soyad <span class="text-rose-500">*</span></label>
                    <input type="text" name="full_name" required maxlength="120" value="{{ old('full_name') }}" class="input">
                    @error('full_name') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Başvurulan pozisyon <span class="text-rose-500">*</span></label>
                    <input type="text" name="position" required maxlength="120" value="{{ old('position') }}" class="input"
                           placeholder="Örn. Saha Koordinatörü">
                    @error('position') <p class="error">{{ $message }}</p> @enderror
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
            </div>

            <div>
                <label class="label">Ön Yazı / Motivasyon</label>
                <textarea name="cover_letter" rows="5" maxlength="5000" class="input resize-none"
                          placeholder="Bu pozisyon için neden uygun olduğunu kısaca anlat.">{{ old('cover_letter') }}</textarea>
            </div>

            <div>
                <label class="label">CV Yükle <span class="text-rose-500">*</span></label>
                <input type="file" name="cv" required accept=".pdf,.doc,.docx"
                       class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:px-4 file:py-2 file:font-semibold hover:file:bg-brand-100">
                <p class="help">PDF, DOC veya DOCX. En fazla 5 MB.</p>
                @error('cv') <p class="error">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-start gap-2 text-sm cursor-pointer">
                <input type="checkbox" name="kvkk" value="1" required {{ old('kvkk') ? 'checked' : '' }}
                       class="h-4 w-4 rounded border-slate-300 text-brand-700 mt-0.5">
                <span class="text-slate-600"><a href="#" class="underline">KVKK aydınlatma metnini</a> okudum.</span>
            </label>
            @error('kvkk') <p class="error">{{ $message }}</p> @enderror

            <button type="submit" class="btn-primary btn-md">
                Başvuruyu Gönder <i data-lucide="briefcase" class="w-4 h-4"></i>
            </button>
        </form>
    </div>
</section>
@endsection
