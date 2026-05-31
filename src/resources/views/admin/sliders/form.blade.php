@extends('admin.layout')

@section('title', $mode === 'create' ? 'Yeni Slayt' : 'Slayt Düzenle')
@section('header', $mode === 'create' ? 'Yeni Slayt' : $slider->title_tr)

@section('header_actions')
    <a href="{{ route('admin.sliders.index') }}" class="btn-ghost btn-sm">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Geri
    </a>
@endsection

@section('content')

<form method="POST" action="{{ $mode === 'create' ? route('admin.sliders.store') : route('admin.sliders.update', $slider) }}"
      class="grid lg:grid-cols-[1fr_320px] gap-6">
    @csrf
    @if ($mode === 'edit') @method('PUT') @endif

    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6 space-y-4">
        <div>
            <label class="label">Üst başlık (eyebrow)</label>
            <input type="text" name="eyebrow_tr" maxlength="255"
                   value="{{ old('eyebrow_tr', $slider->eyebrow_tr) }}" class="input">
        </div>
        <div>
            <label class="label">Başlık (TR) <span class="text-rose-500">*</span></label>
            <input type="text" name="title_tr" required maxlength="255"
                   value="{{ old('title_tr', $slider->title_tr) }}" class="input">
            @error('title_tr') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Alt metin</label>
            <textarea name="subtitle_tr" rows="3" maxlength="1000" class="input resize-none">{{ old('subtitle_tr', $slider->subtitle_tr) }}</textarea>
        </div>
        <div>
            <label class="label">Görsel URL <span class="text-rose-500">*</span></label>
            <input type="url" name="image" required maxlength="500"
                   value="{{ old('image', $slider->image) }}" class="input">
            @error('image') <p class="error">{{ $message }}</p> @enderror
            @if ($slider->image)
                <img src="{{ $slider->image }}" alt="" class="mt-3 rounded-xl w-full max-w-sm aspect-[16/9] object-cover border border-slate-200">
            @endif
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="label">CTA metni</label>
                <input type="text" name="cta_text_tr" maxlength="120"
                       value="{{ old('cta_text_tr', $slider->cta_text_tr) }}" class="input">
            </div>
            <div>
                <label class="label">CTA URL</label>
                <input type="text" name="cta_url" maxlength="500"
                       value="{{ old('cta_url', $slider->cta_url) }}" class="input"
                       placeholder="/calismalarimiz?category=...">
            </div>
        </div>
    </div>

    <div class="space-y-5 lg:sticky lg:top-20 lg:self-start">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 space-y-3">
            <h3 class="font-bold text-brand-900">Yayın</h3>
            <label class="flex items-center justify-between gap-2 text-sm cursor-pointer">
                <span>Aktif</span>
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $slider->is_active ?? true))
                       class="h-4 w-4 rounded border-slate-300 text-brand-700">
            </label>
            <div>
                <label class="label">Sıralama</label>
                <input type="number" name="order" required value="{{ old('order', $slider->order ?? 0) }}" class="input !py-2">
            </div>
            <div>
                <label class="label">Overlay rengi</label>
                <input type="color" name="overlay_color" required value="{{ old('overlay_color', $slider->overlay_color ?? '#0B295C') }}" class="w-full h-10 rounded-lg border border-slate-200">
            </div>
            <div>
                <label class="label">Overlay yoğunluğu (%)</label>
                <input type="number" name="overlay_opacity" required min="0" max="100"
                       value="{{ old('overlay_opacity', $slider->overlay_opacity ?? 40) }}" class="input !py-2">
            </div>
        </div>

        <button type="submit" class="btn-primary btn-md w-full justify-center">
            <i data-lucide="save" class="w-4 h-4"></i>
            {{ $mode === 'create' ? 'Oluştur' : 'Kaydet' }}
        </button>
    </div>
</form>

@endsection
