<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Donation;
use App\Models\Campaign;
use App\Services\MidtransService;
use Midtrans\Transaction;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar dengan relasi
        $query = Donation::with(['user', 'campaign']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Filter berdasarkan campaign
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter pencarian berdasarkan nama atau email donatur
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Ambil data dengan pagination
        $donations = $query->latest()->paginate(10);
        
        // Ambil semua campaign untuk dropdown filter
        $campaigns = Campaign::orderBy('title')->get();

        // Hitung statistik
        $stats = [
            'total_donations' => Donation::where('payment_status', 'success')->sum('amount'),
            'success_donations' => Donation::where('payment_status', 'success')->count(),
            'pending_donations' => Donation::where('payment_status', 'pending')->count(),
            'today_donations' => Donation::whereDate('created_at', today())
                                       ->where('payment_status', 'success')
                                       ->sum('amount'),
        ];

        return view('admin.donation.index', compact('donations', 'campaigns', 'stats'));
    }

    public function create(Campaign $campaign)
    {
        // Pastikan campaign aktif
        if (!$campaign->is_active || $campaign->status !== 'active') {
            return redirect()->route('campaign.show', $campaign->id)
                           ->with('error', 'Campaign ini tidak aktif untuk donasi.');
        }

        return view('donation.create', compact('campaign'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'amount' => 'required|numeric|min:10000',
            'message' => 'nullable|string|max:1000',
            'is_anonymous' => 'nullable|boolean',
        ]);

        // Pastikan user sudah login
        if (!auth()->check()) {
            session()->flash('donation_form', $request->all());
            return redirect()->route('login')->with('message', 'Silakan login untuk melanjutkan donasi.');
        }

        // Buat donation
        $donation = Donation::create([
            'campaign_id'       => $request->campaign_id,
            'user_id'           => auth()->id(),
            'amount'            => $request->amount,
            'message'           => $request->message,
            'is_anonymous'      => $request->boolean('is_anonymous'),
            'payment_status'    => 'pending',
            'midtrans_order_id' => 'DONATE-' . strtoupper(Str::random(10)),
        ]);

        return redirect()->route('donation.payment', $donation->id);
    }

    // Method untuk export CSV
    public function export(Request $request)
    {
        $query = Donation::with(['user', 'campaign']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $donations = $query->latest()->get();

        $filename = 'donations-' . date('Y-m-d-H-i-s') . '.csv';
        
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=' . $filename,
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($donations) {
            $file = fopen('php://output', 'w');
            
            // Header CSV
            fputcsv($file, ['No', 'Campaign', 'Donatur', 'Email', 'Jumlah', 'Status', 'Tanggal']);

            // Data rows
            foreach ($donations as $index => $donation) {
                fputcsv($file, [
                    $index + 1,
                    $donation->campaign->title ?? '-',
                    $donation->is_anonymous ? 'Anonim' : ($donation->user->name ?? '-'),
                    $donation->user->email ?? '-',
                    $donation->amount,
                    $donation->payment_status,
                    $donation->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Method untuk update status payment
    public function updatePaymentStatus(Request $request)
    {
        $orderId = $request->order_id;
        $donation = Donation::where('midtrans_order_id', $orderId)->first();

        if (!$donation) {
            return response()->json(['status' => 'error', 'message' => 'Donation not found']);
        }

        $transactionStatus = $request->transaction_status;
        
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $donation->update([
                    'payment_status' => 'success',
                    'paid_at' => now()
                ]);
                
                // Update campaign collected amount
                $donation->campaign->increment('collected_amount', $donation->amount);
                break;
                
            case 'pending':
                $donation->update(['payment_status' => 'pending']);
                break;
                
            case 'deny':
            case 'expire':
            case 'cancel':
                $donation->update(['payment_status' => 'failed']);
                break;
        }

        return response()->json(['status' => 'success']);
    }
}