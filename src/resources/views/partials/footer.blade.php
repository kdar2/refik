{{-- Footer --}}
<footer class="bg-brand-900 text-brand-100">
    <div class="container-x grid lg:grid-cols-12 gap-10 py-16">

        {{-- Marka --}}
        <div class="lg:col-span-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset(config('site.logo')) }}" alt="{{ config('site.name') }}"
                     class="h-12 w-auto object-contain">
                <div>
                    <div class="text-2xl font-extrabold font-display text-white">REFİK</div>
                    <div class="text-[0.7rem] tracking-[0.25em] uppercase text-brand-300">Derneği</div>
                </div>
            </div>
            <p class="mt-5 text-sm leading-relaxed text-brand-200">
                Hayra Yoldaş ol — destekçilerimizin bağışlarıyla mağdur ve mazlumlara umut oluyor,
                "yalnız değilsiniz" diyebilmek için 120'den fazla bölgede çalışıyoruz.
            </p>

            {{-- Sosyal --}}
            <div class="mt-6 flex items-center gap-2">
                @foreach (['instagram'=>'#','twitter'=>'#','facebook'=>'#','youtube'=>'#','linkedin'=>'#'] as $icon => $url)
                    <a href="{{ $url }}" class="grid place-items-center w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white transition" aria-label="{{ $icon }}">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4"></i>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Site haritası --}}
        <div class="lg:col-span-2">
            <h4 class="text-white font-semibold mb-4">Keşfet</h4>
            <ul class="space-y-2.5 text-sm">
                <li><a href="{{ route('campaigns.index') }}" class="hover:text-white transition">Çalışmalarımız</a></li>
                <li><a href="{{ route('countries.index') }}" class="hover:text-white transition">Nerede Çalışıyoruz</a></li>
                <li><a href="{{ route('impact') }}" class="hover:text-white transition">Etki & Güvence</a></li>
                <li><a href="{{ route('about') }}" class="hover:text-white transition">Hakkımızda</a></li>
                <li><a href="{{ route('donate.show') }}" class="hover:text-white transition">Bağış Yap</a></li>
            </ul>
        </div>

        {{-- Destek --}}
        <div class="lg:col-span-3">
            <h4 class="text-white font-semibold mb-4">Destek</h4>
            <ul class="space-y-2.5 text-sm">
                <li><a href="https://wa.me/{{ ltrim(config('site.contact.whatsapp'), '+') }}" target="_blank" rel="noopener" class="hover:text-white transition flex items-center gap-2"><i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp Destek</a></li>
                <li><a href="{{ route('contact') }}" class="hover:text-white transition">İletişim Formu</a></li>
                <li><a href="{{ route('careers.show') }}" class="hover:text-white transition">İnsan Kaynakları</a></li>
                <li><a href="{{ route('volunteer.show') }}" class="hover:text-white transition">Gönüllü Ol</a></li>
                <li><a href="{{ route('help-request.show') }}" class="hover:text-white transition">Yardım Talebi</a></li>
            </ul>
        </div>

        {{-- Verimlilik --}}
        <div class="lg:col-span-3">
            <h4 class="text-white font-semibold mb-4">Verimliliğimiz</h4>
            <div class="space-y-2 text-sm">
                <div class="flex items-center justify-between"><span>%80 Programlar ve Hizmetler</span></div>
                <div class="flex items-center justify-between"><span>%12 Bağış Toplama</span></div>
                <div class="flex items-center justify-between"><span>%8 Yönetim</span></div>
            </div>
            <div class="mt-5 rounded-xl bg-white/5 p-4 border border-white/10">
                <div class="flex items-start gap-3">
                    <i data-lucide="map-pin" class="w-5 h-5 text-brand-300 shrink-0 mt-0.5"></i>
                    <p class="text-xs leading-relaxed">Dumlupınar Blv. No:274/6-65 Çankaya/Ankara</p>
                </div>
                <div class="flex items-center gap-3 mt-3">
                    <i data-lucide="phone" class="w-5 h-5 text-brand-300"></i>
                    <a href="tel:+905015673333" class="text-sm font-semibold text-white">+90 501 567 33 33</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Alt bant --}}
    <div class="border-t border-white/10">
        <div class="container-x py-5 grid md:grid-cols-3 gap-3 text-xs text-brand-300">
            <div>Yardım Toplama İzni: <span class="text-brand-100 font-semibold">12.09.2025 — 475213</span></div>
            <div>Sicil No: <span class="text-brand-100 font-semibold">06-157-152</span></div>
            <div>IBAN: <span class="text-brand-100 font-semibold">TR44 0020 9000 0208 1561 0000 10</span></div>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="container-x py-5 flex flex-col md:flex-row gap-4 items-center justify-between text-xs text-brand-300">
            <p>© {{ date('Y') }} Refik Eğitim, Kültür ve Yardımlaşma Derneği — Tüm hakları saklıdır.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-white transition">Gizlilik Politikası</a>
                <a href="#" class="hover:text-white transition">Çerez Politikası</a>
                <a href="#" class="hover:text-white transition">İhlal Bildir</a>
            </div>
        </div>
    </div>
</footer>
