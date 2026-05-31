// Refik Vakfı — Public Site JS
// Alpine.js + Lucide ikonları + sade modüller

import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';
import './modules/zakat-calculator.js';

window.Alpine = Alpine;

// ── Topbar: sıradaki namaz vaktine geri sayım
Alpine.data('prayerCountdown', (iso) => ({
    target: iso ? new Date(iso) : null,
    remaining: '',
    timer: null,
    start() {
        if (!this.target || isNaN(this.target.getTime())) return;
        this.tick();
        this.timer = setInterval(() => this.tick(), 1000);
    },
    tick() {
        const diff = this.target.getTime() - Date.now();
        if (diff <= 0) {
            this.remaining = '';
            clearInterval(this.timer);
            return;
        }
        const h = Math.floor(diff / 3_600_000);
        const m = Math.floor((diff % 3_600_000) / 60_000);
        const s = Math.floor((diff % 60_000) / 1_000);
        this.remaining = h > 0
            ? `${h}sa ${String(m).padStart(2, '0')}dk`
            : `${m}dk ${String(s).padStart(2, '0')}sn`;
    },
}));

// ── Lucide ikonlarını DOM'a uygula
function refreshIcons() {
    createIcons({ icons, attrs: { 'stroke-width': 1.75 } });
}

// ── "data-rise" elementleri görünür alana girince yükselsin
function initRiseObserver() {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-rise');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15 },
    );
    document.querySelectorAll('[data-rise]').forEach((el) => observer.observe(el));
}

// ── Sayaç animasyonu (counter)
function initCounters() {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const el = entry.target;
                const target = parseInt(el.dataset.counter || '0', 10);
                const duration = parseInt(el.dataset.duration || '1500', 10);
                const start = performance.now();
                const step = (now) => {
                    const p = Math.min(1, (now - start) / duration);
                    const eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = Math.floor(target * eased).toLocaleString('tr-TR');
                    if (p < 1) requestAnimationFrame(step);
                    else el.textContent = target.toLocaleString('tr-TR') + (el.dataset.suffix || '');
                };
                requestAnimationFrame(step);
                observer.unobserve(el);
            });
        },
        { threshold: 0.4 },
    );
    document.querySelectorAll('[data-counter]').forEach((el) => observer.observe(el));
}

// ── Acil duyuru çubuğu (üst) kapatma
function initAlertBar() {
    const bar = document.getElementById('alert-bar');
    if (!bar) return;
    if (localStorage.getItem('alertDismissed') === '1') {
        bar.remove();
        return;
    }
    bar.querySelector('[data-close]')?.addEventListener('click', () => {
        localStorage.setItem('alertDismissed', '1');
        bar.classList.add('opacity-0');
        setTimeout(() => bar.remove(), 250);
    });
}

// ── Scroll'a göre header arka planı yumuşat
function initHeaderScroll() {
    const header = document.getElementById('site-header');
    if (!header) return;
    const onScroll = () => {
        const isScrolled = window.scrollY > 8;
        header.classList.toggle('shadow-md', isScrolled);
        header.classList.toggle('bg-white/95', isScrolled);
        header.classList.toggle('backdrop-blur', isScrolled);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
}

document.addEventListener('DOMContentLoaded', () => {
    refreshIcons();
    initRiseObserver();
    initCounters();
    initAlertBar();
    initHeaderScroll();
});

Alpine.start();
