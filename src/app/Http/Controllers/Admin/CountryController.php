<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(Request $request): View
    {
        $q = Country::orderBy('name_tr');
        if ($s = $request->query('q')) $q->where('name_tr', 'like', "%{$s}%");

        return view('admin.countries.index', [
            'countries' => $q->paginate(30)->withQueryString(),
            'q' => $s,
        ]);
    }

    public function create(): View
    {
        return view('admin.countries.form', ['country' => new Country(), 'mode' => 'create']);
    }

    public function store(Request $request): RedirectResponse
    {
        Country::create($this->validated($request));
        return redirect()->route('admin.countries.index')->with('success', 'Ülke eklendi.');
    }

    public function edit(Country $country): View
    {
        return view('admin.countries.form', ['country' => $country, 'mode' => 'edit']);
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $country->update($this->validated($request, $country));
        return back()->with('success', 'Ülke güncellendi.');
    }

    public function destroy(Country $country): RedirectResponse
    {
        $country->delete();
        return redirect()->route('admin.countries.index')->with('success', 'Ülke silindi.');
    }

    private function validated(Request $request, ?Country $country = null): array
    {
        return $request->validate([
            'code'             => ['required', 'string', 'size:3', Rule::unique('countries', 'code')->ignore($country?->id)],
            'name_tr'          => ['required', 'string', 'max:120'],
            'name_en'          => ['required', 'string', 'max:120'],
            'lat'              => ['nullable', 'numeric'],
            'lng'              => ['nullable', 'numeric'],
            'flag_emoji'       => ['nullable', 'string', 'max:8'],
            'description_tr'   => ['nullable', 'string', 'max:2000'],
            'is_active_region' => ['nullable', 'boolean'],
        ]);
    }
}
