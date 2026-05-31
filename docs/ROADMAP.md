# Geliştirme Yol Haritası (ROADMAP)

Sıralı, bağımlılıkları olan **6 faz**. Her fazın sonunda çalışan ve test edilmiş bir adım çıkmalı.

---

## Faz 0 — Hazırlık (mevcut durum)

✅ Docker Compose (app, nginx, mysql, phpmyadmin) çalışıyor
✅ Laravel 13 + Tailwind 4 + Vite 8 kurulu
✅ docs/ klasöründe SPEC, DATABASE, DESIGN, ROADMAP
✅ CLAUDE.md ana yönerge dosyası

---

## Faz 1 — Altyapı & Layout (1–2 gün)

**Hedef:** Çalışan public layout, header/footer, anasayfa iskeleti.

### 1.1 Konfigürasyon
- [ ] `.env` — `DB_DATABASE=refik`, `DB_USERNAME=refik`, `DB_PASSWORD=secret` (mevcut compose ile uyumlu)
- [ ] `APP_LOCALE=tr`, `APP_FALLBACK_LOCALE=en`
- [ ] `config/site.php` — telefon, e-posta, IBAN, adres, sosyal medya — `settings` tablosundan okunacak helper
- [ ] `config/currencies.php` — desteklenen para birimleri

### 1.2 CSS / Tema
- [ ] `resources/css/app.css` — `docs/DESIGN.md` §11'deki tüm `@theme` ve component class'larını yaz
- [ ] Font dosyalarını `public/fonts/` altına ekle (Inter Variable + Plus Jakarta Sans Variable + opsiyonel Cormorant)
- [ ] `resources/js/app.js` — Alpine.js + lucide ikonlar import

### 1.3 Layout
- [ ] `resources/views/layouts/app.blade.php` — head (meta/SEO/font preload), body, slots
- [ ] `resources/views/partials/topbar.blade.php`
- [ ] `resources/views/partials/header.blade.php`
- [ ] `resources/views/partials/footer.blade.php`
- [ ] `resources/views/partials/quick-donate-bar.blade.php`
- [ ] `resources/views/partials/alert-bar.blade.php`
- [ ] `resources/views/components/button.blade.php`
- [ ] `resources/views/components/section.blade.php`
- [ ] `resources/views/components/icon.blade.php` (lucide wrapper)

### 1.4 Routes & Controllers iskeleti
- [ ] `routes/web.php` — public route grubunu tanımla
- [ ] `app/Http/Controllers/Site/HomeController.php` — `__invoke()` (tek aksiyonlu)
- [ ] Anasayfa şu anda boş bölümlerle çalışıyor olmalı

### Çıktı kontrolü
- `http://localhost:8090` açılınca header + footer + boş anasayfa + sticky bağış çubuğu görünür.
- Tasarım sistemine uygun renkler ve tipografi yüklenmiş.

---

## Faz 2 — Veritabanı & Modeller (2–3 gün)

**Hedef:** Tüm tablolar, modeller, seed verileri.

### 2.1 Migrationlar
`docs/DATABASE.md §5`'teki sırada **21 migration** dosyası oluştur ve çalıştır:
```bash
docker compose exec app php artisan make:migration create_campaign_categories_table
# ... 21 kez
docker compose exec app php artisan migrate
```

### 2.2 Modeller
Her tablo için Eloquent model:
- `Campaign`, `CampaignCategory`, `Country`, `Donation`, `DonationIntention`
- `Post`, `PostCategory`, `Page`, `Slider`
- `Appointment`, `NewsletterSubscriber`, `ContactMessage`
- `Volunteer`, `HelpRequest`, `JobApplication`
- `AuditReport`, `SmsDonationCode`, `ZekatNisabSetting`
- `Setting`, `CurrencyRate`

`Sluggable` paketi: `composer require cviebrock/eloquent-sluggable`

### 2.3 Factory + Seeder
- Her model için factory (sahte veri üretmek için)
- `DatabaseSeeder` içinde `docs/DATABASE.md §4`'teki sırayla çağrılır:
```bash
docker compose exec app php artisan db:seed
```

