<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class HomeController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::active()
            ->with(['donations' => function ($q) {
                $q->success(); // ganti paid() jadi success()
            }])
            ->latest()
            ->get();

        return view('home', compact('campaigns'));
    }

    public function showCampaign($id)
    {
        $campaign = Campaign::with(['donations' => function ($q) {
            $q->success()->latest(); // ganti paid() jadi success()
        }])->findOrFail($id);

        $recentDonors = $campaign->donations()
            ->success() // ganti paid() jadi success()
            ->latest()
            ->limit(10)
            ->get();

        return view('campaign.detail', compact('campaign', 'recentDonors'));
    }
}
