<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function __construct(private readonly ApiService $api)
    {
    }

    public function index(Request $request): View
    {
        $params = array_filter([
            'category_id' => $request->query('category_id') ?: ($request->query('category') ?: null),
            'search'      => $request->query('search') ?: ($request->query('q') ?: null),
            'ordering'    => $this->mapOrdering($request->query('sort', 'featured')),
            'page'        => $request->query('page', 1),
            'region'      => $request->query('region') ?: null,
            'eligibility' => $request->query('eligibility') ?: null,
        ], fn ($v) => $v !== null && $v !== '');

        $raw     = $this->api->get('/api/v1/donations/projects/', $params);
        $items   = collect(data_get($raw, 'results', []))->map(fn($i) => $this->api->toObject($i));
        $total   = data_get($raw, 'count', $items->count());
        $perPage = 12;
        $page    = (int) $request->query('page', 1);

        $campaigns = new LengthAwarePaginator(
            $items, $total, $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        $categories = $this->api->collect('/api/v1/donations/categories/', [], 600);

        $slug        = $request->query('category');
        $region      = $request->query('region');
        $eligibility = $request->query('eligibility');

        return view('pages.campaigns.index', [
            'campaigns'         => $campaigns,
            'categories'        => $categories,
            'activeCategory'    => $slug,
            'activeRegion'      => $region,
            'activeEligibility' => $eligibility,
            'sort'              => $request->query('sort', 'featured'),
        ]);
    }

    public function show(string $slug): View
    {
        $campaignData = $this->api->get("/api/v1/donations/projects/{$slug}/");
        $campaign     = $campaignData ? (object) $campaignData : null;

        abort_if(! $campaign, 404);

        $categoryId = $campaign->category['id'] ?? null;
        $similar    = collect();
        if ($categoryId) {
            $similar = $this->api->collect('/api/v1/donations/projects/', [
                'category_id' => $categoryId, 'limit' => 4,
            ])->reject(fn($item) => $item->slug === $slug)->take(3);
        }

        return view('pages.campaigns.show', compact('campaign', 'similar'));
    }

    private function mapOrdering(string $sort): string
    {
        return match ($sort) {
            'most-donated' => '-raised_amount',
            'ending-soon'  => 'end_date',
            'newest'       => '-created_at',
            default        => '-is_featured',
        };
    }
}
