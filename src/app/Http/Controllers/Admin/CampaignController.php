<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(Request $request): View
    {
        $q = Campaign::with(['category', 'country'])->latest('id');

        if ($search = $request->query('q')) {
            $q->where('title_tr', 'like', "%{$search}%");
        }
        if ($status = $request->query('status')) {
            $q->where('is_active', $status === 'active');
        }
        if ($cat = $request->query('category')) {
            $q->whereHas('category', fn ($qq) => $qq->where('slug', $cat));
        }

        return view('admin.campaigns.index', [
            'campaigns' => $q->paginate(15)->withQueryString(),
            'categories'=> CampaignCategory::orderBy('order')->get(),
            'q'         => $search,
            'status'    => $status,
            'category'  => $cat,
        ]);
    }

    public function create(): View
    {
        return view('admin.campaigns.form', [
            'campaign'   => new Campaign(['currency' => 'TRY', 'is_active' => true, 'order' => 0]),
            'categories' => CampaignCategory::orderBy('order')->get(),
            'countries'  => Country::orderBy('name_tr')->get(),
            'mode'       => 'create',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $campaign = Campaign::create($data);

        return redirect()->route('admin.campaigns.edit', $campaign)
            ->with('success', 'Kampanya oluşturuldu.');
    }

    public function edit(Campaign $campaign): View
    {
        return view('admin.campaigns.form', [
            'campaign'   => $campaign,
            'categories' => CampaignCategory::orderBy('order')->get(),
            'countries'  => Country::orderBy('name_tr')->get(),
            'mode'       => 'edit',
        ]);
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $campaign->update($this->validated($request));

        return back()->with('success', 'Kampanya güncellendi.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();
        return redirect()->route('admin.campaigns.index')->with('success', 'Kampanya silindi.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title_tr'        => ['required', 'string', 'max:255'],
            'title_en'        => ['nullable', 'string', 'max:255'],
            'subtitle_tr'     => ['nullable', 'string', 'max:500'],
            'description_tr'  => ['required', 'string'],
            'category_id'     => ['required', 'exists:campaign_categories,id'],
            'country_id'      => ['nullable', 'exists:countries,id'],
            'cover_image'     => ['required', 'string', 'max:500'],
            'goal_amount'     => ['nullable', 'numeric', 'min:0'],
            'raised_amount'   => ['nullable', 'numeric', 'min:0'],
            'currency'        => ['required', 'in:TRY,USD,EUR'],
            'donor_count'     => ['nullable', 'integer', 'min:0'],
            'zakat_eligible'  => ['nullable', 'boolean'],
            'sadaka_eligible' => ['nullable', 'boolean'],
            'fitre_eligible'  => ['nullable', 'boolean'],
            'kurban_eligible' => ['nullable', 'boolean'],
            'is_featured'     => ['nullable', 'boolean'],
            'is_emergency'    => ['nullable', 'boolean'],
            'is_active'       => ['nullable', 'boolean'],
            'start_date'      => ['nullable', 'date'],
            'end_date'        => ['nullable', 'date', 'after_or_equal:start_date'],
            'order'           => ['nullable', 'integer'],
        ]);
    }
}
