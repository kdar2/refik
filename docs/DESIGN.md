# Tasarım Sistemi (Design System)

Bu döküman, sitenin **görsel tutarlılığını** sağlar. Her yeni component buradaki kurallara uymalıdır.

---

## 1. Marka Kimliği

- **Logo**: "REFİK DERNEĞİ" — özel kalp + R bağlama (yer tutucu olarak metin + lucide `heart` ikonu).
- **Slogan**: "Hayra Yoldaş"
- **Ton**: sıcak, güvenilir, profesyonel, samimi. Bombastik değil, sade.

---

## 2. Renk Paleti

### Birincil Marka — Royal Indigo Navy
| Token | Hex | Kullanım |
|---|---|---|
| `--color-brand-50`  | `#EFF2FA` | Çok açık zemin |
| `--color-brand-100` | `#D5DDEF` | Açık zemin / hover |
| `--color-brand-300` | `#7A8EC3` | İkincil metin |
| `--color-brand-500` | `#2B448C` | Genel marka |
| `--color-brand-700` | `#0B295C` | **ANA marka** (header, başlıklar) |
| `--color-brand-900` | `#03132C` | Koyu zemin / footer |

### Vurgu (Bağış) — Wine Crimson
| Token | Hex | Kullanım |
|---|---|---|
| `--color-accent-500` | `#D52B52` | "Bağış Yap" butonu |
| `--color-accent-600` | `#AF1F45` | Hover |
| `--color-accent-50`  | `#FFF1F3` | Hafif vurgu zemini |

### Altın (Lüks / Kurban) — Champagne
| Token | Hex | Kullanım |
|---|---|---|
| `--color-gold-500` | `#C09740` | Kurban CTA |
| `--color-gold-600` | `#9D7B33` | Hover |
| `--color-gold-100` | `#F4ECD0` | Soft zemin |

### Nötrler
| Token | Hex | Kullanım |
|---|---|---|
| `--color-surface`     | `#FFFFFF` | Kart / form |
| `--color-surface-alt` | `#F6F8FC` | Bölüm zemini |
| `--color-border`      | `#E2E7F0` | Çizgi / ayraç |
| `--color-text`        | `#0F172A` | Ana metin |
| `--color-text-muted`  | `#64748B` | Yardımcı metin |

### Anlamsal (semantic)
| Token | Hex | Kullanım |
|---|---|---|
| `--color-success` | `#16A34A` | Başarı / progress bar |
| `--color-warning` | `#F59E0B` | Uyarı / acil |
| `--color-danger`  | `#DC2626` | Hata |
| `--color-info`    | `#0EA5E9` | Bilgi |

---

## 3. Tipografi

### Font Aileleri
- **Başlık (display)**: `"Plus Jakarta Sans", system-ui` — 700/800
- **Gövde (body)**: `"Inter", system-ui` — 400/500/600
- **Vurgu / büyük başlık (opsiyonel)**: `"Cormorant Garamond", serif` — premium dokunuş için sadece hero h1.

### Skala
| Token | Boyut | Line | Kullanım |
|---|---|---|---|
| `text-xs`   | 12px | 1.2 | Etiket, mikro |
| `text-sm`   | 14px | 1.4 | Yardımcı metin |
| `text-base` | 16px | 1.6 | Gövde |
| `text-lg`   | 18px | 1.6 | Lead paragraf |
| `text-xl`   | 20px | 1.5 | Alt başlık |
| `text-2xl`  | 24px | 1.4 | Kart başlığı |
| `text-3xl`  | 30px | 1.3 | Bölüm başlığı |
| `text-4xl`  | 36px | 1.2 | Sayfa başlığı |
| `text-5xl`  | 48px | 1.1 | Hero |
| `text-6xl`  | 60px | 1.0 | Hero büyük (lg+) |
| `text-7xl`  | 72px | 1.0 | Mega |

**Kural:** Bir sayfada en fazla 3 farklı boyut + 2 farklı ağırlık aynı anda.

---

## 4. Spacing (8px scale)

Tailwind default'a uy: `0, 1 (4), 2 (8), 3 (12), 4 (16), 6 (24), 8 (32), 12 (48), 16 (64), 20 (80), 24 (96), 32 (128)`.

**Bölüm dikey padding** (`section`):
- Mobil: `py-12` (48px)
- Tablet+: `py-20` (80px)
- Geniş ekran: `py-24` (96px)

**Container**: `max-w-7xl` (1280px) + `px-4 sm:px-6 lg:px-8`.

---

## 5. Köşe Yuvarlaklığı (Radius)

| Token | Değer | Kullanım |
|---|---|---|
| `rounded` (sm)    | 6px  | Etiket / chip |
| `rounded-lg`      | 12px | Buton / input |
| `rounded-xl`      | 16px | Kart |
| `rounded-2xl`     | 20px | Büyük kart / modal |
| `rounded-3xl`     | 24px | Hero görsel / featured |
| `rounded-full`    | ∞    | Avatar / yuvarlak buton |

---

## 6. Gölge

