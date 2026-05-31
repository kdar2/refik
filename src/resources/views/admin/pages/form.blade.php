@extends('admin.layout')

@section('title', $mode === 'create' ? 'Yeni Sayfa' : $page->title_tr)
@section('header', $mode === 'create' ? 'Yeni Sayfa' : $page->title_tr)

@section('header_actions')
    <a href="{{ route('admin.pages.index') }}" class="btn-ghost btn-sm">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Geri
    </a>
@endsection

@section('content')

<form method="POST" action="{{ $mode === 'create' ? route('admin.pages.store') : route('admin.pages.update', $page) }}"
      class="grid lg:grid-cols-[1fr_280px] gap-6">
    @csrf
    @if ($mode === 'edit') @method('PUT') @endif

    <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6 space-y-4">
        <div>
            <label class="label">Başlık (TR) <span class="text-rose-500">*</span></label>
            <input type="text" name="title_tr" required maxlength="255" value="{{ old('title_tr', $page->title_tr) }}" class="input">
            @error('title_tr') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">Başlık (EN)</label>
            <input type="text" name="title_en" maxlength="255" value="{{ old('title_en', $page->title_en) }}" class="input">
        </div>
        <div>
            <label class="label">İçerik (TR) <span class="text-rose-500">*</span></label>
            <textarea name="body_tr" rows="20" required class="input resize-y font-mono text-sm">{{ old('body_tr', $page->body_tr) }}</textarea>
            <p class="help">HTML kullanabilirsiniz.</p>
            @error('body_tr') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="label">İçerik (EN)</label>
            <textarea name="body_en" rows="10" class="input resize-y font-mono text-sm">{{ old('body_en', $page->body_en) }}</textarea>
        </div>
    </div>

    <div class="space-y-5 lg:sticky lg:top-20 lg:self-start">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <h3 class="font-bold text-brand-900 mb-3">Yayın</h3>
            <label class="flex items-center justify-between gap-2 text-sm cursor-pointer">
                <span>Yayında</span>
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $page->is_published ?? true))
                       class="h-4 w-4 rounded border-slate-300 text-brand-700">
            </label>
            @if ($mode === 'edit')
                <p class="mt-3 text-xs text-slate-500">Slug: <code class="font-mono">{{ $page->slug }}</code></p>
            @endif
        </div>

        <button type="submit" class="btn-primary btn-md w-full justify-center shadow-brand">
            <i data-lucide="save" class="w-4 h-4"></i>
            {{ $mode === 'create' ? 'Oluştur' : 'Kaydet' }}
        </button>
    </div>
</form>

@endsection
