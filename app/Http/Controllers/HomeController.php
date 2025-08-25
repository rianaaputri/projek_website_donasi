<?php

namespace App\Http\Controllers;

use App\Models\Campaign;

class HomeController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::where('status', 'active')
            ->where('verification_status', 'approved') // ✅ hanya campaign yang sudah disetujui admin
            ->with(['donations' => function ($q) {
                $q->where('payment_status', 'success'); // ✅ hanya donasi yang sukses
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

        // ✅ cek aktif & sudah disetujui
        $isActive = $campaign->status === 'active' && $campaign->verification_status === 'approved';

        // ✅ load donasi sukses terbaru
        $campaign->load([
            'donations' => function ($query) {
                $query->where('payment_status', 'success')->latest();
            }
        ]);

        // ✅ ambil max 10 donatur terbaru
        $recentDonors = $campaign->donations->take(10);

        return view('campaign.detail', compact('campaign', 'recentDonors', 'isActive'));
    }
}