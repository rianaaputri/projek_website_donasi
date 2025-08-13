<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Donation;
use App\Models\Campaign; // Pastikan ini di-import
use App\Services\MidtransService;
use Midtrans\Transaction; // Pastikan ini di-import jika digunakan

class DonationController extends Controller
{




public function index()
{
    $donations = Donation::latest()->paginate(10); // atau ->get() kalau tidak pakai pagination

    $campaigns = Campaign::all(); // kalau bagian filter butuh data campaign

    $stats = [
        'total_donations' => Donation::sum('amount'),
        'success_donations' => 0,
        'pending_donations' => 0,
        'today_donations' => Donation::whereDate('created_at', today())->sum('amount'),
    ];

    return view('admin.donation.index', compact('donations', 'campaigns', 'stats'));
}

    public function create(Campaign $campaign) // <-- Pastikan method ini ada dan namanya 'create'
    {
        // Pastikan campaign aktif sebelum menampilkan form donasi
        if (!$campaign->is_active || $campaign->status !== 'active') {
            return redirect()->route('campaign.show', $campaign->id)
                             ->with('error', 'Campaign ini tidak aktif untuk donasi.');
        }

        // Memuat view 'donation.form' yang Anda miliki di Canvas
        return view('donation.create', compact('campaign'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email',
            'donor_phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:10000',
            'comment' => 'nullable|string|max:1000',
            'is_anonymous' => 'nullable|boolean', // Validasi untuk checkbox
        ]);

       $donation = Donation::create([
    'campaign_id' => $request->campaign_id,
    'donor_name' => $request->donor_name,
    'donor_email' => $request->donor_email,
    'donor_phone' => $request->donor_phone,
    'amount' => $request->amount,
    'message' => $request->comment, // ✅ GUNAKAN KOLOM YANG ADA
    'is_anonymous' => $request->has('is_anonymous') ? true : false,
    'payment_status' => 'pending',
    'midtrans_order_id' => 'DONATE-' . strtoupper(Str::random(10)),
]);

        $isAnonymous = $request->has('is_anonymous');

        $donation = Donation::create([
            'campaign_id'       => $request->campaign_id,
            'donor_name'        => $isAnonymous ? 'Seseorang' : $request->donor_name,
            'donor_email'       => $request->donor_email,
            'donor_phone'       => $request->donor_phone,
            'amount'            => $request->amount,
            'comment'           => $request->comment,
            'is_anonymous'      => $isAnonymous,
            'payment_status'    => 'pending',
            'midtrans_order_id' => 'DONATE-' . strtoupper(Str::random(10)),
        ]);

        // Simpan ID donasi di session untuk redirect setelah login
        session(['pending_donation_id' => $donation->id]);

        // Jika belum login, redirect ke login
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Silakan login untuk melanjutkan pembayaran.');
        }

        // Jika sudah login, redirect langsung ke payment
        return redirect()->route('donation.payment', $donation->id);
    }


}