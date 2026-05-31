@extends('layouts.app')

@section('title', 'Bağış Yap')

@section('content')

<section class="section bg-surface-alt min-h-screen">
    <div class="container-x max-w-4xl">

        {{-- Üst başlık + kampanya bilgisi --}}
        <div class="text-center mb-8" data-rise>
            <p class="h-eyebrow">İyilik yolunda</p>
            <h1 class="mt-2 text-3xl lg:text-4xl font-extrabold font-display text-brand-900 tracking-tight">
                Bağış Yap
            </h1>
        </div>

        @if ($campaign)
            <div class="rounded-2xl bg-white border border-slate-100 shadow-sm p-4 lg:p-5 mb-6 flex items-center gap-4">
                <img src="{{ $campaign->cover_image }}" alt="{{ $campaign->title_tr }}"
                     class="w-16 h-16 lg:w-20 lg:h-20 rounded-xl object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-xs uppercase tracking-wider text-brand-500 font-semibold">{{ $campaign->category?->name_tr }}</p>
                    <p class="font-bold text-brand-900 truncate">{{ $campaign->title_tr }}</p>
                    <p class="text-xs text-slate-500 line-clamp-1">{{ $campaign->subtitle_tr }}</p>
                </div>
                <a href="{{ route('campaigns.show', $campaign->slug) }}" class="text-xs text-brand-500 hover:text-brand-700 underline-offset-2 hover:underline shrink-0">
                    Kampanya
                </a>
            </div>
        @endif

        {{-- Hata mesajı --}}
        @if (session('payment_error'))
            <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 mb-6 text-sm text-rose-900 flex items-start gap-3">
                <i data-lucide="alert-circle" class="w-5 h-5 shrink-0"></i>
                <span>{{ session('payment_error') }}</span>
            </div>
        @endif

        {{-- 4 adımlı form (Alpine x-data step state) --}}
        <form method="POST" action="{{ route('donate.store') }}"
              x-data="donateFlow({
                  amount:    {{ old('amount', $defaults['amount']) }},
                  type:      '{{ old('type',      $defaults['type']) }}',
                  frequency: '{{ old('frequency', $defaults['frequency']) }}',
                  paymentMethod: '{{ old('payment_method', 'credit_card') }}',
                  isCorporate: {{ old('is_corporate') ? 'true' : 'false' }},
                  hasErrors: {{ $errors->any() ? 'true' : 'false' }},
                  cartAddUrl: @js(route('cart.add')),
                  cartShowUrl: @js(route('cart.show')),
                  campaignId: {{ $campaign?->id ?? 'null' }},
                  csrf: @js(csrf_token()),
              })"
              x-init="hasErrors && jumpToFirstError()"
              class="rounded-3xl bg-white border border-slate-100 shadow-brand overflow-hidden">
            @csrf

            @if ($campaign)
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
            @endif

            {{-- Step indicator --}}
            <div class="border-b border-slate-100 px-6 lg:px-10 py-5">
                <div class="grid grid-cols-4 gap-2">
                    @foreach (['Tutar', 'Bilgiler', 'Niyet', 'Ödeme'] as $i => $label)
                        <button type="button" @click="goTo({{ $i + 1 }})"
                                :class="step >= {{ $i + 1 }} ? 'border-brand-700' : 'border-slate-200'"
                                class="flex items-center gap-2 border-t-4 pt-3 text-left">
                            <span :class="step >= {{ $i + 1 }} ? 'bg-brand-700 text-white' : 'bg-slate-200 text-slate-500'"
                                  class="grid place-items-center w-6 h-6 rounded-full text-xs font-bold transition">{{ $i + 1 }}</span>
                            <span :class="step >= {{ $i + 1 }} ? 'text-brand-900' : 'text-slate-500'"
                                  class="text-xs lg:text-sm font-semibold transition">{{ $label }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="p-6 lg:p-10">

                {{-- ─── ADIM 1: Tutar & Tür ─── --}}
                <div x-show="step === 1" x-transition>
                    <h2 class="text-xl font-extrabold font-display text-brand-900 mb-1">Tutar ve Tür</h2>
                    <p class="text-sm text-slate-500 mb-6">Ne kadar bağışlamak istersin?</p>

                    <label class="label">Tutar seç</label>
                    <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 mb-3">
                        @foreach ($presets as $val)
                            <button type="button" @click="amount = {{ $val }}"
                                    :class="amount === {{ $val }} ? 'border-brand-700 bg-brand-50 text-brand-900' : 'border-slate-200 hover:border-brand-300'"
                                    class="rounded-lg border-2 py-3 text-sm font-bold transition">
                                {{ number_format($val, 0, ',', '.') }} ₺
                            </button>
                        @endforeach
                    </div>
                    <div class="grid grid-cols-[1fr_140px] gap-2">
                        <input type="number" name="amount" x-model.number="amount" min="1" max="1000000" step="1" required
                               class="input" placeholder="Özel tutar girin">
                        <select name="currency" class="input">
                            <option value="TRY" selected>TRY ₺</option>
                            <option value="USD">USD $</option>
                            <option value="EUR">EUR €</option>
                        </select>
                    </div>
                    @error('amount') <p class="error">{{ $message }}</p> @enderror

                    <div class="grid sm:grid-cols-2 gap-4 mt-6">
                        <div>
                            <label class="label">Bağış türü</label>
                            <select name="type" x-model="type" class="input">
                                <option value="general">Genel Bağış</option>
                                <option value="zakat">Zekat</option>
                                <option value="fitre">Fitre</option>
                                <option value="sadaka">Sadaka-i Cariye</option>
                                <option value="kurban">Kurban</option>
                                <option value="adak">Adak</option>
                                <option value="kefaret">Kefaret</option>
                            </select>
                        </div>
                        <div>
                            <label class="label">Sıklık</label>
                            <select name="frequency" x-model="frequency" class="input">
                                <option value="one_time">Tek Sefer</option>
                                <option value="monthly">Aylık</option>
                                <option value="quarterly">Üç Aylık</option>
                                <option value="yearly">Yıllık</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 mt-8">
                        <button type="button" @click="addToCart()" :disabled="cartLoading"
                                class="btn-ghost btn-md disabled:opacity-50">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                            <span x-text="cartLoading ? 'Ekleniyor…' : 'Sepete Ekle'"></span>
                        </button>
                        <button type="button" @click="next()" class="btn-primary btn-md ml-auto">
                            Devam <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- ─── ADIM 2: Bağışçı Bilgileri ─── --}}
                <div x-show="step === 2" x-cloak x-transition>
                    <h2 class="text-xl font-extrabold font-display text-brand-900 mb-1">Bağışçı Bilgileri</h2>
                    <p class="text-sm text-slate-500 mb-6">İletişim ve fatura için kullanılacak.</p>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Ad Soyad <span class="text-rose-500">*</span></label>
                            <input type="text" name="donor_name" required maxlength="120"
                                   value="{{ old('donor_name') }}" class="input">
                            @error('donor_name') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label">E-posta <span class="text-rose-500">*</span></label>
                            <input type="email" name="donor_email" required value="{{ old('donor_email') }}" class="input">
                            @error('donor_email') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label">Telefon</label>
                            <input type="tel" name="donor_phone" value="{{ old('donor_phone') }}" class="input">
                        </div>
                        <div>
                            <label class="label">TC Kimlik No <span class="text-slate-400 font-normal">(vergi indirimi için)</span></label>
                            <input type="text" name="tckn" maxlength="11" pattern="\d{11}" value="{{ old('tckn') }}" class="input">
                            @error('tckn') <p class="error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Kurumsal --}}
                    <div class="mt-6 rounded-xl bg-slate-50 border border-slate-200 p-4">
                        <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-brand-900">
                            <input type="checkbox" name="is_corporate" value="1" x-model="isCorporate"
                                   class="h-4 w-4 rounded border-slate-300 text-brand-700">
                            Kurumsal bağış yapıyorum
                        </label>
                        <div x-show="isCorporate" x-cloak class="grid sm:grid-cols-3 gap-3 mt-4">
                            <div>
                                <label class="label">Şirket adı</label>
                                <input type="text" name="company_name" value="{{ old('company_name') }}" class="input">
                                @error('company_name') <p class="error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="label">Vergi dairesi</label>
                                <input type="text" name="tax_office" value="{{ old('tax_office') }}" class="input">
                                @error('tax_office') <p class="error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="label">Vergi no</label>
                                <input type="text" name="tax_no" value="{{ old('tax_no') }}" class="input">
                                @error('tax_no') <p class="error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="flex items-start gap-2 cursor-pointer text-sm">
                            <input type="checkbox" name="kvkk" value="1" required {{ old('kvkk') ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-slate-300 text-brand-700 mt-0.5">
                            <span class="text-slate-600">
                                <a href="{{ route('contact') }}" class="underline hover:text-brand-700">KVKK aydınlatma metnini</a>
                                okudum, kişisel verilerimin işlenmesine onay veriyorum.
                            </span>
                        </label>
                        @error('kvkk') <p class="error">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" @click="prev()" class="btn-ghost btn-md">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Geri
                        </button>
                        <button type="button" @click="next()" class="btn-primary btn-md">
                            Devam <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- ─── ADIM 3: Niyet & Mesaj ─── --}}
                <div x-show="step === 3" x-cloak x-transition>
                    <h2 class="text-xl font-extrabold font-display text-brand-900 mb-1">Niyet & Mesaj</h2>
                    <p class="text-sm text-slate-500 mb-6">İsteğe bağlı — niyetinize ve teşekkür mesajına alan.</p>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label">Niyet</label>
                            <select name="intention" class="input">
                                <option value="">Seçim yapmadım</option>
                                @foreach ($intentions as $i)
                                    <option value="{{ $i->label_tr }}" @selected(old('intention')===$i->label_tr)>{{ $i->label_tr }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="label">Yakını için (isim)</label>
                            <input type="text" name="intention_for" value="{{ old('intention_for') }}" class="input"
                                   placeholder="Örn. Mehmet Yılmaz">
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="label">Mesajınız <span class="text-slate-400 font-normal">(en fazla 1000 karakter)</span></label>
                        <textarea name="message" rows="3" maxlength="1000" class="input resize-none"
                                  placeholder="İyilik yolculuğunuza eşlik edecek bir not bırakın…">{{ old('message') }}</textarea>
                    </div>

                    <label class="flex items-center gap-2 mt-5 cursor-pointer text-sm">
                        <input type="checkbox" name="certificate_requested" value="1" {{ old('certificate_requested') ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-slate-300 text-brand-700">
                        <span>Bağış sertifikası ve teşekkür e-postası istiyorum.</span>
                    </label>

                    <div class="flex justify-between mt-8">
                        <button type="button" @click="prev()" class="btn-ghost btn-md">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Geri
                        </button>
                        <button type="button" @click="next()" class="btn-primary btn-md">
                            Devam <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- ─── ADIM 4: Ödeme ─── --}}
                <div x-show="step === 4" x-cloak x-transition>
                    <h2 class="text-xl font-extrabold font-display text-brand-900 mb-1">Ödeme</h2>
                    <p class="text-sm text-slate-500 mb-6">Tüm bağış işlemleri 256-bit SSL ile şifrelenir.</p>

                    <div class="grid sm:grid-cols-2 gap-3 mb-6">
                        <button type="button" @click="paymentMethod = 'credit_card'"
                                :class="paymentMethod === 'credit_card' ? 'border-brand-700 bg-brand-50 text-brand-900' : 'border-slate-200'"
                                class="rounded-xl border-2 p-4 text-left transition">
                            <i data-lucide="credit-card" class="w-5 h-5 mb-2"></i>
                            <p class="font-bold">Kredi / Banka Kartı</p>
                            <p class="text-xs text-slate-500 mt-0.5">3D Secure ile doğrulamalı ödeme</p>
                        </button>
                        <button type="button" @click="paymentMethod = 'bank_transfer'"
                                :class="paymentMethod === 'bank_transfer' ? 'border-brand-700 bg-brand-50 text-brand-900' : 'border-slate-200'"
                                class="rounded-xl border-2 p-4 text-left transition">
                            <i data-lucide="landmark" class="w-5 h-5 mb-2"></i>
                            <p class="font-bold">Havale / EFT</p>
                            <p class="text-xs text-slate-500 mt-0.5">Banka hesap bilgileri size mail ile iletilir</p>
                        </button>
                    </div>
                    <input type="hidden" name="payment_method" :value="paymentMethod">

                    {{-- Kart formu --}}
                    <div x-show="paymentMethod === 'credit_card'" x-cloak class="space-y-4">
                        <div>
                            <label class="label">Kart üzerindeki isim</label>
                            <input type="text" name="card_holder" :required="paymentMethod === 'credit_card'"
                                   value="{{ old('card_holder') }}" class="input">
                            @error('card_holder') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="label">Kart numarası</label>
                            <input type="text" name="card_number" inputmode="numeric" autocomplete="cc-number"
                                   :required="paymentMethod === 'credit_card'"
                                   maxlength="19" placeholder="0000 0000 0000 0000" class="input font-mono">
                            @error('card_number') <p class="error">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="label">Son kullanma (AA/YY)</label>
                                <input type="text" name="card_expiry" placeholder="06/30" maxlength="7"
                                       :required="paymentMethod === 'credit_card'" class="input font-mono">
                                @error('card_expiry') <p class="error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="label">CVV</label>
                                <input type="text" name="card_cvv" inputmode="numeric" maxlength="4"
                                       :required="paymentMethod === 'credit_card'" class="input font-mono">
                                @error('card_cvv') <p class="error">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        <p class="text-[11px] text-slate-500 flex items-center gap-1.5">
                            <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                            Kart bilgileriniz tarafımızda saklanmaz; ödeme sağlayıcının güvenli sayfasında işlenir.
                        </p>
                    </div>

                    <div x-show="paymentMethod === 'bank_transfer'" x-cloak
                         class="rounded-xl bg-amber-50 border border-amber-200 p-4 text-sm text-amber-900">
                        <p class="font-semibold mb-2 flex items-center gap-2">
                            <i data-lucide="info" class="w-4 h-4"></i> Havale ile bağış
                        </p>
                        <p>
                            Bağış kaydınız oluşturulduktan sonra banka hesap bilgilerimiz e-posta ile size iletilir.
                            Açıklamaya bağış referans numaranızı yazınız.
                        </p>
                    </div>

                    {{-- Özet --}}
                    <div class="rounded-2xl bg-brand-50 border border-brand-100 p-5 mt-6">
                        <p class="text-xs uppercase tracking-wider text-brand-500 font-semibold mb-3">Bağış Özeti</p>
                        <dl class="space-y-1.5 text-sm">
                            <div class="flex justify-between"><dt class="text-slate-600">Tutar</dt>
                                <dd class="font-bold text-brand-900"><span x-text="formatTRY(amount)"></span></dd></div>
                            <div class="flex justify-between"><dt class="text-slate-600">Tür</dt>
                                <dd class="font-bold text-brand-900" x-text="typeLabels[type]"></dd></div>
                            <div class="flex justify-between"><dt class="text-slate-600">Sıklık</dt>
                                <dd class="font-bold text-brand-900" x-text="frequencyLabels[frequency]"></dd></div>
                        </dl>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" @click="prev()" class="btn-ghost btn-md">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Geri
                        </button>
                        <button type="submit" class="btn-accent btn-lg shadow-brand">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                            Bağışı Tamamla
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</section>

@push('scripts')
<script>
function donateFlow(initial) {
    return {
        step: 1,
        amount:        initial.amount    || 100,
        type:          initial.type      || 'general',
        frequency:     initial.frequency || 'one_time',
        paymentMethod: initial.paymentMethod || 'credit_card',
        isCorporate:   initial.isCorporate,
        hasErrors:     initial.hasErrors,

        cartAddUrl:    initial.cartAddUrl,
        cartShowUrl:   initial.cartShowUrl,
        campaignId:    initial.campaignId,
        csrf:          initial.csrf,
        cartLoading:   false,

        typeLabels: {
            general: 'Genel Bağış', zakat: 'Zekat', fitre: 'Fitre',
            sadaka: 'Sadaka-i Cariye', kurban: 'Kurban', adak: 'Adak', kefaret: 'Kefaret',
        },
        frequencyLabels: {
            one_time: 'Tek Sefer', monthly: 'Aylık', quarterly: 'Üç Aylık', yearly: 'Yıllık',
        },

        next() {
            if (this.step < 4) this.step++;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        prev() {
            if (this.step > 1) this.step--;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        goTo(n) {
            this.step = n;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        },

        // Validation hatası varsa son adıma çık (kullanıcı düzeltebilsin)
        jumpToFirstError() { this.step = 4; },

        formatTRY(v) {
            return new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY', minimumFractionDigits: 0 })
                .format(v || 0);
        },

        async addToCart() {
            if (this.cartLoading || !this.amount || this.amount < 1) return;
            this.cartLoading = true;
            try {
                const res = await fetch(this.cartAddUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        amount: this.amount,
                        type: this.type,
                        frequency: this.frequency,
                        currency: 'TRY',
                        campaign_id: this.campaignId,
                    }),
                });
                if (res.ok) {
                    window.location.href = this.cartShowUrl;
                } else {
                    this.cartLoading = false;
                }
            } catch (_) {
                this.cartLoading = false;
            }
        },
    };
}
</script>
@endpush

@endsection
