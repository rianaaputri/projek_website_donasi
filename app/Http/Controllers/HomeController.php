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
public function showCampaign($id)
{
    $campaign = Campaign::find($id);

    if (!$campaign) {
        dd('Campaign not found', $id);
    }

  

    $isActive = $campaign->status === 'active' && $campaign->is_active;
 


    // Ambil donasi terakhir (misal cuma yg paid)
    $campaign->load([
        'donations' => function ($query) {
            $query->paid()->latest();
        }
    ]);

    // Ambil 10 donatur terbaru
    $recentDonors = $campaign->donations->take(10);

    return view('campaign.detail', compact('campaign', 'recentDonors', 'isActive'));
}

}