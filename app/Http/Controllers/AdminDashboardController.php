<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Campaign, Donation, User};
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        // Pastikan hanya admin yang bisa akses
        $this->middleware(['auth', 'verified', 'role.check:admin']);
    }

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
            'total_campaigns'  => (int) Campaign::count(),
            'total_donations'  => (int) Donation::count(),
            'total_collected'  => (float) Donation::sum('amount'),
            'total_users'      => (int) User::where('role', 'user')->count(),
        ];
    }

    /**
     * Ambil data campaign terbaru.
     */
    protected function getRecentCampaigns()
    {
        // Gunakan withSum untuk menghindari eager loading semua donasi jika tidak diperlukan
        return Campaign::withSum('donations', 'amount')
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
        try {
            $year = now()->year;

            $monthly_donations = Donation::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('SUM(amount) as total')
                )
                ->whereYear('created_at', $year)
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
        } catch (\Throwable $e) {
            // log error jika perlu: \Log::error($e);
            return response()->json([
                'error' => 'Gagal mengambil data statistik'
            ], 500);
        }
    }
}
