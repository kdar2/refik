# Refik Vakfı / Dernek Web Sitesi — Claude Code Yönerge Dosyası

Bu dosya **Claude Code**'un projeyi anlamasını ve tutarlı bir şekilde geliştirmesini sağlar. Her sohbet/oturum başında otomatik olarak okunur.

---

## 1. Proje Tanımı

**Refik Derneği** referans alınarak inşa edilen, **modern, hızlı, erişilebilir ve son derece şık** bir İslami yardım/bağış platformu. Hedef: Türkiye'deki en iyi vakıf sitelerinden biri görünümü.

Referans site: `https://remoshop.com.tr/refikdernegi/index.php` (sadece referans — birebir kopya değil)

**Ana fonksiyonlar:**
- Bağış kampanyaları (Zekat, Fitre, Kurban, Sadaka-i Cariye, Genel Bağış)
- Tek sefer / düzenli bağış akışı + ödeme entegrasyonu (iyzico/PayTR — placeholder)
- Zekat hesaplayıcı (client-side, hiçbir veri sunucuya gitmez)
- Çok dilli + çok para birimli görünüm (TR/EN, TRY/USD/EUR)
- Haber/blog (Medya ve Duyurular)
- Ülke/lokasyon bazlı çalışma alanları (interaktif harita)
- SMS bağış görselleri, online görüşme/randevu
- Newsletter, iletişim formu, gönüllü/yardım talebi formları
- Admin paneli (Filament veya custom Blade) — kampanya, haber, sayfa yönetimi

---

## 2. Teknoloji Stack (kuruldu, değiştirme)

| Katman | Teknoloji | Not |
|---|---|---|
| Backend | Laravel 13 + PHP 8.3 | `src/` altında |
| Frontend | Blade + Tailwind CSS v4 + Vite 8 | CSS-first theme (`@theme` bloğu) |
| Veritabanı | MySQL 8 | docker servis adı: `mysql` |
| Web sunucu | Nginx (port `8090`) | `docker/nginx/default.conf` |
| Yönetim | phpMyAdmin (port `8081`) | |
| JS framework | Alpine.js 3 + tek bir Stimulus benzeri sade JS | Ağır SPA YOK |
| İkon | Lucide (CDN veya `lucide` npm) | |
| Animasyon | CSS + tiny GSAP (gerektiğinde) | |
| Yönetim paneli | **Filament v4** önerisi (sonradan eklenecek) | Faz 2'de |

**Tailwind v4 NOT:** Bu projede `tailwind.config.js` **kullanılmaz**. Tüm tema değişkenleri `resources/css/app.css` dosyasındaki `@theme {}` bloğunda tanımlanır. Bkz. `docs/DESIGN.md`.

---

## 3. Klasör/Yol Yapısı (geliştirme yapılırken bu yapıya uy)

```
refik/                          ← Repo kökü (host: C:\Users\kadir\Desktop\refik)
├── docker/                     ← Dockerfile, nginx config
├── docker-compose.yml          ← app/nginx/mysql/phpmyadmin
├── docs/                       ← Tüm proje dokümantasyonu (bu klasördeki *.md dosyalarını ÖNCE oku)
│   ├── SPEC.md                 ← Sayfa & bölüm spesifikasyonu
│   ├── DATABASE.md             ← Şema, modeller, ilişkiler
│   ├── DESIGN.md               ← Renk, tipografi, component standartları
│   └── ROADMAP.md              ← Faz faz uygulama planı
├── CLAUDE.md                   ← BU dosya
└── src/                        ← Laravel kökü
    ├── app/
    │   ├── Http/Controllers/Site/      ← Public site controllerları
    │   ├── Http/Controllers/Admin/     ← Admin controllerlar
    │   ├── Models/                     ← Eloquent modeller
    │   ├── Services/                   ← Bağış, ödeme, mail vb. servis sınıfları
    │   ├── View/Components/            ← Blade component sınıfları
    │   └── Enums/                      ← PHP 8 enumlar (DonationType, Currency vs.)
    ├── resources/
    │   ├── css/app.css                 ← Tailwind v4 + @theme + custom utilities
    │   ├── js/app.js                   ← Alpine + ufak modüller
    │   ├── views/
    │   │   ├── layouts/app.blade.php
    │   │   ├── partials/{header,footer,quick-donate-bar,topbar}.blade.php
    │   │   ├── components/             ← Anonim Blade componentleri (button, card, ...)
    │   │   ├── pages/                  ← Public sayfalar (home, campaigns, ...)
    │   │   └── admin/                  ← Admin görünümler (eğer Filament değilse)
    ├── routes/web.php
    ├── database/migrations/
    └── database/seeders/
```

---

## 4. Çalıştırma & Komut Sırası (Docker Compose)

```powershell
# Repo kökünden
docker compose up -d
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app npm install
docker compose exec app npm run dev    # geliştirmede
docker compose exec app npm run build  # production build
```

Site: `http://localhost:8090` — phpMyAdmin: `http://localhost:8081`

