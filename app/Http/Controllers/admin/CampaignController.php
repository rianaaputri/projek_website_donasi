<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Tambahkan middleware admin jika ada
        // $this->middleware('admin');
    }

    /**
     * Display a listing of donations
     */
    public function index(Request $request)
    {
        $query = Donation::with('campaign')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by campaign
        if ($request->has('campaign_id') && $request->campaign_id != '') {
            $query->where('campaign_id', $request->campaign_id);
        }

        // Search by donor name or email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('donor_email', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->paginate(15);
        $campaigns = Campaign::select('id', 'title')->get();

        // Statistics
        $stats = [
            'total_donations' => Donation::where('status', 'success')->sum('amount'),
            'pending_donations' => Donation::where('status', 'pending')->count(),
            'success_donations' => Donation::where('status', 'success')->count(),
            'failed_donations' => Donation::where('status', 'failed')->count(),
            'today_donations' => Donation::where('status', 'success')
                ->whereDate('created_at', today())
                ->sum('amount'),
        ];

        return view('admin.donations.index', compact('donations', 'campaigns', 'stats'));
    }

    /**
     * Display the specified donation
     */
    public function show($id)
    {
        $donation = Donation::with('campaign')->findOrFail($id);
        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Update donation status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed'
        ]);

        $donation = Donation::findOrFail($id);
        $oldStatus = $donation->status;
        $newStatus = $request->status;

        DB::beginTransaction();
        try {
            // Update donation status
            $donation->update(['status' => $newStatus]);

            // Update campaign collected amount
            $campaign = $donation->campaign;
            
            if ($oldStatus === 'success' && $newStatus !== 'success') {
                // Remove from collected amount
                $campaign->decrement('collected_amount', $donation->amount);
            } elseif ($oldStatus !== 'success' && $newStatus === 'success') {
                // Add to collected amount
                $campaign->increment('collected_amount', $donation->amount);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Status donasi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status donasi.');
        }
    }

    /**
     * Remove the specified donation from storage
     */
    public function destroy($id)
    {
        $donation = Donation::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // If donation was successful, reduce campaign collected amount
            if ($donation->status === 'success') {
                $donation->campaign->decrement('collected_amount', $donation->amount);
            }

            $donation->delete();
            DB::commit();

            return redirect()->route('admin.donations.index')
                ->with('success', 'Donasi berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus donasi.');
        }
    }

    /**
     * Export donations to CSV
     */
    public function export(Request $request)
    {
        $query = Donation::with('campaign')
            ->orderBy('created_at', 'desc');

        // Apply same filters as index
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('campaign_id') && $request->campaign_id != '') {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('donor_name', 'like', "%{$search}%")
                  ->orWhere('donor_email', 'like', "%{$search}%");
            });
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $donations = $query->get();

        $filename = 'donations_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($donations) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, [
                'ID',
                'Campaign',
                'Donor Name',
                'Donor Email',
                'Amount',
                'Status',
                'Comment',
                'Payment ID',
                'Created At'
            ]);

            // Data rows
            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->id,
                    $donation->campaign->title,
                    $donation->donor_name,
                    $donation->donor_email,
                    number_format($donation->amount, 0, ',', '.'),
                    ucfirst($donation->status),
                    $donation->comment,
                    $donation->payment_id,
                    $donation->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get donation analytics data
     */
    public function analytics()
    {
        // Monthly donation stats
        $monthlyStats = Donation::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(CASE WHEN status = "success" THEN amount ELSE 0 END) as total_amount')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Top campaigns by donations
        $topCampaigns = Campaign::select('campaigns.*')
            ->withSum(['donations as total_donations' => function($query) {
                $query->where('status', 'success');
            }], 'amount')
            ->withCount(['donations as donations_count' => function($query) {
                $query->where('status', 'success');
            }])
            ->orderBy('total_donations', 'desc')
            ->limit(10)
            ->get();

        // Recent large donations
        $largeDonations = Donation::with('campaign')
            ->where('status', 'success')
            ->where('amount', '>=', 100000)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Status distribution
        $statusStats = Donation::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return view('admin.donations.analytics', compact(
            'monthlyStats', 
            'topCampaigns', 
            'largeDonations', 
            'statusStats'
        ));
    }

    /**
     * Bulk update donation status
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'donation_ids' => 'required|array',
            'donation_ids.*' => 'exists:donations,id',
            'bulk_status' => 'required|in:pending,success,failed'
        ]);

        DB::beginTransaction();
        try {
            $donations = Donation::whereIn('id', $request->donation_ids)->get();
            
            foreach ($donations as $donation) {
                $oldStatus = $donation->status;
                $newStatus = $request->bulk_status;
                
                $donation->update(['status' => $newStatus]);
                
                // Update campaign collected amount
                $campaign = $donation->campaign;
                
                if ($oldStatus === 'success' && $newStatus !== 'success') {
                    $campaign->decrement('collected_amount', $donation->amount);
                } elseif ($oldStatus !== 'success' && $newStatus === 'success') {
                    $campaign->increment('collected_amount', $donation->amount);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 
                'Berhasil memperbarui status ' . count($request->donation_ids) . ' donasi!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui status donasi.');
        }
    }
}