<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class HomeController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('status', 'active')
            ->where('verification_status', 'accepted') // ✅ Hanya yang diverifikasi
            ->with(['donations' => function ($q) {
                $q->where('status', 'success'); // hanya donasi sukses
            }])
            ->latest()
            ->get();

        return view('home', compact('campaigns'));
    }

    public function showCampaign($id)
    {
        $campaign = Campaign::find($id);
        if (!$campaign) {
            abort(404, 'Campaign tidak ditemukan');
        }

        $isActive = $campaign->status === 'active' && $campaign->verification_status === 'accepted';

        $campaign->load([
            'donations' => function ($query) {
                $query->where('status', 'success')->latest();
            }
        ]);

        $recentDonors = $campaign->donations->take(10);

        return view('campaign.detail', compact('campaign', 'recentDonors', 'isActive'));
    }
}