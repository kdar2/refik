<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly ApiService $api)
    {
    }

    public function __invoke(): View
    {
        $sliders         = $this->api->collect('/api/v1/donations/banners/');
        $categories      = $this->api->collect('/api/v1/donations/categories/', [], 600);
        $featuredCampaigns = $this->api->collect('/api/v1/donations/projects/featured/', [], 300);
        $quickDonations  = $this->api->collect('/api/v1/donations/quick-donations/', [], 600);
        $smsCodes        = $this->api->collect('/api/v1/donations/sms-codes/', [], 3600);
        $activeCountries = $this->api->collect('/api/v1/countries/', [], 3600);
        $intentions      = $this->api->collect('/api/v1/donations/intentions/', [], 3600);

        $latestPosts  = $this->api->collect('/api/v1/posts/', ['limit' => 6], 300);
        $featuredPost = $latestPosts->first(fn($p) => $p->is_featured ?? false) ?? $latestPosts->first();
        $featuredId   = $featuredPost?->id;
        $sidePosts    = $latestPosts->filter(fn($p) => $p->id !== $featuredId)->take(3);
        $thumbPosts   = $latestPosts->filter(fn($p) => $p->id !== $featuredId)->skip(3)->take(6);

        $nisab = null;

        return view('pages.home', compact(
            'sliders', 'featuredCampaigns', 'smsCodes', 'categories',
            'activeCountries', 'featuredPost', 'sidePosts', 'thumbPosts',
            'nisab', 'intentions', 'quickDonations',
        ));
    }
}
