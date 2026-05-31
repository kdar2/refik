# Doküman İndeksi

Bu dizin proje dökümantasyonunu içerir. **Claude Code, her yeni feature başlamadan ilgili dosyayı okumalıdır.**

| # | Dosya | Açıklama | Ne Zaman Oku? |
|---|---|---|---|
| 0 | [`../CLAUDE.md`](../CLAUDE.md) | Genel yönerge, kurallar, klasör yapısı | **Her oturum başında otomatik** |
| 1 | [`SPEC.md`](./SPEC.md) | Sayfa & bölüm spesifikasyonu | Yeni sayfa/bölüm geliştirilirken |
| 2 | [`DATABASE.md`](./DATABASE.md) | DB şeması, modeller, migrationlar | DB ile etkileşim öncesi |
| 3 | [`DESIGN.md`](./DESIGN.md) | Renk, tipografi, component sistemi | UI/component yazılırken |
| 4 | [`ROADMAP.md`](./ROADMAP.md) | Faz bazlı uygulama planı | Sıradaki adımı belirlerken |

---

## Önerilen Okuma Sırası (yeni gelişmeci için)

1. `CLAUDE.md` (5 dk)
2. `docs/SPEC.md` §A (Global Bileşenler) + §B (Anasayfa) (10 dk)
3. `docs/DATABASE.md` §1 + §2 (10 dk)
4. `docs/DESIGN.md` §2 + §11 + §12 (10 dk)
5. `docs/ROADMAP.md` Faz 1 (5 dk)

→ Toplam ~40 dk içinde tüm sistemi anlamış olursun.

---

## Faz Durumu (özet)

- ✅ **Faz 0** — Hazırlık: docs/ klasörü, CLAUDE.md, scaffolding tamam
- ⬜ **Faz 1** — Layout + tema (kısmen başladı)
- ⬜ **Faz 2** — Veritabanı & modeller
- ⬜ **Faz 3** — Anasayfa bölümleri
- ⬜ **Faz 4** — Bağış akışı & ödeme
- ⬜ **Faz 5** — Admin & içerik sayfaları
- ⬜ **Faz 6** — Test, optimizasyon, deploy

Detay: [ROADMAP.md](./ROADMAP.md)
