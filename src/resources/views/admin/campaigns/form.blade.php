@extends('admin.layout')

@section('title', $mode === 'create' ? 'Yeni Kampanya' : 'Kampanya Düzenle')
@section('header', $mode === 'create' ? 'Yeni Kampanya' : $campaign->title_tr)

@section('header_actions')
    <a href="{{ route('admin.campaigns.index') }}" class="btn-ghost btn-sm">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Geri
    </a>
@endsection

@section('content')

<form method="POST" action="{{ $mode === 'create' ? route('admin.campaigns.store') : route('admin.campaigns.update', $campaign) }}"
      class="grid lg:grid-cols-[1fr_320px] gap-6">
    @csrf
    @if ($mode === 'edit') @method('PUT') @endif

    {{-- Sol kolon: ana içerik --}}
    <div class="space-y-5">
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6">
            <h2 class="font-bold text-brand-900 mb-4">İçerik</h2>

            <div class="space-y-4">
                <div>
                    <label class="label">Başlık (TR) <span class="text-rose-500">*</span></label>
                    <input type="text" name="title_tr" required maxlength="255"
                           value="{{ old('title_tr', $campaign->title_tr) }}" class="input">
                    @error('title_tr') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Başlık (EN)</label>
                    <input type="text" name="title_en" maxlength="255"
                           value="{{ old('title_en', $campaign->title_en) }}" class="input">
                </div>
                <div>
                    <label class="label">Alt başlık (TR)</label>
                    <textarea name="subtitle_tr" rows="2" maxlength="500" class="input resize-none">{{ old('subtitle_tr', $campaign->subtitle_tr) }}</textarea>
                </div>
                <div>
                    <label class="label">Açıklama (TR) <span class="text-rose-500">*</span></label>
                    <textarea name="description_tr" rows="10" required class="input resize-y font-mono text-sm">{{ old('description_tr', $campaign->description_tr) }}</textarea>
                    <p class="help">HTML kullanabilirsiniz. (&lt;p&gt;, &lt;ul&gt;, &lt;strong&gt;)</p>
                    @error('description_tr') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6">
            <h2 class="font-bold text-brand-900 mb-4">Görsel & Medya</h2>

            <div class="space-y-4">
                <div>
                    <label class="label">Kapak görseli URL <span class="text-rose-500">*</span></label>
                    <input type="url" name="cover_image" required maxlength="500"
                           value="{{ old('cover_image', $campaign->cover_image) }}" class="input"
                           x-data x-on:input="$refs.preview.src = $event.target.value">
                    <p class="help">Hızlı eklemek için <code>https://picsum.photos/seed/...</code> kullanılabilir.</p>
                    @error('cover_image') <p class="error">{{ $message }}</p> @enderror

                    @if ($campaign->cover_image)
                        <img x-ref="preview" src="{{ $campaign->cover_image }}" alt=""
                             class="mt-3 rounded-xl w-full max-w-sm aspect-[16/9] object-cover border border-slate-200">
                    @endif
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 lg:p-6">
            <h2 class="font-bold text-brand-900 mb-4">Bağış uygunluğu</h2>
            <div class="grid sm:grid-cols-2 gap-3 text-sm">
                @foreach ([
                    'zakat_eligible'  => 'Zekat için uygundur',
                    'sadaka_eligible' => 'Sadaka-i Cariye için uygundur',
                    'fitre_eligible'  => 'Fitre için uygundur',
                    'kurban_eligible' => 'Kurban için uygundur',
                ] as $field => $label)
                    <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2.5 cursor-pointer hover:bg-slate-50">
                        <input type="hidden" name="{{ $field }}" value="0">
                        <input type="checkbox" name="{{ $field }}" value="1"
                               @checked(old($field, $campaign->{$field}))
                               class="h-4 w-4 rounded border-slate-300 text-brand-700">
                        <span>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Sağ kolon: meta --}}
    <div class="space-y-5 lg:sticky lg:top-20 lg:self-start">

        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <h3 class="font-bold text-brand-900 mb-3">Yayın</h3>
            <div class="space-y-3">
                <label class="flex items-center justify-between gap-2 text-sm cursor-pointer">
                    <span>Aktif</span>
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $campaign->is_active ?? true))
                           class="h-4 w-4 rounded border-slate-300 text-brand-700">
                </label>
                <label class="flex items-center justify-between gap-2 text-sm cursor-pointer">
                    <span>Öne çıkar (anasayfa)</span>
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $campaign->is_featured))
                           class="h-4 w-4 rounded border-slate-300 text-brand-700">
                </label>
                <label class="flex items-center justify-between gap-2 text-sm cursor-pointer">
                    <span>Acil bayrağı</span>
                    <input type="hidden" name="is_emergency" value="0">
                    <input type="checkbox" name="is_emergency" value="1" @checked(old('is_emergency', $campaign->is_emergency))
                           class="h-4 w-4 rounded border-slate-300 text-brand-700">
                </label>
                <div>
                    <label class="label">Sıralama</label>
                    <input type="number" name="order" value="{{ old('order', $campaign->order ?? 0) }}" class="input !py-2">
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <h3 class="font-bold text-brand-900 mb-3">Sınıflandırma</h3>
            <div class="space-y-3">
                <div>
                    <label class="label">Kategori <span class="text-rose-500">*</span></label>
                    <select name="category_id" required class="input !py-2">
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected(old('category_id', $campaign->category_id)==$cat->id)>{{ $cat->name_tr }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="label">Ülke</label>
                    <select name="country_id" class="input !py-2">
                        <option value="">— Seç —</option>
                        @foreach ($countries as $cnt)
                            <option value="{{ $cnt->id }}" @selected(old('country_id', $campaign->country_id)==$cnt->id)>
                                {{ $cnt->flag_emoji }} {{ $cnt->name_tr }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <h3 class="font-bold text-brand-900 mb-3">Tutarlar</h3>
            <div class="space-y-3">
                <div>
                    <label class="label">Niyet (hedef)</label>
                    <input type="number" min="0" step="0.01" name="goal_amount"
                           value="{{ old('goal_amount', $campaign->goal_amount) }}" class="input !py-2">
                </div>
                <div>
                    <label class="label">Toplanan</label>
                    <input type="number" min="0" step="0.01" name="raised_amount"
                           value="{{ old('raised_amount', $campaign->raised_amount ?? 0) }}" class="input !py-2">
                    <p class="help">Otomatik bağış akışı bu değeri kendiliğinden artırır.</p>
                </div>
                <div>
                    <label class="label">Para birimi</label>
                    <select name="currency" class="input !py-2">
                        @foreach (['TRY','USD','EUR'] as $c)
                            <option value="{{ $c }}" @selected(old('currency', $campaign->currency)==$c)>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="label">Bağışçı sayısı</label>
                    <input type="number" min="0" name="donor_count"
                           value="{{ old('donor_count', $campaign->donor_count ?? 0) }}" class="input !py-2">
                </div>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5">
            <h3 class="font-bold text-brand-900 mb-3">Tarihler</h3>
            <div class="space-y-3">
                <div>
                    <label class="label">Başlangıç</label>
                    <input type="date" name="start_date"
                           value="{{ old('start_date', optional($campaign->start_date)->format('Y-m-d')) }}" class="input !py-2">
                </div>
                <div>
                    <label class="label">Bitiş</label>
                    <input type="date" name="end_date"
                           value="{{ old('end_date', optional($campaign->end_date)->format('Y-m-d')) }}" class="input !py-2">
                    @error('end_date') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn-primary btn-md w-full justify-center shadow-brand">
            <i data-lucide="save" class="w-4 h-4"></i>
            {{ $mode === 'create' ? 'Kampanyayı Oluştur' : 'Değişiklikleri Kaydet' }}
        </button>
    </div>
</form>

@endsection
