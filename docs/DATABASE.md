# Veritabanı Şeması & Modeller

MySQL 8.0 — DB adı: `refik`. Tüm tablolar `utf8mb4_unicode_ci`.

İlke: **Veri ismi snake_case, model PascalCase tekil**. Foreign key: `referencing_table_id`.

---

## 1. ER Şeması (özet)

```
users ─┐
       ├── donations ──┐
       │              ├── campaigns ── campaign_categories
       │              ├── countries
       │              └── donation_intentions
       ├── appointments
       ├── newsletter_subscribers
       └── contact_messages

posts ── post_categories
pages
settings
sliders
sms_donation_codes
zekat_nisab_settings
audit_reports
volunteers
help_requests
job_applications
```

---

## 2. Tablo Listesi & Migration İskeletleri

### 2.1 `users`
Laravel default + ek alanlar.
```php
$table->id();
$table->string('name');
$table->string('email')->unique();
$table->string('phone')->nullable();
$table->string('password');
$table->enum('role', ['admin','editor','viewer','member'])->default('member');
$table->boolean('newsletter')->default(false);
$table->timestamp('email_verified_at')->nullable();
$table->rememberToken();
$table->timestamps();
$table->softDeletes();
```

### 2.2 `campaign_categories`
```php
$table->id();
$table->string('slug')->unique();
$table->string('name_tr');
$table->string('name_en');
$table->string('icon')->nullable();         // lucide icon name
$table->string('color', 7)->default('#0B295C');
$table->text('description_tr')->nullable();
$table->text('description_en')->nullable();
$table->integer('order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```
Örnek kategoriler: gida, su-kuyusu, yetim, egitim, saglik, kurban, zekat, sadaka-i-cariye, gazze-acil, sudan, suriye, barinma, giyim, orman.

### 2.3 `countries`
```php
$table->id();
$table->string('code', 3)->unique();        // ISO 3166-1 alpha-3
$table->string('name_tr');
$table->string('name_en');
$table->decimal('lat', 9, 6)->nullable();   // harita marker
$table->decimal('lng', 9, 6)->nullable();
$table->string('flag_emoji', 8)->nullable();
$table->text('description_tr')->nullable();
$table->text('description_en')->nullable();
$table->string('hero_image')->nullable();
$table->boolean('is_active_region')->default(false); // bizim çalıştığımız bölge mi
$table->timestamps();
```

### 2.4 `campaigns`  (en önemli tablo)
```php
$table->id();
$table->string('slug')->unique();
$table->string('title_tr');
$table->string('title_en')->nullable();
$table->text('subtitle_tr')->nullable();
$table->text('subtitle_en')->nullable();
$table->longText('description_tr');
$table->longText('description_en')->nullable();

$table->foreignId('category_id')->constrained('campaign_categories');
$table->foreignId('country_id')->nullable()->constrained('countries');

$table->string('cover_image');                       // 1600x900
$table->json('gallery')->nullable();                 // ek görseller
$table->string('video_url')->nullable();

$table->decimal('goal_amount', 15, 2)->nullable();   // niyet edilen
$table->decimal('raised_amount', 15, 2)->default(0); // toplanan (cron ile güncellenir)
$table->char('currency', 3)->default('TRY');
$table->integer('donor_count')->default(0);

$table->boolean('zakat_eligible')->default(false);
$table->boolean('sadaka_eligible')->default(false);
$table->boolean('fitre_eligible')->default(false);
$table->boolean('kurban_eligible')->default(false);

$table->boolean('is_featured')->default(false);
$table->boolean('is_emergency')->default(false);
$table->boolean('is_active')->default(true);

$table->date('start_date')->nullable();
$table->date('end_date')->nullable();

$table->json('seo')->nullable();                     // {title, description, og_image}
$table->integer('order')->default(0);
$table->timestamps();
$table->softDeletes();

$table->index(['is_active','is_featured','order']);
```

