<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\AuditReport;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\View\View;

class ImpactController extends Controller
{
    public function show(): View
    {
        return view('pages.impact', [
            'reports'    => AuditReport::published()->orderByDesc('year')->get(),
            'totalRaised'=> (float) Campaign::sum('raised_amount'),
            'totalDonors'=> (int)  Campaign::sum('donor_count'),
            'totalCampaigns' => Campaign::active()->count(),
            'completedDonations' => Donation::completed()->count(),
        ]);
    }
}
