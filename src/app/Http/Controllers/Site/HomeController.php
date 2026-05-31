<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Country;
use App\Models\DonationIntention;
use App\Models\Post;
use App\Models\Slider;
use App\Models\SmsDonationCode;
use App\Models\ZekatNisabSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        // ID listelerini cache'liyoruz (raw int dizisi → unserialize sorunu yok),
        // sonra modelleri yeniden çekiyoruz. Eğer sayı azsa hızlı, çoksa hâlâ cache'in faydasını alıyoruz.

        $sliderIds = Cache::remember('home:slider-ids', 600,
            fn () => Slider::active()->ordered()->pluck('id')->all(),
        );
        $sliders = Slider::whereIn('id', $sliderIds)->orderBy('order')->get();

        $featuredIds = Cache::remember('home:featured-ids', 600,
            fn () => Campaign::active()->featured()->ordered()->limit(8)->pluck('id')->all(),
        );
        $featuredCampaigns = Campaign::whereIn('id', $featuredIds)
            ->with(['category', 'country'])
            ->orderBy('order')->orderByDesc('id')
            ->get();

        $smsCodeIds = Cache::remember('home:sms-code-ids', 3600,
            fn () => SmsDonationCode::active()->ordered()->pluck('id')->all(),
        );
        $smsCodes = SmsDonationCode::whereIn('id', $smsCodeIds)->orderBy('order')->get();

        $categoryIds = Cache::remember('home:category-ids', 3600,
            fn () => CampaignCategory::active()->orderBy('order')->pluck('id')->all(),
        );
        $categories = CampaignCategory::whereIn('id', $categoryIds)->orderBy('order')->get();

        $activeCountryIds = Cache::remember('home:active-country-ids', 3600,
            fn () => Country::active()->orderBy('name_tr')->pluck('id')->all(),
        );
        $activeCountries = Country::whereIn('id', $activeCountryIds)->orderBy('name_tr')->get();

        // Posts taze listelendiği için cache'siz (anasayfada zaten 10 satır)
        $latestPosts  = Post::published()->with('category')->latest('published_at')->limit(10)->get();
        $featuredPost = $latestPosts->where('is_featured', true)->first() ?? $latestPosts->first();
        $sidePosts    = $latestPosts->where('id', '!=', $featuredPost?->id)->take(3);
        $thumbPosts   = $latestPosts->where('id', '!=', $featuredPost?->id)->skip(3)->take(6);

        // Nisab + intentions — küçük & değişmez veriler, ID cache yeterli
        $nisabId = Cache::remember('home:latest-nisab-id', 3600,
            fn () => ZekatNisabSetting::orderByDesc('updated_for_date')->value('id'),
        );
        $nisab      = $nisabId ? ZekatNisabSetting::find($nisabId) : null;
        $intentions = DonationIntention::active()->ordered()->get();

        return view('pages.home', compact(
            'sliders',
            'featuredCampaigns',
            'smsCodes',
            'categories',
            'activeCountries',
            'featuredPost',
            'sidePosts',
            'thumbPosts',
            'nisab',
            'intentions',
        ));
    }
}