### 2.5 `donations`
```php
$table->id();
$table->string('reference')->unique();               // RFK-2026-000123 formatı
$table->foreignId('user_id')->nullable()->constrained();
$table->foreignId('campaign_id')->nullable()->constrained();

$table->string('donor_name');
$table->string('donor_email');
$table->string('donor_phone')->nullable();
$table->string('tckn', 11)->nullable();              // vergi indirimi
$table->string('company_name')->nullable();
$table->string('tax_office')->nullable();
$table->string('tax_no')->nullable();

$table->decimal('amount', 15, 2);
$table->char('currency', 3)->default('TRY');
$table->decimal('amount_try', 15, 2);                // her zaman TL karşılığı kayıt

$table->enum('type', ['general','zakat','fitre','sadaka','kurban','adak','kefaret']);
$table->enum('frequency', ['one_time','monthly','quarterly','yearly'])->default('one_time');
$table->date('next_charge_at')->nullable();          // düzenli bağış için
$table->boolean('is_recurring')->default(false);
$table->boolean('is_corporate')->default(false);

$table->string('intention')->nullable();             // niyet (rahmetli yakın vs.)
$table->string('intention_for')->nullable();         // ad-soyad
$table->text('message')->nullable();

$table->enum('payment_method', ['credit_card','bank_transfer','sms','crypto']);
$table->enum('payment_status', ['pending','completed','failed','refunded','cancelled'])->default('pending');
$table->string('payment_provider')->nullable();      // iyzico/paytr
$table->string('payment_transaction_id')->nullable();
$table->json('payment_response')->nullable();

$table->boolean('certificate_requested')->default(false);
$table->string('certificate_path')->nullable();

$table->timestamp('completed_at')->nullable();
$table->timestamps();
$table->softDeletes();

$table->index(['payment_status','created_at']);
$table->index(['campaign_id','payment_status']);
$table->index('type');
```

### 2.6 `donation_intentions` (preset niyetler)
```php
$table->id();
$table->string('label_tr');
$table->string('label_en');
$table->integer('order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```
Örnek: Kendi adıma / Rahmetli yakınım için / Kefaret / Adak / Şükür.

### 2.7 `post_categories`
```php
$table->id();
$table->string('slug')->unique();
$table->string('name_tr');
$table->string('name_en');
$table->boolean('is_active')->default(true);
$table->timestamps();
```
Örnek: saha-haberleri, duyurular, basinda-biz, etkinlikler.

### 2.8 `posts` (haberler/blog)
```php
$table->id();
$table->string('slug')->unique();
$table->string('title_tr');
$table->string('title_en')->nullable();
$table->text('excerpt_tr')->nullable();
$table->longText('content_tr');
$table->longText('content_en')->nullable();
$table->foreignId('post_category_id')->nullable()->constrained();
$table->foreignId('author_id')->nullable()->constrained('users');
$table->string('cover_image');
$table->json('gallery')->nullable();
$table->string('video_url')->nullable();
$table->boolean('is_featured')->default(false);
$table->boolean('is_published')->default(false);
$table->timestamp('published_at')->nullable();
$table->json('seo')->nullable();
$table->integer('view_count')->default(0);
$table->timestamps();
$table->softDeletes();
$table->index(['is_published','published_at']);
```

### 2.9 `pages` (statik içerik sayfaları)
```php
$table->id();
$table->string('slug')->unique();
$table->string('title_tr');
$table->string('title_en')->nullable();
$table->longText('body_tr');
$table->longText('body_en')->nullable();
$table->json('seo')->nullable();
$table->boolean('is_published')->default(true);
$table->timestamps();
```

