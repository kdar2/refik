@extends('admin.layout')

@section('title', $mode === 'create' ? 'Yeni Ülke' : $country->name_tr)
@section('header', $mode === 'create' ? 'Yeni Ülke' : $country->name_tr)

@section('header_actions')
    <a href="{{ route('admin.countries.index') }}" class="btn-ghost btn-sm">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Geri
    </a>
@endsection

@section('content')

<form method="POST" action="{{ $mode === 'create' ? route('admin.countries.store') : route('admin.countries.update', $country) }}"
      class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6 space-y-4 max-w-2xl">
    @csrf
    @if ($mode === 'edit') @method('PUT') @endif

    <div class="grid sm:grid-cols-2 gap-4">
        <div>
            <label class="label">ISO Kodu (3 hane) <span class="text-rose-500">*</span></label>
            <input type="text" name="code" required maxlength="3" minlength="3"
                   value="{{ old('code', $country->code) }}" class="input uppercase font-mono">
            @error('code') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Bayrak emoji</label>
            <input type="text" name="flag_emoji" maxlength="8" value="{{ old('flag_emoji', $country->flag_emoji) }}" class="input text-2xl">
        </div>
        <div>
            <label class="label">Ad (TR) <span class="text-rose-500">*</span></label>
            <input type="text" name="name_tr" required maxlength="120" value="{{ old('name_tr', $country->name_tr) }}" class="input">
            @error('name_tr') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Ad (EN) <span class="text-rose-500">*</span></label>
            <input type="text" name="name_en" required maxlength="120" value="{{ old('name_en', $country->name_en) }}" class="input">
            @error('name_en') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Enlem (lat)</label>
            <input type="number" step="0.000001" name="lat" value="{{ old('lat', $country->lat) }}" class="input font-mono">
        </div>
        <div>
            <label class="label">Boylam (lng)</label>
            <input type="number" step="0.000001" name="lng" value="{{ old('lng', $country->lng) }}" class="input font-mono">
        </div>
    </div>
    <div>
        <label class="label">Açıklama (TR)</label>
        <textarea name="description_tr" rows="3" maxlength="2000" class="input resize-none">{{ old('description_tr', $country->description_tr) }}</textarea>
    </div>
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="hidden" name="is_active_region" value="0">
        <input type="checkbox" name="is_active_region" value="1" @checked(old('is_active_region', $country->is_active_region))
               class="h-4 w-4 rounded border-slate-300 text-brand-700">
        <span class="text-sm text-slate-700">Aktif çalışma bölgesi (haritada pulse marker olarak gösterilir)</span>
    </label>

    <button type="submit" class="btn-primary btn-md shadow-brand">
        <i data-lucide="save" class="w-4 h-4"></i>
        {{ $mode === 'create' ? 'Oluştur' : 'Kaydet' }}
    </button>
</form>

@endsection
