// Refik Zekat Hesaplayıcı — saf client-side
// Hiçbir veri sunucuya gönderilmez, hiçbir input localStorage'a yazılmaz.
// Sadece "privacy-popup okundu" bilgisi localStorage'a kaydedilir.
//
// Kullanım: home.blade.php içinde [data-zakat-calculator] elementi + nisab data attribute'ları.

const STORAGE_KEY = 'zakat-privacy-acknowledged';
const TRY_FORMAT = new Intl.NumberFormat('tr-TR', {
    style: 'currency',
    currency: 'TRY',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
});
const ZAKAT_RATE = 0.025; // %2.5

function num(input) {
    const v = parseFloat(input?.value ?? '0');
    return Number.isFinite(v) && v > 0 ? v : 0;
}

function init() {
    const root = document.querySelector('[data-zakat-calculator]');
    if (!root) return;

    const goldPrice   = parseFloat(root.dataset.goldPrice)   || 4250;
    const silverPrice = parseFloat(root.dataset.silverPrice) || 49.5;
    const nisabGoldGr = parseFloat(root.dataset.nisabGold)   || 80.18;

    const form     = root.querySelector('[data-zakat-form]');
    const result   = root.querySelector('[data-zakat-result]');
    const privacy  = root.querySelector('[data-zakat-privacy]');
    const accept   = root.querySelector('[data-zakat-privacy-accept]');
    const nisabEl  = root.querySelector('[data-zakat-nisab]');
    const netEl    = root.querySelector('[data-zakat-net]');
    const amountEl = root.querySelector('[data-zakat-amount]');
    const statusEl = root.querySelector('[data-zakat-status]');
    const explainEl= root.querySelector('[data-zakat-explain]');

    // Privacy popup — ilk açılışta veya henüz onaylanmadıysa
    if (privacy && !localStorage.getItem(STORAGE_KEY)) {
        privacy.classList.remove('hidden');
    }
    accept?.addEventListener('click', () => {
        localStorage.setItem(STORAGE_KEY, '1');
        privacy?.classList.add('hidden');
    });

    // Nisap (altın eşdeğeri ₺) — bilgi olarak göster
    const nisabAmount = nisabGoldGr * goldPrice;
    if (nisabEl) nisabEl.textContent = TRY_FORMAT.format(nisabAmount);

    // Canlı net hesapla (input değiştikçe)
    const computeNet = () => {
        const cash        = num(form.querySelector('[name="cash"]'));
        const bank        = num(form.querySelector('[name="bank"]'));
        const goldGrams   = num(form.querySelector('[name="gold_grams"]'));
        const silverGrams = num(form.querySelector('[name="silver_grams"]'));
        const stocks      = num(form.querySelector('[name="stocks"]'));
        const receivables = num(form.querySelector('[name="receivables"]'));
        const debts       = num(form.querySelector('[name="debts"]'));

        const totalAssets =
            cash + bank +
            goldGrams * goldPrice +
            silverGrams * silverPrice +
            stocks + receivables;
        const net = Math.max(0, totalAssets - debts);
        if (netEl) netEl.textContent = TRY_FORMAT.format(net);
        return net;
    };

    form?.addEventListener('input', computeNet);
    computeNet();

    form?.addEventListener('submit', (e) => {
        e.preventDefault();
        const net = computeNet();
        const meetsNisab = net >= nisabAmount;
        const zakat = meetsNisab ? net * ZAKAT_RATE : 0;

        if (statusEl) {
            statusEl.textContent = meetsNisab
                ? 'Toplam zekat verilebilir varlığınız nisap miktarını aşıyor; üzerinize zekat farzdır.'
                : 'Toplam zekat verilebilir varlığınız nisap miktarının altında; üzerinize zekat farz değildir.';
        }
        if (amountEl) amountEl.textContent = TRY_FORMAT.format(Math.round(zakat));
        if (explainEl) {
            explainEl.textContent = meetsNisab
                ? `Net varlık ${TRY_FORMAT.format(net)} × %2.5 = ${TRY_FORMAT.format(Math.round(zakat))}`
                : `Nisap eşiği: ${TRY_FORMAT.format(Math.round(nisabAmount))}.`;
        }

        result?.classList.remove('hidden');
        result?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    form?.addEventListener('reset', () => {
        // Reset sonrası canlı toplam ve sonucu temizle
        setTimeout(() => {
            computeNet();
            result?.classList.add('hidden');
        }, 0);
    });
}

document.addEventListener('DOMContentLoaded', init);

export { init };