**Önemli:** Tüm `php artisan`, `composer`, `npm` komutları `docker compose exec app …` üzerinden çalıştırılır. Host'ta direkt `php` veya `composer` çağırma.

---

## 5. Geliştirme Kuralları (Claude Code bunlara uymalı)

### Kod Stili
- PHP: **PSR-12** + Laravel Pint. `docker compose exec app ./vendor/bin/pint`
- Blade: 4 boşluk indent, küçük harf component isimleri (`<x-button>`).
- JS: Modüler, ES2020+, fonksiyon başına ≤ 50 satır.
- CSS: Tailwind utility-first. Custom CSS sadece `@theme`, `@layer components`, ya da gerçekten gerekli olan animasyonlar için.

### İsimlendirme
- Controller: `HomeController`, `DonationController` (PascalCase + `Controller` eki)
- Model: tekil PascalCase: `Campaign`, `Donation`, `Country`
- Migration: `create_campaigns_table` snake_case
- Blade view: kebab-case (`donation-form.blade.php`)
- Route: kebab-case URL, snake_case route name (`campaigns.show`)

### Mimari İlkeler
1. **Fat Model, Skinny Controller** — iş mantığı service sınıflarında.
2. **Form Request** sınıfı her POST/PUT işleminde zorunlu (validation tek noktada).
3. **Repository pattern KULLANMA** — Eloquent zaten yeterli.
4. **N+1 sorgu yok** — Eager loading (`with`) kullanılacak.
5. **Tüm metinler ve para birimleri çok dilli** — `lang/tr/`, `lang/en/`, ve currency helper.
6. **Erişilebilirlik (a11y)** — semantic HTML, ARIA labels, klavye navigasyonu, kontrast oranı AA.

### Güvenlik
- `.env` asla commitlenmez.
- Tüm formlar CSRF korumalı (Laravel default).
- Bağış tutarları sunucuda yeniden doğrulanır (asla client'a güvenme).
- XSS için `{{ }}` (escaped) kullan, `{!! !!}` sadece güvenli/sanitize edilmiş HTML için.

### Performans
- Görselleri WebP/AVIF olarak servis et, `loading="lazy"`.
- Cache: kampanya listesi, footer bilgileri, ülke listesi `Cache::remember(...)` ile.
- Veritabanı indexlemeleri migration'larda yapılır.

---

## 6. Tasarım Felsefesi (özet — detay: `docs/DESIGN.md`)

- **Modern, sade, premium**: çok beyaz alan, büyük tipografi, yumuşak gölgeler.
- **Renk paleti** (rafine premium tonlar):
  - Birincil — Royal Indigo Navy: `#0B295C`
  - Vurgu — Wine Crimson (bağış): `#D52B52`
  - Altın — Champagne (Kurban/lüks): `#C09740`
  - Nötr açık zeminler: `#F6F8FC`, `#FFFFFF`
- **Tipografi**: Inter veya Plus Jakarta Sans (UI), Cormorant Garamond (büyük başlıklar — opsiyonel premium dokunuş).
- **Köşe yuvarlaklığı**: 12–24 px arası tutarlı.
- **Mikro etkileşim**: hover'da yumuşak yukarı kayma (translateY(-2px)) ve gölge artışı.
- **Sticky bottom bar**: ana sayfada her zaman görünen "Hızlı Bağış Yap" çubuğu.

---

## 7. Yeni Geliştirme İçin İş Akışı

Her yeni feature/sayfa için sırasıyla:

1. **`docs/SPEC.md` oku** — bölümün ne içermesi gerektiğini gör.
2. **`docs/DATABASE.md` oku** — model/şema mevcutsa kullan, değilse migration ekle.
3. **TaskList kullan** (Claude Code) — adımları planla.
4. **Migration → Model → Factory/Seeder → Controller → Route → Blade → Style** sırası.
5. **Test yaz** — en azından `RouteRegistration` ve form validation testleri (`tests/Feature/`).
6. **Lint & Format**: `pint` + `npm run build` çalıştır, hata yoksa commit.
7. **Commit mesajı**: Conventional Commits — `feat(home): add featured campaigns section`.

---

## 8. Hatırlatma

- **Spec değiştirmek isteyen kullanıcı isteklerini önce CLAUDE.md / docs/ ile karşılaştır**, çatışırsa kullanıcıya sor.
- **Refik Derneği'nden görsel/metin BIREBIR kopyalama** — yapı/fikir referans alınabilir, copy/paste yapılmaz.
- **`Lorem Ipsum`** kalmasın — anlamlı Türkçe placeholder kullan.

---

## 9. İletişim & Hesaplar (örnek/placeholder)

- E-posta: `info@refikdernegi.org` (placeholder)
- Telefon: `+90 501 567 33 33` (placeholder)
- Adres: Dumlupınar Blv. No:274/6-65 Çankaya/Ankara

Bu değerler `config/site.php` dosyasında merkezi tutulur, viewlar oradan okur.

---

**Son güncelleme:** 2026-05-02
