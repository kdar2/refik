{{-- Newsletter Bandı --}}
<section class="relative overflow-hidden bg-gradient-to-br from-brand-700 via-brand-800 to-brand-900 text-white">
    <div class="absolute inset-0 bg-grid-soft opacity-30"></div>
    <div class="container-x relative section-sm grid lg:grid-cols-2 gap-8 items-center">
        <div>
            <p class="h-eyebrow text-brand-200">İyilik Bültenimiz</p>
            <h2 class="mt-2 text-3xl lg:text-4xl font-extrabold font-display tracking-tight">
                İyilik Haberini Kaçırma
            </h2>
            <p class="mt-2 text-brand-100/90">
                Saha haberlerimiz, yeni kampanyalar ve etkimize dair güncellemeler için bültenimize katıl.
            </p>
        </div>

        <div>
            <form action="{{ route('newsletter.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <input type="email" name="email" required placeholder="E-posta adresinizi giriniz"
                       class="input !bg-white/10 !border-white/30 !text-white placeholder:text-white/60 focus:!bg-white/20 focus:!border-white"
                       value="{{ old('email') }}">
                <button class="btn-accent btn-md whitespace-nowrap">
                    ABONE OL
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
            @if (session('newsletter_success'))
                <p class="mt-3 inline-flex items-center gap-2 text-sm text-emerald-200">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    {{ session('newsletter_success') }}
                </p>
            @endif
            @error('email')
                <p class="mt-3 text-sm text-rose-200">{{ $message }}</p>
            @enderror
        </div>
    </div>
</section>