### 2.4 Helper / Utility
- `App\Support\Money` — para birimi gösterimi (`Money::format(15000, 'TRY')`)
- `App\Support\Settings` — `Settings::get('site.phone')` kısayolu
- `App\Services\CurrencyConverter` — TL ↔ USD/EUR

### Çıktı kontrolü
- phpMyAdmin'de tüm tablolar var, seed verileri görünüyor.
- `php artisan tinker`'da `Campaign::featured()->get()` çalışıyor.

---

## Faz 3 — Anasayfa Bölümleri (3–5 gün)

**Hedef:** Ana sayfayı tüm bölümleriyle çalışır hale getir.

### 3.1 Hero Slider (B1)
- `App\Http\View\Composers\HomeComposer` ile `Slider::active()->ordered()->get()` view'a paylaşılır
- Alpine.js veya Embla.js ile slider
- Auto-advance + lazy load + reduced-motion

### 3.2 Güven Bandı (B2)
- 3 statik rozet + paragraf — `partials/sections/trust.blade.php`

### 3.3 Öne Çıkan Çağrılar (B3)
- `Campaign::active()->featured()->ordered()->limit(8)->with('category','country')->get()`
- Filtre dropdown: kategori bazlı (AJAX yenileme — Alpine + `wire:` yerine sadece query string ve Turbo benzeri yenileme)
- `donation-progress` component'i kullanılır

### 3.4 Etki İstatistikleri (B4)
- `Setting::group('stats')` ya da `home_stats` tablosu
- IntersectionObserver + countup animasyonu (saf JS)

### 3.5 Hayra Yoldaş (B5)
- Markdown veya `pages` tablosundan "Hakkımızda Özeti" çekilir
- 7 hızlı erişim ikonu — `partials/sections/quick-access.blade.php`

### 3.6 Zekat Vurgu (B6)
- 2 statik kart — `pages` tablosundan "Zekat Rehberi" özeti

### 3.7 Zekat Hesaplayıcı + Online Görüşme (B7)
- **Hesaplayıcı:** Pure JS modülü `resources/js/modules/zakat-calculator.js`
  - `ZekatNisabSetting::latest()->first()` viewa pas edilir.
  - Form alanları, `Zekatı Hesapla` aksiyonu hesabı yapar, sonuç gösterir.
- **Randevu:** Alpine.js takvim component'i, müsait günleri DB'den çek (`/api/appointments/availability`)
- POST `/appointments` → mailable + DB.

### 3.8 SMS Bağış (B8)
- `SmsDonationCode::active()->ordered()->get()` — 3 kart

### 3.9 Kategori Slider (B9)
- `CampaignCategory::active()->ordered()->get()`
- Yatay scroll-snap + Embla.js

### 3.10 Dünya Haritası (B10)
- `Country::where('is_active_region', true)->get()`
- SVG inline (jvectormap veya basit özel SVG path)
- Marker hover/click → ülke detay yan paneli

### 3.11 Medya & Duyurular (B11)
- `Post::published()->latest()->limit(7)->get()`
- 1 büyük + 6 thumbnail + sağda 4 küçük

### 3.12 Newsletter (B12)
- `POST /newsletter/subscribe` (validation + mail confirm)

### Çıktı kontrolü
- Anasayfa Lighthouse Performance ≥ 85, görsel olarak Refik'e çok yakın bir kalite.

---

## Faz 4 — Bağış Akışı & Ödeme (3–4 gün)

**Hedef:** Uçtan uca bağış yapma, kampanya detay, sertifika.

### 4.1 Kampanya Sayfaları
- `/calismalarimiz` — liste + filtreler (kategori, bölge, tür, durum)
- `/calismalarimiz/{slug}` — detay + sticky bağış kartı

### 4.2 Bağış Akışı (`/donate`)
- 4 adımlı multi-step (Alpine.js step state)
- Form Request validation her adım için
- `App\Services\Donation\DonationService` — bağış kaydı, e-posta, sertifika

### 4.3 Ödeme Entegrasyonu (placeholder)
- `App\Services\Payment\PaymentGatewayInterface`
- `App\Services\Payment\IyzicoGateway` ve `App\Services\Payment\PaytrGateway`
- Webhook `POST /webhooks/iyzico` — `payment_status` günceller

