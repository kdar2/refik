# Sayfa & Bölüm Şartnamesi (SPEC)

Bu döküman, **public site** için gerekli tüm sayfaları ve her sayfadaki bölümleri detaylı açıklar. Claude Code, bir sayfayı geliştirirken **önce bu dökümanın ilgili bölümünü okumalıdır**.

---

## A. Global Bileşenler (her sayfada görünür)

### A1. Acil Duyuru Çubuğu (Top Alert Bar)
- Tam genişlik, sarı zemin (`bg-amber-300`).
- Sol tarafta `ACİL` etiketi (siyah pill), yanında kısa metin: "Gazze'de yardıma ihtiyacı olan kardeşlerimize destek ol!"
- Sağ tarafta küçük `×` (kapatma) butonu — kapatma localStorage'a kaydedilir, 24 saat görünmez.
- Admin'den açılıp kapatılabilir (DB: `settings.alert_enabled`, `alert_text`, `alert_link`).

### A2. Üst Bilgi Çubuğu (Topbar — Header üstü ince çizgi)
İçerik 5 kolon (responsive olarak gizlenir):
1. Logo (sol)
2. **Hicri tarih** — JS ile `Intl.DateTimeFormat('tr-TR-u-ca-islamic')` kullan
3. **Sıradaki namaz vakti** — varsayılan İstanbul; konum izniyle güncellenir (Aladhan API placeholder)
4. **Son bağış canlı yayını** — son 5 bağışı 4 sn'de bir değiştir (anonim isim + tutar + kampanya)
5. Sağda: Giriş Yap / Dil seçici (TR/EN) / Para birimi (TRY/USD/EUR) / Bağış sepeti

### A3. Ana Menü (Header)
- Sol: Logo (büyük)
- Orta: Çalışmalarımız • Nerede Çalışıyoruz • Etki & Güvence • Hakkımızda
  - "Çalışmalarımız" hover'da mega menü: Eğitim / Sağlık / Barınma / Gıda / Kurban / Su Kuyusu / Yetim
- Sağ: Arama ikonu (büyüteç) — açılınca slide-down arama paneli
- Sağ üstte 3 CTA butonu (renk farklı):
  - **Kurban 2026** (champagne altın `#C09740`)
  - **Zekat Ver** (royal indigo `#0B295C`)
  - **Bağış Yap** (wine crimson `#D52B52`)
- Mobilde: hamburger menü, bottom-sheet animasyonu.
- **Sticky** scroll'da üst kısım arka plana yumuşak geçiş yapar (blur backdrop).

### A4. Sticky Hızlı Bağış Çubuğu (Sayfa altı sabit)
Tüm public sayfalarda görünür, mobilde kompakt.
4 dropdown + tutar + sepet + buton:
1. **Kategori**: Gazze Acil / Gıda / Yetim / Eğitim / Su Kuyusu / Sağlık
2. **Ödeme tipi**: Tek Sefer / Düzenli (Aylık)
3. **Tutar**: 50 / 100 / 200 / 500 / 1000 / Özel
4. **Tür**: Zekat / Fitre / Sadaka / Genel
- Sepet: anlık toplam (`session('cart_total')`)
- "Hızlı Bağış Yap" → `/donate?campaign=…&amount=…&type=…&recurring=…`

### A5. Footer
- Sol blok: Logo + kısa misyon metni + sosyal medya ikonları (IG, X, FB, YT, LinkedIn)
- Orta blok 1: Site haritası — Çalışmalarımız / Nerede Çalışıyoruz / Etki & Güvence / Hakkımızda / Bağış
- Orta blok 2: Destek — WhatsApp Hattı / İletişim Bilgileri / İletişim Formu / İnsan Kaynakları / Sosyal Ağlar
- Sağ blok: **Verimliliğimiz** donut grafiği (Chart.js): %80 Programlar / %12 Bağış Toplama / %8 Yönetim
- Newsletter: tam genişlik bant — "İyilik Haberini Kaçırma" + e-posta input + ABONE OL
- Alt bant: Adres / Telefon / IBAN / Sicil No / Yardım Toplama İzni / Çerez & Gizlilik linkleri
- En alt: Visa, Mastercard, Troy logoları + "İhlal Bildir" butonu

### A6. Erişilebilirlik Widget (Sağ kenar, sabit)
- Tekerlekli sandalye ikonu, tıklanınca açılan kontrol paneli:
  - Yüksek kontrast modu, font büyütme (A−/A+), bağlantıları altı çizili göster, animasyonları durdur, koyu mod.
- Tercihler `localStorage`'da saklanır.

---

## B. Anasayfa Bölümleri (`/`)

