<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function __construct(private readonly ApiService $api)
    {
    }

    public function index(): View
    {
        // Tüm ülkeler — 1 saat cache
        $raw    = $this->api->get('/api/v1/countries/', [], 3600);
        $all    = collect(data_get($raw, 'results', $raw ?: []));
        $active = $all->where('is_active_region', true)->values();
        $others = $all->where('is_active_region', false)->values();

        return view('pages.countries.index', compact('active', 'others'));
    }

    public function show(string $code): View
    {
        // Ülke detayı + o ülkedeki kampanyalar
        $raw      = $this->api->get('/api/v1/countries/', [], 3600);
        $all      = collect(data_get($raw, 'results', $raw ?: []));
        $country  = $all->first(fn ($c) => strtoupper(data_get($c, 'code', '')) === strtoupper($code));

        if (!$country) {
            abort(404);
        }

        $countryId    = data_get($country, 'id');
        $campaignsRaw = $countryId
            ? $this->api->get('/api/v1/donations/projects/', ['country_id' => $countryId, 'limit' => 6])
            : [];

        $campaigns = collect(data_get($campaignsRaw, 'results', $campaignsRaw ?: []));

        return view('pages.countries.show', compact('country', 'campaigns'));
    }
}
