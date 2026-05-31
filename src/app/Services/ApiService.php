<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiService
{
    private string $baseUrl;
    private ?string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.refik_api.base_url', 'https://api.refik.demirtassistem.com'), '/');
        $this->token   = session('api_token');
    }

    private function http(): \Illuminate\Http\Client\PendingRequest
    {
        $http = Http::timeout(10);
        if ($this->token) {
            $http = $http->withToken($this->token);
        }
        return $http;
    }

    public function get(string $endpoint, array $params = [], int $cacheTtl = 0): array
    {
        $key  = 'api_' . md5($endpoint . serialize($params));
        $call = function () use ($endpoint, $params): array {
            try {
                $response = $this->http()
                    ->get($this->baseUrl . $endpoint, $params);

                if ($response->successful()) {
                    return $response->json() ?? [];
                }

                Log::warning('ApiService GET failed', [
                    'endpoint' => $endpoint,
                    'status'   => $response->status(),
                ]);

                return [];
            } catch (\Throwable $e) {
                Log::error('ApiService GET exception', [
                    'endpoint' => $endpoint,
                    'error'    => $e->getMessage(),
                ]);

                return [];
            }
        };

        if ($cacheTtl > 0) {
            return Cache::remember($key, $cacheTtl, $call);
        }

        return $call();
    }

    /**
     * API'den obje koleksiyonu döndürür (view'da ->property erişimi için).
     * _tr alias'larını otomatik ekler (title_tr = title, name_tr = name, vb.)
     */
    public function collect(string $endpoint, array $params = [], int $cacheTtl = 0): \Illuminate\Support\Collection
    {
        $raw   = $this->get($endpoint, $params, $cacheTtl);
        $items = data_get($raw, 'results', is_array($raw) && !isset($raw['count']) ? $raw : []);
        return collect($items)->map(fn($item) => $this->toObject($item));
    }

    public function one(string $endpoint, array $params = [], int $cacheTtl = 0): ?object
    {
        $raw = $this->get($endpoint, $params, $cacheTtl);
        return $raw ? $this->toObject($raw) : null;
    }

    /**
     * Array'i stdClass'a çevirir, çok dilli _tr alias'larını ekler.
     */
    public function toObject(array $item): object
    {
        // İç içe array'leri de objeye çevir
        foreach ($item as $key => $value) {
            if (is_array($value) && array_is_list($value)) {
                $item[$key] = collect($value)->map(fn($v) => is_array($v) ? $this->toObject($v) : $v)->all();
            } elseif (is_array($value)) {
                $item[$key] = $this->toObject($value);
            }
        }

        $aliases = [
            // Çok dilli _tr alias'ları
            'title_tr'        => $item['title']            ?? ($item['name'] ?? null),
            'name_tr'         => $item['name']             ?? ($item['title'] ?? null),
            'subtitle_tr'     => $item['subtitle']         ?? ($item['short_description'] ?? null),
            'description_tr'  => $item['description']      ?? null,
            'eyebrow_tr'      => $item['eyebrow']          ?? null,
            'cta_text_tr'     => $item['button_text']      ?? ($item['cta_text'] ?? null),
            'label_tr'        => $item['title']            ?? ($item['name'] ?? null),
            // Alan adı farklılıkları
            'cta_url'         => $item['link_value']       ?? ($item['cta_url'] ?? ($item['slug'] ?? null)),
            'raised_amount'   => $item['collected_amount'] ?? ($item['raised_amount'] ?? 0),
            'goal_amount'     => $item['target_amount']    ?? ($item['goal_amount'] ?? 0),
            'progress_percent'=> $item['progress_percentage'] ?? ($item['progress_percent'] ?? 0),
            'is_emergency'    => $item['is_urgent']        ?? ($item['is_emergency'] ?? false),
            'flag_emoji'      => $item['country_flag']     ?? ($item['flag_emoji'] ?? null),
            'cover_image'     => $item['cover_image']      ?? ($item['image'] ?? null),
            'zakat_eligible'  => $item['zakat_eligible']   ?? false,
            'fitre_eligible'  => $item['fitre_eligible']   ?? false,
            'sadaka_eligible' => $item['sadaka_eligible']  ?? true,
            'short_code'      => $item['code']             ?? ($item['short_code'] ?? null),
            'keyword'         => $item['keyword']          ?? ($item['code'] ?? null),
            'published_at'    => $item['published_at']     ?? ($item['created_at'] ?? null),
            'currency'        => $item['currency']         ?? 'TRY',
        ];

        return (object) array_merge($item, $aliases);
    }

    public function post(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->http()
                ->timeout(15)
                ->post($this->baseUrl . $endpoint, $data);

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('ApiService POST exception', [
                'endpoint' => $endpoint,
                'error'    => $e->getMessage(),
            ]);

            return [];
        }
    }

    public function delete(string $endpoint): array
    {
        try {
            $response = $this->http()
                ->delete($this->baseUrl . $endpoint);

            return $response->json() ?? [];
        } catch (\Throwable $e) {
            Log::error('ApiService DELETE exception', [
                'endpoint' => $endpoint,
                'error'    => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Guest token yoksa API'den alıp session'a kaydet.
     */
    public function ensureGuestToken(): void
    {
        if (session('api_token')) {
            return;
        }

        $result = $this->post('/api/v1/auth/guest-login');

        if (!empty($result['token'])) {
            session(['api_token' => $result['token']]);
            $this->token = $result['token'];
        }
    }
}