### B1. Hero Slider
- 5 slayt, 7 sn aralıkla otomatik geçiş, sol/sağ ok + alt nokta navigasyonu.
- Her slayt: arka plan görseli (gradient overlay) + üst başlık + büyük başlık + alt metin + tek CTA buton.
- Örnek slaytlar:
  1. **İlim Yolcusuna Destek** — "Senin Desteğinle Okusun / Sadakan Sonsuz Olsun" → CTA: İlme Yoldaş Ol
  2. **Kurban 2026** — "Bu Bayram Yalnız Olmasınlar" → CTA: Kurban Bağışı Yap
  3. **Gazze Acil** — "Bir Lokma da Senden" → CTA: Hemen Destek Ol
  4. **Su Kuyusu** — "Suyun Aktığı Yerde Hayat Var" → CTA: Su Kuyusu Aç
  5. **Yetim Hatırı** — "Bir Yetimin Yüzü Senin Bahtın Olsun" → CTA: Yetim Sponsoru Ol
- **Sayaç**: alt sol köşede `01 ─────` ilerleme çubuğu.

### B2. Güven & Akreditasyon Bandı
- 3 büyük rozet ikonu + sağda paragraf metin.
- Rozetler: İlmi Kurul Onaylı / Bağımsız Denetim / %100 Zekat Politikası
- Yumuşak `bg-slate-50` arka plan.

### B3. Öne Çıkan Çağrılar (Featured Campaigns)
- Üst: Başlık ("Öne Çıkan Çağrılar") + alt başlık + filtre dropdown ("Tümünü Göster") + "Daha Fazla" link
- Grid: 4 kart (lg: 4 kolon, md: 2, sm: 1) + sağa kayan slider (kart sayısı > 4 ise)
- **Kart yapısı:**
  - Üst: Görsel (16:9), sağ üstte 1–2 rozet (Z = Zekat, SC = Sadaka-i Cariye, F = Fitre)
  - Görsel altına yapışık ilerleme paneli: `Niyet: 18.000₺` / sağ üstte `%39` / yeşilimsi progress bar / `Toplanan: 7.000₺`
  - Başlık (lg, semibold)
  - 2 satır açıklama (line-clamp-2)
  - "Bağış Yap" buton (kırmızı, full-width)
  - Alt mikro etiketler: "Zekat için uygundur" / "Sadaka-i Cariye için uygundur"
- Hover: kart hafifçe yukarı kayar + gölge

### B4. Etki İstatistikleri (Counter Block)
- Royal indigo (`#0B295C`) zeminli geniş şerit
- 3 kolon, her birinde:
  - Üstte beyaz pill içinde büyük rakam: `19,500+ kişi`
  - Altında kalın beyaz başlık: `Gazze Sıcak Yemek Dağıtımı`
  - Altında ince açıklama paragrafı.
- **Sayılar görünür alana girince animasyonla artsın** (count-up).

### B5. Hayra Yoldaş — Kurumsal Tanıtım
- 2 kolon: solda metin, sağda büyük illüstrasyon (kalp + paket).
- Başlık: "Hayra Yoldaş"
- 2 paragraf metin + "Daha Fazla" buton (lacivert outline)
- Altta **7 ikon hızlı erişim**: Online Bağış / Düzenli Bağış / Gönüllümüz Olun / Yardım Talebi / Banka Hesapları / Temsilciklerimiz / İş Başvurusu
  - Mobilde yatay scroll snap.

### B6. Zekatınız Hayatları Değiştiriyor
- Başlık (lg) + 2 büyük kart yan yana:
  - **Zekat Rehberi** — illüstrasyon + kısa metin + "Zekat Ver" buton
  - **İlim Yolcusuna Zekat** — illüstrasyon + kısa metin + "Zekat Ver" buton
- Kartlar zarif gölge + hover animasyon.

### B7. Zekat Hesaplayıcı + Online Görüşme (yan yana)

