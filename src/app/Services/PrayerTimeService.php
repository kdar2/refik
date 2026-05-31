<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Aladhan API'den günlük namaz vakitlerini ve Hicri tarihi çeker (Diyanet method=13).
 * Sonuç o gün sonuna kadar cache'lenir.
 */
class PrayerTimeService
{
    private const ENDPOINT = 'https://api.aladhan.com/v1/timingsByCity';

    /** Aladhan -> Türkçe vakit etiketleri (Diyanet sırası) */
    private const PRAYERS = [
        ['key' => 'Fajr',     'tr' => 'İmsak'],
        ['key' => 'Sunrise',  'tr' => 'Güneş'],
        ['key' => 'Dhuhr',    'tr' => 'Öğle'],
        ['key' => 'Asr',      'tr' => 'İkindi'],
        ['key' => 'Maghrib',  'tr' => 'Akşam'],
        ['key' => 'Isha',     'tr' => 'Yatsı'],
    ];

    public function __construct(
        private readonly string $city = 'Ankara',
        private readonly string $country = 'Turkey',
        private readonly string $timezone = 'Europe/Istanbul',
        private readonly int $method = 13,
    ) {
    }

    /**
     * Bir sonraki namaz vakti.
     * @return array{name:string,time:string,iso:string,is_tomorrow:bool}|null
     */
    public function next(): ?array
    {
        $now = CarbonImmutable::now($this->timezone);

        $today = $this->timings($now);
        if ($today !== null) {
            foreach (self::PRAYERS as $p) {
                $time = $this->parseTime($today[$p['key']] ?? null, $now);
                if ($time && $time->greaterThan($now)) {
                    return [
                        'name'        => $p['tr'],
                        'time'        => $time->format('H:i'),
                        'iso'         => $time->toIso8601String(),
                        'is_tomorrow' => false,
                    ];
                }
            }
        }

        // Yatsı sonrası (ya da bugün için veri yok) — yarının İmsak'ı
        $tomorrowDate = $now->addDay();
        $tomorrow     = $this->timings($tomorrowDate);
        $first        = $this->parseTime($tomorrow['Fajr'] ?? null, $tomorrowDate);
        if ($first === null) {
            return null;
        }

        return [
            'name'        => 'İmsak',
            'time'        => $first->format('H:i'),
            'iso'         => $first->toIso8601String(),
            'is_tomorrow' => true,
        ];
    }

    /** @return array<string,string>|null */
    public function timings(?CarbonImmutable $date = null): ?array
    {
        $payload = $this->fetchDay($date ?? CarbonImmutable::now($this->timezone));
        return $payload['timings'] ?? null;
    }

    /**
     * Aladhan'dan gelen Hicri tarih: ['day' => '20', 'month' => ['number' => 11, 'en' => 'Dhū al-Qaʿdah'], 'year' => '1447'].
     * @return array<string,mixed>|null
     */
    public function hijri(?CarbonImmutable $date = null): ?array
    {
        $payload = $this->fetchDay($date ?? CarbonImmutable::now($this->timezone));
        return $payload['date']['hijri'] ?? null;
    }

    /** @return array<string,mixed>|null */
    private function fetchDay(CarbonImmutable $date): ?array
    {
        $key = sprintf(
            'aladhan:%s:%s:%d:%s',
            strtolower($this->city),
            strtolower($this->country),
            $this->method,
            $date->format('Y-m-d'),
        );

        return Cache::remember($key, $date->endOfDay(), function () use ($date) {
            try {
                $response = Http::timeout(4)
                    ->retry(2, 200)
                    ->get(self::ENDPOINT . '/' . $date->format('d-m-Y'), [
                        'city'    => $this->city,
                        'country' => $this->country,
                        'method'  => $this->method,
                    ]);

                if ($response->successful()) {
                    return $response->json('data');
                }

                Log::warning('Aladhan non-2xx', [
                    'status' => $response->status(),
                    'city'   => $this->city,
                ]);
            } catch (\Throwable $e) {
                Log::warning('Aladhan failed', [
                    'error' => $e->getMessage(),
                    'city'  => $this->city,
                ]);
            }

            return null;
        });
    }

    /**
     * "12:54 (EET)" gibi cevabı Carbon'a parse eder.
     */
    private function parseTime(?string $value, CarbonImmutable $reference): ?CarbonImmutable
    {
        if (!$value) {
            return null;
        }

        $clean = trim(explode(' ', $value)[0]);
        if (!preg_match('/^\d{2}:\d{2}$/', $clean)) {
            return null;
        }

        [$h, $m] = explode(':', $clean);

        return $reference->setTime((int) $h, (int) $m, 0);
    }
}