### 2.10 `sliders` (ana sayfa hero)
```php
$table->id();
$table->string('eyebrow_tr')->nullable();
$table->string('title_tr');
$table->string('title_en')->nullable();
$table->text('subtitle_tr')->nullable();
$table->text('subtitle_en')->nullable();
$table->string('image');
$table->string('cta_text_tr')->nullable();
$table->string('cta_url')->nullable();
$table->string('overlay_color', 7)->default('#0B295C');
$table->tinyInteger('overlay_opacity')->default(40); // %
$table->integer('order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### 2.11 `appointments` (online görüşme)
```php
$table->id();
$table->string('full_name');
$table->string('email');
$table->string('phone');
$table->date('date');
$table->time('time');
$table->enum('topic', ['donation_advisory','zakat_advisory','will','general']);
$table->text('notes')->nullable();
$table->enum('status', ['pending','confirmed','completed','cancelled'])->default('pending');
$table->timestamps();
$table->index(['date','status']);
```

### 2.12 `newsletter_subscribers`
```php
$table->id();
$table->string('email')->unique();
$table->string('name')->nullable();
$table->string('language', 5)->default('tr');
$table->boolean('is_active')->default(true);
$table->string('verification_token')->nullable();
$table->timestamp('verified_at')->nullable();
$table->timestamp('unsubscribed_at')->nullable();
$table->timestamps();
```

### 2.13 `contact_messages`
```php
$table->id();
$table->string('full_name');
$table->string('email');
$table->string('phone')->nullable();
$table->string('subject');
$table->text('message');
$table->boolean('is_read')->default(false);
$table->boolean('is_archived')->default(false);
$table->timestamps();
```

### 2.14 `volunteers` (gönüllü başvurusu)
```php
$table->id();
$table->string('full_name');
$table->string('email');
$table->string('phone');
$table->string('city');
$table->date('birth_date');
$table->json('areas');                  // ['saha','egitim','tercume',...]
$table->text('experience')->nullable();
$table->json('availability')->nullable();
$table->enum('status', ['pending','accepted','rejected'])->default('pending');
$table->timestamps();
```

### 2.15 `help_requests` (yardım talebi)
```php
$table->id();
$table->string('full_name');
$table->string('email')->nullable();
$table->string('phone');
$table->string('city');
$table->string('district');
$table->string('category');             // gida/saglik/barinma/egitim
$table->text('description');
$table->json('attachments')->nullable();
$table->enum('status', ['pending','reviewing','accepted','rejected','completed'])->default('pending');
$table->timestamps();
```

### 2.16 `job_applications`
```php
$table->id();
$table->string('full_name');
$table->string('email');
$table->string('phone');
$table->string('position');             // başvurulan pozisyon
$table->string('cv_path');
$table->text('cover_letter')->nullable();
$table->enum('status', ['pending','reviewing','interview','hired','rejected'])->default('pending');
$table->timestamps();
```

### 2.17 `audit_reports` (denetim/faaliyet raporları)
```php
$table->id();
$table->string('title_tr');
$table->string('title_en')->nullable();
$table->year('year');
$table->enum('type', ['annual','audit','financial','transparency']);
$table->string('file_path');           // PDF
$table->bigInteger('file_size')->nullable();
$table->string('cover_image')->nullable();
$table->boolean('is_published')->default(true);
$table->timestamps();
```

### 2.18 `sms_donation_codes`
```php
$table->id();
$table->string('label_tr');             // "Bağış", "Zekat", "İlim"
$table->string('label_en');
$table->string('short_code', 10);       // 2516, 7705
$table->string('keyword')->nullable();
$table->decimal('amount', 10, 2);
$table->char('currency', 3)->default('TRY');
$table->string('qr_code_path')->nullable();
$table->text('description_tr')->nullable();
$table->integer('order')->default(0);
$table->boolean('is_active')->default(true);
$table->timestamps();
```

### 2.19 `zekat_nisab_settings`
Admin'den güncellenir, hesaplayıcı bunu okur.
```php
$table->id();
$table->decimal('gold_price_per_gram', 10, 2);  // TL/gr
$table->decimal('silver_price_per_gram', 10, 2);
$table->decimal('nisab_gold_grams', 8, 2)->default(80.18);
$table->decimal('nisab_silver_grams', 8, 2)->default(560);
$table->date('updated_for_date');
$table->timestamps();
```

### 2.20 `settings` (key-value, esnek)
```php
$table->id();
$table->string('key')->unique();
$table->longText('value')->nullable();
$table->string('type')->default('string'); // string|json|bool|int
$table->string('group')->default('general');
$table->timestamps();
```
Örnek key'ler:
- `site.phone`, `site.email`, `site.address`, `site.iban`
- `social.instagram`, `social.facebook`, `social.youtube`, `social.twitter`
- `efficiency.programs` (80), `efficiency.fundraising` (12), `efficiency.management` (8)
- `alert.enabled`, `alert.text`, `alert.link`
- `permission.help_collection_no`, `permission.registry_no`
- `currency.default`, `currency.rates_provider`

### 2.21 `currency_rates`
```php
$table->id();
$table->char('from_currency', 3);
$table->char('to_currency', 3);
$table->decimal('rate', 16, 6);
$table->timestamp('fetched_at');
$table->timestamps();
$table->unique(['from_currency','to_currency','fetched_at']);
```

### 2.22 `media` (genel görsel/dosya kütüphanesi)
- Kullanım: Spatie Media Library paketi (önerilir, faz 2)
- Şu an: `cover_image`, `gallery` json kolonları yeterli.

---

## 3. Eloquent Model Standartları

Her model:
- `$fillable` veya `$guarded = []` (tercihen `$fillable` whitelist).
- `$casts`: tarih, json, bool, decimal alanları.
- `slug` üretmek için `Sluggable` trait (cviebrock/eloquent-sluggable) veya basit `creating` event.
- `scopePublished()`, `scopeActive()`, `scopeFeatured()` gibi local scope'lar.
- İlişkiler: `hasMany`, `belongsTo`, `hasManyThrough` standart.

**Örnek: `Campaign` modeli**
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\Sluggable;

class Campaign extends Model
{
    use SoftDeletes, Sluggable;

    protected $guarded = [];

    protected $casts = [
        'gallery' => 'array',
        'seo' => 'array',
        'goal_amount' => 'decimal:2',
        'raised_amount' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_emergency' => 'boolean',
        'is_active' => 'boolean',
        'zakat_eligible' => 'boolean',
        'sadaka_eligible' => 'boolean',
        'fitre_eligible' => 'boolean',
        'kurban_eligible' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title_tr']];
    }

    public function category()  { return $this->belongsTo(CampaignCategory::class); }
    public function country()   { return $this->belongsTo(Country::class); }
    public function donations() { return $this->hasMany(Donation::class); }

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }

    public function getProgressPercentAttribute(): int
    {
        if (!$this->goal_amount) return 0;
        return min(100, (int) round(($this->raised_amount / $this->goal_amount) * 100));
    }
}
```

