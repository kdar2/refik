# Refik Derneği — Vakıf Web Sitesi

Modern, hızlı ve şık bir Türk hayır kurumu / vakıf sitesi. **Laravel 13 + Tailwind v4** ile inşa edilir, Docker ile çalışır.

---

## Hızlı Başlangıç

```powershell
# 1) Konteynerleri başlat
docker compose up -d

# 2) Laravel kurulumu (ilk seferde)
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed

# 3) Frontend bağımlılıkları
docker compose exec app npm install
docker compose exec app npm run dev

# Site:        http://localhost:8090
# phpMyAdmin:  http://localhost:8081  (root / rootsecret)
```

---

## Proje Dökümanları

Her şey `docs/` altında ve `CLAUDE.md` Claude Code'un projeye girişi için ana yönergedir.

| Dosya | İçerik |
|---|---|
| **[CLAUDE.md](./CLAUDE.md)** | Proje genel yönergesi, kurallar, klasör yapısı |
| [docs/SPEC.md](./docs/SPEC.md) | Tüm sayfalar ve bölüm spesifikasyonu |
| [docs/DATABASE.md](./docs/DATABASE.md) | Veritabanı şeması, modeller, migration sırası |
| [docs/DESIGN.md](./docs/DESIGN.md) | Renk, tipografi, component sistemi |
| [docs/ROADMAP.md](./docs/ROADMAP.md) | Faz faz uygulama planı |

> **Claude Code'a görev verirken:** Önce `CLAUDE.md` ve ilgili `docs/*.md` dosyasını oku diyerek başla.

---

## Özellik Özeti

- **Bağış Yönetimi**: Tek sefer / düzenli, Zekat / Fitre / Kurban / Sadaka, kampanya bazlı
- **Zekat Hesaplayıcı** (client-side, gizliliğe saygılı)
- **Online Görüşme / Randevu Sistemi**
- **Çok dilli (TR/EN), çok para birimli (TRY/USD/EUR)**
- **İnteraktif dünya haritası** (120+ ülke)
- **SMS Bağış + QR kod**
- **Haberler / Saha Raporları**
- **Newsletter, İletişim, Gönüllü, Yardım Talebi formları**
- **Filament v4 admin paneli** (Faz 5)
- **Erişilebilirlik Widget'ı** (kontrast, font büyütme)
- **Sticky Bottom Hızlı Bağış Çubuğu**

---

## Teknoloji

- PHP 8.3 / Laravel 13
- MySQL 8
- Tailwind CSS 4 + Vite 8
- Alpine.js 3 + Lucide ikonları
- Filament v4 (admin paneli — sonradan eklenecek)

---

## Klasör Yapısı

```
refik/
├── docker/                  # Dockerfile + nginx config
├── docker-compose.yml       # 4 servis: app/nginx/mysql/phpmyadmin
├── docs/                    # Proje dokümantasyonu (Claude Code öncelikle okur)
├── CLAUDE.md                # Claude Code ana yönergesi
└── src/                     # Laravel uygulaması
    ├── app/Http/Controllers/Site/
    ├── resources/views/{layouts,partials,components,pages}/
    ├── resources/css/app.css        # Tailwind v4 @theme
    ├── resources/js/app.js          # Alpine + Lucide
    ├── routes/web.php
    └── config/site.php              # Site ayarları
```

---

## Geliştirme İş Akışı

1. **`docs/SPEC.md`** — sayfanın spesifikasyonunu oku.
2. **`docs/DATABASE.md`** — model/şema gerekli mi?
3. **TaskList ile planla** — adımları parçala.
4. **Migration → Model → Factory/Seeder → Controller → Route → Blade**
5. `pint` + `npm run build` → commit (Conventional Commits)

---

## Lisans

Tüm hakları saklıdır © Refik Eğitim, Kültür ve Yardımlaşma Derneği.
