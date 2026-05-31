<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\HelpRequest;
use App\Models\NewsletterSubscriber;
use App\Models\Volunteer;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function show(): View
    {
        $now = now();
        $sevenDaysAgo = $now->copy()->subDays(7);

        $totalRaised = (float) Donation::completed()->sum('amount_try');
        $weeklyRaised = (float) Donation::completed()->where('completed_at', '>=', $sevenDaysAgo)->sum('amount_try');
        $totalDonations = Donation::completed()->count();
        $weeklyDonations = Donation::completed()->where('completed_at', '>=', $sevenDaysAgo)->count();

        // Son 7 gün günlük toplam (basit chart için)
        $daily = collect(range(6, 0))->map(function ($i) use ($now) {
            $day = $now->copy()->subDays($i)->startOfDay();
            $next = $day->copy()->endOfDay();
            return [
                'date'  => $day->format('d M'),
                'total' => (float) Donation::completed()->whereBetween('completed_at', [$day, $next])->sum('amount_try'),
                'count' => Donation::completed()->whereBetween('completed_at', [$day, $next])->count(),
            ];
        });

        $recentDonations = Donation::with('campaign')
            ->latest('created_at')
            ->limit(8)
            ->get();

        $topCampaigns = Campaign::active()
            ->orderByDesc('raised_amount')
            ->limit(5)
            ->get();

        $pendingItems = [
            'contact_messages'      => ContactMessage::where('is_read', false)->count(),
            'volunteer_applications'=> Volunteer::where('status', 'pending')->count(),
            'help_requests'         => HelpRequest::where('status', 'pending')->count(),
            'appointments'          => Appointment::where('status', 'pending')->where('date', '>=', $now->toDateString())->count(),
            'newsletter_subs'       => NewsletterSubscriber::active()->count(),
        ];

        return view('admin.dashboard', compact(
            'totalRaised', 'weeklyRaised', 'totalDonations', 'weeklyDonations',
            'daily', 'recentDonations', 'topCampaigns', 'pendingItems',
        ));
    }
}