---

## 4. Seeder Planı

Sırayla çalışır:
1. `CampaignCategorySeeder` — 14 kategori
2. `CountrySeeder` — 60 ülke (kayda değer)
3. `SettingSeeder` — global ayarlar
4. `SliderSeeder` — 5 hero slayt
5. `SmsDonationCodeSeeder` — 3 SMS kodu
6. `ZekatNisabSeeder` — başlangıç altın/gümüş fiyatı
7. `DonationIntentionSeeder` — 5 niyet
8. `PostCategorySeeder` + `PostSeeder` — 4 kategori + 12 örnek haber
9. `CampaignSeeder` — 12 örnek kampanya (görsel placeholder ile)
10. `UserSeeder` — admin kullanıcısı (`admin@refik.test` / `password`)

---

## 5. Migration Sıralaması (timestamp-aware)

```
2026_05_02_000001_create_campaign_categories_table
2026_05_02_000002_create_countries_table
2026_05_02_000003_create_campaigns_table
2026_05_02_000004_create_post_categories_table
2026_05_02_000005_create_posts_table
2026_05_02_000006_create_pages_table
2026_05_02_000007_create_sliders_table
2026_05_02_000008_create_donations_table
2026_05_02_000009_create_donation_intentions_table
2026_05_02_000010_create_appointments_table
2026_05_02_000011_create_newsletter_subscribers_table
2026_05_02_000012_create_contact_messages_table
2026_05_02_000013_create_volunteers_table
2026_05_02_000014_create_help_requests_table
2026_05_02_000015_create_job_applications_table
2026_05_02_000016_create_audit_reports_table
2026_05_02_000017_create_sms_donation_codes_table
2026_05_02_000018_create_zekat_nisab_settings_table
2026_05_02_000019_create_settings_table
2026_05_02_000020_create_currency_rates_table
2026_05_02_000021_extend_users_table  (role, phone, newsletter, soft_delete)
```

---

## 6. Cache Stratejisi
- `campaigns:featured` → `Cache::remember('campaigns:featured', 600, ...)` (10 dk)
- `settings.all` → 1 saat, model save'inde `Cache::forget` (Observer)
- `countries:active` → 1 gün
- `currency.rates` → 1 saat (provider'dan çekildikten sonra)

---

## 7. İndeks & Performans Notları
- `donations(payment_status, created_at)` raporlar için.
- `donations(campaign_id, payment_status)` kampanya başına toplam.
- `posts(is_published, published_at desc)` haber listesi.
- `campaigns(is_active, is_featured, order)` ana sayfa.
- `donations.amount_try` raporlamada toplam alma kolaylığı sağlar.

---

**Son güncelleme:** 2026-05-02
