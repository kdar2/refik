@extends('admin.layout')

@section('title', $mode === 'create' ? 'Yeni Haber' : 'Haber Düzenle')
@section('header', $mode === 'create' ? 'Yeni Haber' : $post->title_tr)

@section('header_actions')
    <a href="{{ route('admin.posts.index') }}" class="btn-ghost btn-sm">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Geri
    </a>
@endsection

@section('content')

<form method="POST" action="{{ $mode === 'create' ? route('admin.posts.store') : route('admin.posts.update', $post) }}"
      class="grid lg:grid-cols-[1fr_320px] gap-6">
    @csrf
    @if ($mode === 'edit') @method('PUT') @endif

    <div class="space-y-5">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6 space-y-4">
            <div>
                <label class="label">Başlık (TR) <span class="text-rose-500">*</span></label>
                <input type="text" name="title_tr" required maxlength="255"
                       value="{{ old('title_tr', $post->title_tr) }}" class="input">
                @error('title_tr') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Başlık (EN)</label>
                <input type="text" name="title_en" maxlength="255"
                       value="{{ old('title_en', $post->title_en) }}" class="input">
            </div>
            <div>
                <label class="label">Özet (TR)</label>
                <textarea name="excerpt_tr" rows="2" maxlength="500" class="input resize-none">{{ old('excerpt_tr', $post->excerpt_tr) }}</textarea>
            </div>
            <div>
                <label class="label">İçerik (TR) <span class="text-rose-500">*</span></label>
                <textarea name="content_tr" rows="14" required class="input resize-y font-mono text-sm">{{ old('content_tr', $post->content_tr) }}</textarea>
                <p class="help">HTML kullanabilirsiniz.</p>
                @error('content_tr') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="label">Kapak görseli URL <span class="text-rose-500">*</span></label>
                <input type="url" name="cover_image" required maxlength="500"
                       value="{{ old('cover_image', $post->cover_image) }}" class="input"
                       x-data x-on:input="$refs.preview && ($refs.preview.src = $event.target.value)">
                @error('cover_image') <p class="error">{{ $message }}</p> @enderror
                @if ($post->cover_image)
                    <img x-ref="preview" src="{{ $post->cover_image }}" alt=""
                         class="mt-3 rounded-xl w-full max-w-sm aspect-[16/9] object-cover border border-slate-200">
                @endif
            </div>
        </div>
    </div>

    <div class="space-y-5 lg:sticky lg:top-20 lg:self-start">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 space-y-3">
            <h3 class="font-bold text-brand-900">Yayın</h3>
            <label class="flex items-center justify-between gap-2 text-sm cursor-pointer">
                <span>Yayında</span>
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published))
                       class="h-4 w-4 rounded border-slate-300 text-brand-700">
            </label>
            <label class="flex items-center justify-between gap-2 text-sm cursor-pointer">
                <span>Öne çıkar (anasayfa)</span>
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))
                       class="h-4 w-4 rounded border-slate-300 text-brand-700">
            </label>
            <div>
                <label class="label">Yayın tarihi</label>
                <input type="datetime-local" name="published_at"
                       value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}" class="input !py-2">
                <p class="help">Boş bırakılırsa şimdi olarak ayarlanır.</p>
            </div>
            <div>
                <label class="label">Kategori</label>
                <select name="post_category_id" class="input !py-2">
                    <option value="">— Seç —</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" @selected(old('post_category_id', $post->post_category_id)==$cat->id)>{{ $cat->name_tr }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <button type="submit" class="btn-primary btn-md w-full justify-center shadow-brand">
            <i data-lucide="save" class="w-4 h-4"></i>
            {{ $mode === 'create' ? 'Haberi Oluştur' : 'Değişiklikleri Kaydet' }}
        </button>
    </div>
</form>

@endsection
