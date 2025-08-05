<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Campaign, Donation, User};
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan halaman utama dashboard admin.
     */
    public function index()
    {
        $stats = $this->getDashboardStats();
        $recent_campaigns = $this->getRecentCampaigns();
        $recent_donations = $this->getRecentDonations();

        return view('admin.dashboard', compact('stats', 'recent_campaigns', 'recent_donations'));
    }

    /**
     * Ambil statistik utama dashboard.
     */
    protected function getDashboardStats()
    {
        return [
            'total_campaigns'  => Campaign::count(),
            'total_donations'  => Donation::count(),
            'total_collected'  => Donation::sum('amount'),
            'total_users'      => User::where('role', 'user')->count(),
        ];
    }

    /**
     * Ambil data campaign terbaru.
     */
    protected function getRecentCampaigns()
    {
        return Campaign::with('donations')
            ->withSum('donations as current_amount', 'amount')
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Ambil data donasi terbaru.
     */
    protected function getRecentDonations()
    {
        return Donation::with('campaign')
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Endpoint untuk ambil statistik JSON (AJAX).
     */
    public function getStatistics()
    {
        $monthly_donations = Donation::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        $campaign_by_category = Campaign::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        return response()->json([
            'monthly_donations'     => $monthly_donations,
            'campaign_by_category'  => $campaign_by_category
        ]);
    }
}