### 4.4 Sertifika & Mail
- DomPDF veya `barryvdh/laravel-dompdf` ile PDF sertifika
- `BağışTeşekkürMail` mailable + queueable

### 4.5 Düzenli Bağış
- Cron job: `php artisan donations:charge-recurring` günlük çalışır
- Database queue üzerinden işle

### Çıktı kontrolü
- Test ortamında bağış adımları sonuna kadar çalışıyor (sandbox payment).
- E-posta + sertifika geliyor.

---

## Faz 5 — İçerik Sayfaları & Admin (3–5 gün)

### 5.1 Statik & İçerik Sayfaları
- `/hakkimizda`, `/etki-ve-guvence`, `/nerelerde-calisiyoruz`, `/iletisim`, `/haberler`, `/yardim-talebi`, `/insan-kaynaklari`
- Form sayfaları: contact, volunteer, help-request, job-application

### 5.2 Admin Paneli (Filament v4)
```bash
docker compose exec app composer require filament/filament:"^4.0"
docker compose exec app php artisan filament:install --panels
docker compose exec app php artisan make:filament-user
```
- Resources: Campaign, Post, Slider, Page, Donation (read-only + filter), Setting, User, Country
- Spatie Permission: roller (`super-admin`, `editor`, `accountant`)
- Dashboard widget'ları: toplam bağış, son 7 gün, aktif kampanyalar

### 5.3 Çok Dilli & Para Birimi
- Middleware `SetLocale` → URL prefix'ten dili al
- `lang/tr/site.php`, `lang/en/site.php`
- Currency middleware: cookie/session'dan oku, `Money::format` helper'a aktar

### Çıktı kontrolü
- Admin'den kampanya/haber/slider eklenip site'da görünüyor.
- TR/EN dil değişimi çalışıyor.

---

## Faz 6 — Test, Optimizasyon, Deploy (2–3 gün)

### 6.1 Test
- Pest veya PHPUnit feature testleri:
  - Anasayfa render
  - Kampanya CRUD
  - Bağış akışı validation
  - Newsletter subscribe
- Smoke test: `php artisan test`
- Browser test: Pest browser (Dusk benzeri)

### 6.2 Performans
- Lighthouse audit + iyileştirme
- Image optimization (`spatie/laravel-image-optimizer`)
- Route cache, view cache, config cache (production)
- HTTP/2 + gzip + brotli (nginx)

### 6.3 SEO
- Sitemap.xml otomatik (`spatie/laravel-sitemap`)
- robots.txt
- Schema.org JSON-LD: Article, Organization, DonateAction
- OpenGraph + Twitter Card her sayfada

### 6.4 Güvenlik
- CSP header
- Rate limit: bağış formu 5/dk, contact 3/dk
- HTTPS zorla (production .env: `APP_URL=https://`)
- `php artisan storage:link`

### 6.5 Deploy
- Production Docker compose (PHP-FPM + Nginx + MySQL)
- GitHub Actions: lint + test + build → SSH deploy
- Backup cron: db dump + storage tarball günlük S3'e

---

## Tahmini Süre Toplamı

| Faz | Süre |
|---|---|
| Faz 1 | 1–2 gün |
| Faz 2 | 2–3 gün |
| Faz 3 | 3–5 gün |
| Faz 4 | 3–4 gün |
| Faz 5 | 3–5 gün |
| Faz 6 | 2–3 gün |
| **Toplam** | **14–22 gün** |

---

## Öncelik Sırası (MVP)

Eğer hızlı bir MVP isteniyorsa şu sıra:

1. Faz 1 + Faz 2 (zorunlu altyapı) — 3–5 gün
2. Faz 3 §3.1, §3.2, §3.3, §3.11 (Hero, Güven, Kampanyalar, Haberler) — 2 gün
3. Faz 4 §4.1, §4.2 (Kampanya detay + bağış akışı) — 2 gün
4. Faz 5 §5.2 minimal admin (Filament) — 2 gün

Toplam **~9–11 gün** içinde demoya hazır canlı bir vakıf sitesi.

---

**Son güncelleme:** 2026-05-02
