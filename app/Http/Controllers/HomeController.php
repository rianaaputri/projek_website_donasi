<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class HomeController extends Controller
{
    public function index()
    {
      $campaigns = Campaign::active()
    ->with(['donations.paid'])
    ->latest()
    ->get();

        return view('home', compact('campaigns'));
    }

    public function showCampaign($id)
    {
        $campaign = Campaign::with(['donations' => function ($q) {
            $q->paid()->latest();
        }])->findOrFail($id);

        $recentDonors = $campaign->donations()
            ->paid()
            ->latest()
            ->limit(10)
            ->get();

        return view('campaign.detail', compact('campaign', 'recentDonors'));
    }
    
}