| Token | Kullanım |
|---|---|
| `shadow-sm`     | Mikro yükseliş |
| `shadow-md`     | Default kart |
| `shadow-lg`     | Hover kart |
| `shadow-xl`     | Modal / popover |
| `shadow-brand`  | Özel: `0 12px 40px -8px rgba(14,49,104,0.25)` — marka renkli premium gölge |

CSS'te tanımlı: `--shadow-brand: 0 12px 40px -8px rgba(14,49,104,0.25);`

---

## 7. Buton Sistemi

### Stil türleri (`.btn-*`)
- `.btn` — temel sınıf: `inline-flex items-center justify-center gap-2 font-semibold rounded-lg transition-all duration-200 focus-visible:outline-2 focus-visible:outline-offset-2`
- `.btn-primary` → marka mavi zemin + beyaz metin
- `.btn-accent` → wine crimson (#D52B52) zemin + beyaz
- `.btn-gold` → altın zemin + lacivert metin
- `.btn-outline` → şeffaf zemin + marka mavi border ve metin
- `.btn-ghost` → hover'da hafif zemin

### Boyutlar
- `.btn-sm` → `px-3 py-1.5 text-sm`
- `.btn-md` → `px-5 py-2.5 text-base` (varsayılan)
- `.btn-lg` → `px-7 py-3.5 text-lg`

### Hover/Active
- Hover: tonu 1 ton koyu, hafif `translate-y-[-1px]` + shadow artışı.
- Active: `scale-[0.98]`.
- Disabled: `opacity-50 cursor-not-allowed`.

---

## 8. Kart (Card) Sistemi

```html
<article class="card group">
  <div class="card-media"><img …></div>
  <div class="card-body">
    <h3 class="card-title">…</h3>
    <p class="card-text">…</p>
    <div class="card-footer">…</div>
  </div>
</article>
```
- `.card` → `bg-white rounded-2xl shadow-md hover:shadow-lg transition border border-slate-100 overflow-hidden`
- `.card-media` → `aspect-video bg-slate-100`
- `.card-body` → `p-5 lg:p-6`
- `.card-title` → `text-lg lg:text-xl font-bold text-brand-900`
- `.card-text` → `mt-2 text-sm text-slate-600 line-clamp-2`
- Hover'da görsel `group-hover:scale-105` ve gölge artışı.

---

## 9. Form / Input

- Input: `w-full px-4 py-3 rounded-lg border border-slate-200 bg-white focus:border-brand-500 focus:ring-2 focus:ring-brand-200 focus:outline-none`
- Label: `block text-sm font-medium text-slate-700 mb-1.5`
- Yardım metni: `mt-1.5 text-xs text-slate-500`
- Hata: `mt-1.5 text-xs text-red-600` + input `border-red-400`
- Checkbox/Radio: özel görünüm — `h-5 w-5 rounded border-slate-300 text-brand-600 focus:ring-brand-300`
- Select: `appearance-none` + chevron lucide ikon

---

## 10. Animasyon İlkeleri

- **Hız**: 150–250 ms (mikro), 300–500 ms (entrance), 600–800 ms (hero parallax).
- **Easing**: `cubic-bezier(0.22, 1, 0.36, 1)` (ease-out-expo gibi).
- **Yumuşak yükselme**: `translateY(8px) → 0` + opacity `0 → 1` (intersection observer ile tetiklenir).
- **Reduced motion**: `prefers-reduced-motion: reduce` aktifse tüm animasyonlar `transition: none`.

---

## 11. Tailwind v4 Tema Yapılandırması

Tailwind v4'te `tailwind.config.js` yok; `resources/css/app.css` içinde:

```css
@import 'tailwindcss';

@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
  /* Renkler */
  --color-brand-50:  #EFF2FA;
  --color-brand-100: #D5DDEF;
  --color-brand-300: #7A8EC3;
  --color-brand-500: #2B448C;
  --color-brand-700: #0B295C;
  --color-brand-900: #03132C;

  --color-accent-50:  #FFF1F3;
  --color-accent-500: #D52B52;
  --color-accent-600: #AF1F45;

  --color-gold-100: #F4ECD0;
  --color-gold-500: #C09740;
  --color-gold-600: #9D7B33;

  --color-surface:     #FFFFFF;
  --color-surface-alt: #F6F8FC;

  /* Tipografi */
  --font-display: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
  --font-sans:    'Inter', ui-sans-serif, system-ui, sans-serif;
  --font-serif:   'Cormorant Garamond', ui-serif, Georgia, serif;

  /* Gölge */
  --shadow-brand: 0 12px 40px -8px rgba(14, 49, 104, 0.25);

  /* Radius */
  --radius-card: 1.25rem;   /* 20px */

  /* Container */
  --container-padding: 1rem;
}

/* Custom utilities */
@layer utilities {
  .bg-grid-soft {
    background-image: radial-gradient(circle, rgba(14,49,104,.08) 1px, transparent 1px);
    background-size: 24px 24px;
  }
  .text-balance { text-wrap: balance; }
}

@layer components {
  .btn { @apply inline-flex items-center justify-center gap-2 font-semibold rounded-lg transition-all duration-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2; }
  .btn-md { @apply px-5 py-2.5 text-base; }
  .btn-primary { @apply btn btn-md bg-brand-700 text-white hover:bg-brand-900 focus-visible:outline-brand-500; }
  .btn-accent  { @apply btn btn-md bg-accent-500 text-white hover:bg-accent-600 focus-visible:outline-accent-500; }
  .btn-gold    { @apply btn btn-md bg-gold-500 text-brand-900 hover:bg-gold-600 focus-visible:outline-gold-500; }
  .btn-outline { @apply btn btn-md border-2 border-brand-700 text-brand-700 hover:bg-brand-50; }
  .btn-ghost   { @apply btn btn-md text-brand-700 hover:bg-brand-50; }

  .card        { @apply bg-white rounded-2xl shadow-md hover:shadow-lg transition border border-slate-100 overflow-hidden; }
  .section     { @apply py-12 lg:py-20; }
  .container-x { @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8; }

  .badge       { @apply inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold; }
  .badge-z     { @apply badge bg-gold-100 text-gold-600; }    /* Zekat */
  .badge-sc    { @apply badge bg-emerald-100 text-emerald-700; } /* Sadaka-i Cariye */
  .badge-f     { @apply badge bg-sky-100 text-sky-700; }     /* Fitre */
}

/* Font yüklemesi */
@font-face { font-family: 'Inter'; font-display: swap; src: url('/fonts/Inter-Variable.woff2') format('woff2-variations'); }
@font-face { font-family: 'Plus Jakarta Sans'; font-display: swap; src: url('/fonts/PlusJakartaSans-Variable.woff2') format('woff2-variations'); }
```

> **Not**: Fontları `public/fonts/` altına eklemek gerekir; alternatif olarak `bunny.net` üzerinden CDN.

---

## 12. Component Kütüphanesi (Blade Components)

### Kullanım Örnekleri
```blade
<x-button variant="accent" size="lg" icon="heart" href="/donate">Bağış Yap</x-button>

<x-card>
  <x-slot:media><img src="..." /></x-slot:media>
  <x-slot:title>Gıda Yardımları</x-slot:title>
  <x-slot:body>Açıklama metni…</x-slot:body>
  <x-slot:footer><x-progress :percent="39" /></x-slot:footer>
</x-card>

<x-section title="Öne Çıkan Çağrılar" subtitle="…">
   …içerik…
</x-section>

<x-stat value="19,500+" label="kişi" caption="Gazze Sıcak Yemek Dağıtımı" />

<x-badge variant="z">Zekat</x-badge>
```

### Bileşen Listesi (`resources/views/components/`)
- `button.blade.php`
- `card.blade.php` + alt slotlar
- `section.blade.php` (başlık + içerik wrapper)
- `progress.blade.php` (yüzdeye göre bar)
- `badge.blade.php`
- `stat.blade.php`
- `input.blade.php`, `textarea.blade.php`, `select.blade.php`
- `icon.blade.php` (lucide ikonlarını render eder)
- `modal.blade.php` (Alpine x-data ile)
- `tabs.blade.php`
- `accordion.blade.php`
- `country-card.blade.php`
- `donation-progress.blade.php`
- `social-share.blade.php`
- `quick-donate-bar.blade.php`
- `topbar.blade.php`
- `header.blade.php`
- `footer.blade.php`

---

## 13. Erişilebilirlik (a11y) Kontrol Listesi

- Tüm interaktif elementler `tabindex` ile klavye gezilebilir.
- `<button>` ve `<a>` doğru kullanılır (`href` varsa link, yoksa button).
- Form etiketleri `<label for="…">` veya `aria-label`.
- Renk kontrastı en az AA: koyu metin / açık zemin **4.5:1**, büyük metin **3:1**.
- Görsellere `alt` zorunlu (dekoratifse `alt=""`).
- `prefers-reduced-motion` dinle.
- Modallarda focus trap + ESC ile kapanma.
- Skip-link: "İçeriğe atla" (sr-only, focus'ta görünür).

---

## 14. Sayfa Düzeni (Layout) Şablonu

```
[ A1 acil duyuru çubuğu ]
[ A2 topbar              ]
[ A3 ana menü (sticky)   ]
─────────────────────────
[ Sayfa içeriği          ]
─────────────────────────
[ Newsletter bandı       ]
[ A5 footer              ]
[ A4 sticky bağış çubuğu ]
[ A6 erişilebilirlik btn ]
```

---

## 15. Görsel Boyut Standartları

| Yer | Boyut | Format |
|---|---|---|
| Hero slider | 1920×1080 | WebP, <250 KB |
| Kampanya kapak | 1200×675 | WebP, <120 KB |
| Kampanya thumbnail | 600×400 | WebP, <60 KB |
| Haber kapak | 1200×675 | WebP |
| Ülke hero | 1600×600 | WebP |
| OG image | 1200×630 | PNG/JPG |
| Avatar | 200×200 | WebP |

**Tüm `<img>`** `width`, `height`, `loading="lazy"` (hero hariç), `decoding="async"` özellikleriyle yazılır. Hero görseli `fetchpriority="high"`.

---

**Son güncelleme:** 2026-05-02
