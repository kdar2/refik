<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use IntlDateFormatter;

/**
 * Hicri (Umm al-Qura) tarihi döner.
 *
 * Kaynak sırası:
 *   1) Aladhan/Diyanet API (PrayerTimeService önbelleği) — en doğru, Türkiye uyumlu.
 *   2) PHP `intl` eklentisi (IntlDateFormatter, calendar=islamic-umalqura).
 *   3) Saf-PHP yaklaşım algoritması — eklenti yoksa son çare.
 */
class HijriDateService
{
    /** Türkçe Hicri ay adları — Diyanet kullanımı. */
    private const TR_MONTHS = [
        1  => 'Muharrem',
        2  => 'Safer',
        3  => 'Rebiülevvel',
        4  => 'Rebiülahir',
        5  => 'Cemaziyelevvel',
        6  => 'Cemaziyelahir',
        7  => 'Recep',
        8  => 'Şaban',
        9  => 'Ramazan',
        10 => 'Şevval',
        11 => 'Zilkade',
        12 => 'Zilhicce',
    ];

    public function __construct(
        private readonly string $timezone = 'Europe/Istanbul',
        private readonly ?PrayerTimeService $prayer = null,
    ) {
    }

    /**
     * "20 Zilkade 1447" gibi tek satırlık formatlanmış Hicri tarih.
     * Hesaplama başarısız olursa boş string döner; layout çökmemelidir.
     */
    public function formatted(?CarbonImmutable $when = null): string
    {
        try {
            $parts = $this->parts($when);
        } catch (\Throwable) {
            return '';
        }

        $month = self::TR_MONTHS[$parts['month']] ?? '';
        if ($month === '' || $parts['day'] < 1 || $parts['year'] < 1) {
            return '';
        }

        return trim(sprintf('%d %s %d', $parts['day'], $month, $parts['year']));
    }

    /**
     * @return array{day:int,month:int,year:int}
     */
    public function parts(?CarbonImmutable $when = null): array
    {
        $when ??= CarbonImmutable::now($this->timezone);

        // 1) Aladhan/Diyanet (PrayerTimeService önbelleğinden okur, ek API çağrısı yok).
        $hijri = $this->prayer?->hijri($when);
        if (is_array($hijri) && isset($hijri['day'], $hijri['month'], $hijri['year'])) {
            $month = is_array($hijri['month']) ? ($hijri['month']['number'] ?? null) : $hijri['month'];
            if ($month !== null) {
                return [
                    'day'   => (int) $hijri['day'],
                    'month' => (int) $month,
                    'year'  => (int) $hijri['year'],
                ];
            }
        }

        // 2) PHP intl eklentisi.
        if (class_exists(IntlDateFormatter::class)) {
            $fmt = IntlDateFormatter::create(
                'tr_TR@calendar=islamic-umalqura',
                IntlDateFormatter::NONE,
                IntlDateFormatter::NONE,
                $this->timezone,
                IntlDateFormatter::TRADITIONAL,
                'd|M|y',
            );

            if ($fmt) {
                $raw = $fmt->format($when->getTimestamp());
                if ($raw && substr_count($raw, '|') === 2) {
                    [$d, $m, $y] = explode('|', $raw);
                    return [
                        'day'   => (int) $d,
                        'month' => (int) $m,
                        'year'  => (int) $y,
                    ];
                }
            }
        }

        // 3) Saf-PHP yaklaşım — son çare.
        return $this->approximate($when);
    }

    /**
     * Intl yoksa kullanılan basit Hicri yaklaşımı.
     * @return array{day:int,month:int,year:int}
     */
    private function approximate(CarbonImmutable $when): array
    {
        $jd = $this->gregorianToJulianDay($when->month, $when->day, $when->year);
        $l  = $jd - 1948440 + 10632;
        $n  = (int) (($l - 1) / 10631);
        $l  = $l - 10631 * $n + 354;
        $j  = (int) ((10985 - $l) / 5316) * (int) ((50 * $l) / 17719) + (int) ($l / 5670) * (int) ((43 * $l) / 15238);
        $l  = $l - (int) ((30 - $j) / 15) * (int) ((17719 * $j) / 50) - (int) ($j / 16) * (int) ((15238 * $j) / 43) + 29;
        $month = (int) ((24 * $l) / 709);
        $day   = $l - (int) ((709 * $month) / 24);
        $year  = 30 * $n + $j - 30;

        return ['day' => $day, 'month' => $month, 'year' => $year];
    }

    /**
     * Pure-PHP Gregorian → Julian Day Number.
     * `calendar` PHP eklentisi (gregoriantojd) yoksa yedek olarak kullanılır.
     */
    private function gregorianToJulianDay(int $month, int $day, int $year): int
    {
        $a = intdiv(14 - $month, 12);
        $y = $year + 4800 - $a;
        $m = $month + 12 * $a - 3;

        return $day
            + intdiv(153 * $m + 2, 5)
            + 365 * $y
            + intdiv($y, 4)
            - intdiv($y, 100)
            + intdiv($y, 400)
            - 32045;
    }
}
