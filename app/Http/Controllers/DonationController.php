<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Donation;
use App\Models\Campaign;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;
use Midtrans\Transaction;

class DonationController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;

        // Proteksi hanya untuk route yang butuh login & verifikasi email
        $this->middleware(['auth', 'verified'])->only([
            'payment', 'success'
        ]);
    }



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


    /**
     * Show the form for creating a new donation for a specific campaign.
     * Method ini dipanggil oleh route 'donation.create'.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
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
            'amount' => 'required|numeric|min:10000',
            'comment' => 'nullable|string|max:500',
            'is_anonymous' => 'nullable|boolean'
        ]);

        $user = auth()->user();
        $orderId = 'DON-' . time() . '-' . strtoupper(Str::random(5));

        $donation = Donation::create([
            'user_id' => $user->id,
            'campaign_id' => $request->campaign_id,
            'amount' => $request->amount,
            'comment' => $request->comment,
            'is_anonymous' => $request->is_anonymous ?? false,
            'donor_name' => $user->name,
            'donor_email' => $user->email,
            'payment_status' => 'pending',
            'midtrans_order_id' => $orderId
        ]);

        return redirect()->route('donation.payment', $donation->id);
    }


    public function payment($id)
    {
        $donation = Donation::findOrFail($id);
        
        if (empty($donation->midtrans_order_id)) {
            // Generate new order ID if not exists
            $donation->midtrans_order_id = 'DON-' . time() . '-' . strtoupper(Str::random(5));
            $donation->save();
        }
        
        $params = [
            'transaction_details' => [
                'order_id' => $donation->midtrans_order_id,
                'gross_amount' => (int) $donation->amount
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'email' => $donation->donor_email
            ],
            'item_details' => [
                [
                    'id' => 'DON-' . $donation->campaign_id,
                    'price' => (int) $donation->amount,
                    'quantity' => 1,
                    'name' => 'Donasi untuk ' . $donation->campaign->title
                ]
            ],
            'enabled_payments' => [
                'credit_card', 'gopay', 'shopeepay', 
                'bank_transfer', 'echannel', 'bca_va', 
                'bni_va', 'bri_va'
            ]
        ];

        try {
            $snapToken = $this->midtrans->getSnapToken($params);
            return view('donation.payment', compact('snapToken', 'donation'));
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }

    public function success($id)
    {
        $donation = Donation::findOrFail($id);
        return view('donation.success', compact('donation'));
    }
      
    public function checkStatus($id)
    {
        $donation = Donation::findOrFail($id);
        
        try {
            $status = Transaction::status($donation->midtrans_order_id);
            
            if (in_array($status->transaction_status, ['settlement', 'capture'])) {
                $donation->payment_status = 'success';
                $donation->paid_at = now();
            } elseif (in_array($status->transaction_status, ['expire', 'cancel', 'deny'])) {
                $donation->payment_status = 'failed';
            } else {
                $donation->payment_status = 'pending';
            }

            $donation->save();

            if ($donation->wasChanged('payment_status')) {
                $campaign = $donation->campaign;
                $campaign->updateCollectedAmount();
                $campaign->refresh();
            }

            return response()->json([
                'status' => $donation->payment_status,
                'progress' => $campaign->progress_percentage ?? 0,
                'collected' => 'Rp ' . number_format($campaign->collected_amount ?? 0, 0, ',', '.'),
                'donors' => $campaign->donations()->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => $donation->payment_status
            ]);
        }
    }
    public function myDonations()
{
    $donations = Donation::with('campaign')
        ->where('user_id', auth()->id())
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('donation.history', compact('donations'));
}


}