<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Campaign, Donation, User, Admin};
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // FIXED: Handle jika kolom status belum ada
        $stats = [
            'total_campaigns' => Campaign::count(),
            'total_donations' => Donation::count(), // Hapus where status dulu
            'total_collected' => Donation::sum('amount'), // Hapus where status dulu
            'total_users' => User::where('role', 'user')->count(),
        ];

        // FIXED: Handle recent campaigns tanpa status
        $recent_campaigns = Campaign::with('donations')
            ->withSum('donations as current_amount', 'amount')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // FIXED: Handle recent donations tanpa status
        $recent_donations = Donation::with('campaign')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_campaigns', 'recent_donations'));
    }

    // Method untuk statistik tambahan
    public function getStatistics()
    {
        // FIXED: Handle monthly donations tanpa status
        $monthly_donations = Donation::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(amount) as total')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();

        $campaign_by_category = Campaign::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        return response()->json([
            'monthly_donations' => $monthly_donations,
            'campaign_by_category' => $campaign_by_category
        ]);
    }
}