<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class HomeController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::active()
    ->with(['donations' => function ($q) {
        $q->success();
    }])
    ->latest()
    ->get();


        return view('home', compact('campaigns'));
    }

    public function showCampaign(Campaign $campaign)
    {
        // Ambil campaign beserta donasi terakhir
        $campaign->load([
            'donations' => function ($query) {
                $query->paid()->latest();
            }
        ]);

        // Ambil 10 donatur terbaru
        $recentDonors = $campaign->donations->take(10);

        return view('campaign.detail', compact('campaign', 'recentDonors'));
    }
}