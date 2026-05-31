<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(Request $request): View
    {
        $q = Campaign::active()
            ->with(['category', 'country'])
            ->orderBy('order')
            ->latest();

        // Kategori filtresi (slug üzerinden)
        if ($slug = $request->query('category')) {
            $q->whereHas('category', fn ($qq) => $qq->where('slug', $slug));
        }

        // Bölge: yurtici / yurtdisi (TUR ise yurtici, diğerleri yurtdisi)
        if ($region = $request->query('region')) {
            $q->whereHas('country', function ($qq) use ($region) {
                $region === 'yurtici'
                    ? $qq->where('code', 'TUR')
                    : $qq->where('code', '<>', 'TUR');
            });
        }

        // Bağış uygunluğu: zakat | sadaka | fitre | kurban
        $eligibility = $request->query('eligibility');
        if (in_array($eligibility, ['zakat', 'sadaka', 'fitre', 'kurban'], true)) {
            $q->where("{$eligibility}_eligible", true);
        }

        // Sıralama: newest | most-donated | ending-soon
        switch ($request->query('sort', 'featured')) {
            case 'most-donated':
                $q->reorder('raised_amount', 'desc');
                break;
            case 'ending-soon':
                $q->reorder('end_date', 'asc');
                break;
            case 'newest':
                $q->reorder('created_at', 'desc');
                break;
            case 'featured':
            default:
                $q->reorder('is_featured', 'desc')->orderBy('order');
                break;
        }

        $campaigns = $q->paginate(12)->withQueryString();

        $categories = CampaignCategory::active()->orderBy('order')->get();

        return view('pages.campaigns.index', [
            'campaigns'      => $campaigns,
            'categories'     => $categories,
            'activeCategory' => $slug,
            'activeRegion'   => $region,
            'activeEligibility' => $eligibility,
            'sort'           => $request->query('sort', 'featured'),
        ]);
    }

    public function show(string $slug): View
    {
        $campaign = Campaign::active()
            ->with(['category', 'country'])
            ->where('slug', $slug)
            ->firstOrFail();

        $similar = Campaign::active()
            ->where('id', '<>', $campaign->id)
            ->where('category_id', $campaign->category_id)
            ->limit(3)
            ->get();

        return view('pages.campaigns.show', compact('campaign', 'similar'));
    }
}
