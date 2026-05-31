<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(): View
    {
        return view('pages.countries.index', [
            'active' => Country::active()->orderBy('name_tr')->get(),
            'others' => Country::where('is_active_region', false)->orderBy('name_tr')->get(),
        ]);
    }

    public function show(string $code): View
    {
        $country = Country::where('code', strtoupper($code))->firstOrFail();

        $campaigns = $country->campaigns()
            ->active()
            ->with('category')
            ->orderBy('order')
            ->limit(6)
            ->get();

        return view('pages.countries.show', compact('country', 'campaigns'));
    }
}