**Zekat Hesaplayıcı (sol):**
- Açılışta gizlilik popup'ı (modal): "Bu modülde girdiğiniz veriler sunucuya gönderilmez, sadece tarayıcınızda hesaplama için kullanılır."
- "Okudum, Hesaplamaya Başla" tıklanınca form açılır.
- Form alanları (sayısal):
  - Nakit (TL/USD/EUR — currency selector)
  - Altın (gr)
  - Gümüş (gr)
  - Hisse senedi
  - Tahvil/Bono
  - Alacaklar
  - **Eksiltmeler**: Borçlar, vergiler
  - Nisap eşiği (otomatik dolduruluyor — admin'den güncellenir)
- "Zekatı Hesapla" → sonuç kartı: nisaba uygun mu, ne kadar zekat düşer, "Zekatımı Şimdi Ver" CTA.
- "Temizle" butonu form sıfırlar.
- **Tüm hesap client-side JS, hiçbir veri sunucuya gönderilmez.**

**Online Görüşme (sağ):**
- Mini takvim (ay görünümü, prev/next ok)
- Müsait günler vurgulanır, hafta sonu pasif.
- Gün seçince sağda saat slotları açılır (10:00-12:00, 14:00-17:00).
- "Randevu Al" → modal: ad/soyad/email/telefon + konu seçimi (Bağış Danışmanlığı / Zekat Danışmanlığı / Vasiyet / Genel)
- POST `/appointments` → DB kaydı + admin'e e-posta.

### B8. SMS Bağış
- Lacivert zeminli geniş kart, 3 kolon:
  - **Bağış**: 2516 → 50 TL
  - **Zekat**: 7705 → 240 TL
  - **İlim**: 7701 → 100 TL
- Her birinin yanında QR kod görseli (admin'den yüklenebilir).

### B9. Bağışlar Hangi Alanlarda Kullanılıyor? (Kategori Slider)
- Yatay scroll snap kart slider (Embla.js veya saf CSS scroll-snap).
- 6+ kart: Gıda Yardımları, Orman Bağışı, Giyim, Barınma, Sağlık, Eğitim, Su, Yetim
- Her kart: arkada görsel, alt overlay (gradient siyah → şeffaf), beyaz başlık + kısa metin + "Daha Fazla" link.
- Görsel `aspect-[4/5]`.

### B10. Nerede Çalışıyoruz? (İnteraktif Dünya Haritası)
- Sol blokta başlık + "120'den fazla ülke ve bölge" + Daha Fazla buton.
- Sağda **SVG dünya haritası** (basit grayscale path), aktif ülkeler kırmızı pulse marker.
- Bir markere hover: tooltip ülke adı, click: yan tarafta ülke detay kartı slide-in (görsel + paragraf + "Bu Bölgede Bağış Yap" butonu).
- Kartların prev/next slider ile gezinilebilir.

### B11. Medya ve Duyurular (Haberler)
- Solda **büyük öne çıkan haber** (image + başlık + tarih + paragraf + "Daha Fazla")
- Solda altında 6'lı küçük thumbnail strip (klik ile featured haberi değiştir).
- Sağda dikey 3-4 küçük haber kartı (resim + başlık + tarih).
- "Tüm Haberler" linki sağ üstte.

### B12. Newsletter Bandı
- Lacivert/koyu degrade arka plan.
- Sol: "İyilik Haberini Kaçırma" başlık + 1 cümle açıklama.
- Sağ: e-posta input + "ABONE OL" buton.
- KVKK onay checkbox altta.

---

## C. Kampanyalar Sayfası (`/calismalarimiz` ve `/calismalarimiz/{slug}`)

### C1. Liste Sayfası
- Hero (kısa, görsel + başlık)
- Filtre paneli (sol kolon, lg+ ekranlarda):
  - Kategori (Eğitim/Sağlık/Gıda/...)
  - Bölge (Yurtiçi/Yurtdışı)
  - Bağış uygunluğu (Zekat/Sadaka/Fitre/Kurban)
  - Durum (Aktif/Tamamlandı)
- Sağ ana alan: kart grid (B3'teki kart yapısı), sayfalama veya "Daha Fazla Yükle" buton.
- Sıralama dropdown: En yeni / En çok bağış toplayan / Bitime az kalan.

### C2. Kampanya Detay Sayfası
- Geniş hero görsel + başlık + meta (kategori, bölge, etiketler)
- Sol: detaylı açıklama (rich text, tab yapısı: Hikaye / SSS / Şeffaflık Raporu)
- Sağ sticky bağış kartı:
  - Toplanan/Niyet + progress bar
  - Bağışçı sayısı
  - Tutar seçenekleri (preset chip'ler) + "Diğer" özel input
  - Tek/Düzenli toggle
  - Bağış türü (Zekat/Sadaka)
  - Niyetinizi yazın (opsiyonel textarea, max 200 char)
  - "Bağış Yap" buton → /donate?...
- Altta: Galeri (lightbox), saha videoları (YouTube embed), benzer kampanyalar.

---

## D. Bağış Akışı (`/donate` — multi-step)

### D1. Adım 1 — Tutar & Tür
- Seçili kampanya/kategori bilgi kartı en üstte
- Tutar seçenekleri (50/100/250/500/1000 TL) + Diğer input
- Para birimi: TRY/USD/EUR
- Düzenli aktifse: Aylık, üç aylık, yıllık seçim + bitiş tarihi.

### D2. Adım 2 — Bağışçı Bilgileri
- Misafir / Üye seçeneği.
- Form: Ad, Soyad, E-posta, Telefon (opsiyonel), TC kimlik (vergi indirimi için, opsiyonel).
- KVKK + ticari ileti checkboxları.
- Kurumsal bağış toggle: Şirket adı, Vergi dairesi/no.

### D3. Adım 3 — Niyet & Mesaj
- Niyet seç (kendi adıma / rahmetli yakınım / kefaret / adak / şükür).
- Yakını için bağışsa: ad-soyad input.
- Sertifika/teşekkür e-postası iste? Toggle.

### D4. Adım 4 — Ödeme
- 3DSecure kart formu — **iyzico/PayTR** entegrasyonu (placeholder: `App\Services\Payment\PaymentGatewayInterface`).
- Havale/EFT alternatifi (banka hesap kartları).
- 3 taksit desteği (TL ≥ 250).
- Kripto, Apple Pay, Google Pay (Faz 3+).

### D5. Teşekkür Sayfası
- Büyük tebrik animasyonu (lottie veya CSS).
- Bağış özeti (referans no, tutar, kampanya).
- Sertifika PDF indir butonu.
- Sosyal paylaş butonları (X, FB, WhatsApp).
- "Düzenli bağışa çevir" / "Başka kampanyaya bağış" CTA.

---

## E. Hakkımızda (`/hakkimizda`)
- Hero + misyon/vizyon + tarihçe timeline + kurucu/yönetim ekibi grid + sayılarla biz + medya & ödüller + iletişim CTA.

## F. Etki & Güvence (`/etki-ve-guvence`)
- Yıllık faaliyet raporları (PDF indir kartları), denetim raporları, mali şeffaflık (yıllık gelir-gider grafikleri Chart.js), sıkça sorulanlar accordion.

## G. Nerede Çalışıyoruz (`/nerelerde-calisiyoruz`)
- Tam ekran interaktif harita + ülke listesi (alfabetik) + ülke detay sayfaları (`/nerelerde-calisiyoruz/sudan` vs.).

## H. Medya/Haberler (`/haberler` ve `/haberler/{slug}`)
- Liste: kategori filtreleri (Saha Haberleri / Duyurular / Basında Biz) + arama + tarih filtresi.
- Detay: galerili görsel başı + içerik + paylaş + ilgili haberler.

## I. İletişim (`/iletisim`)
- Sol: form (Ad, E-posta, Konu, Mesaj) + KVKK
- Sağ: harita (Google Maps embed), adres, telefon, e-posta, çalışma saatleri.

## J. Statik Yardımcı Sayfalar
- `/gizlilik-politikasi`, `/cerez-politikasi`, `/kvkk-aydinlatma`, `/yardim-talebi`, `/insan-kaynaklari`, `/temsilciklerimiz`, `/banka-hesaplari`.

---

## K. Admin Panel (`/admin`) — Faz 2

**Tercih: Filament v4** (hızlı + güzel + kuvvetli).

Modüller:
- Dashboard (toplam bağış, aktif kampanyalar, son bağışçılar)
- Kampanyalar CRUD (resim galeri + zengin metin)
- Bağışlar (filtreleme, dışa aktar CSV/Excel)
- Bağışçılar
- Haberler/Blog
- Ülkeler & Çalışma alanları
- Sayfalar (about/iletişim gibi statikler için)
- Ayarlar (telefon, IBAN, alert bar, çalışma saatleri, sosyal medya)
- Kullanıcılar & Roller (Spatie Permission)
- Newsletter aboneleri

---

## L. Çok Dilli Destek
- `lang/tr/site.php`, `lang/en/site.php`
- URL'de `/en/...` prefix (ya da subdomain — sonradan karar).
- Veri tablolarında `name_tr / name_en / description_tr / description_en` kolonları (ya da Spatie Translatable paketi).

---

## M. SEO & Schema.org
- Her public sayfada `<title>`, `meta description`, OpenGraph & Twitter Card.
- Kampanya & Haber için `Article` ve `DonateAction` schema.
- Sitemap.xml otomatik (kampanya/haber URL'leri dahil).
- Robots.txt + canonical.

---

## N. Performance Hedefleri
- Lighthouse Performance ≥ 90 (mobil)
- LCP ≤ 2.5 sn
- CLS ≤ 0.1
- Görseller WebP + responsive `srcset`
- Critical CSS inline (Tailwind v4 + Vite ile zaten optimize)

---

**Son güncelleme:** 2026-05-02
