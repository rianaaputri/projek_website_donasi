<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Donation;
use App\Models\Campaign;
use App\Services\MidtransService;
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
        $donations = Donation::latest()->paginate(10);
        $campaigns = Campaign::all();

        $stats = [
            'total_donations'    => Donation::sum('amount'),
            'success_donations'  => Donation::where('payment_status', 'success')->sum('amount'),
            'pending_donations'  => Donation::where('payment_status', 'pending')->sum('amount'),
            'today_donations'    => Donation::whereDate('created_at', today())->sum('amount'),
        ];

        return view('admin.donation.index', compact('donations', 'campaigns', 'stats'));
    }

    public function create(Campaign $campaign)
    {
        if (!$campaign->is_active || $campaign->status !== 'active') {
            return redirect()->route('campaign.show', $campaign->id)
                             ->with('error', 'Campaign ini tidak aktif untuk donasi.');
        }

        return view('donation.create', compact('campaign'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'campaign_id'   => 'required|exists:campaigns,id',
            'donor_name'    => 'nullable|string|max:255',
            'donor_email'   => 'required|email',
            'donor_phone'   => 'nullable|string|max:20',
            'amount'        => 'required|numeric|min:10000',
            'comment'       => 'nullable|string|max:1000',
            'is_anonymous'  => 'nullable|boolean',
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

    public function payment($id)
    {
        $donation = Donation::with('campaign')->findOrFail($id);

        $params = [
            'transaction_details' => [
                'order_id'     => $donation->midtrans_order_id,
                'gross_amount' => $donation->amount,
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'email'      => $donation->donor_email,
                'phone'      => $donation->donor_phone,
            ],
            'item_details' => [
                [
                    'id'       => $donation->campaign_id,
                    'price'    => $donation->amount,
                    'quantity' => 1,
                    'name'     => 'Donasi untuk ' . $donation->campaign->title,
                ]
            ]
        ];

        $snapToken = $this->midtrans->getSnapToken($params);

        return view('donation.payment', compact('snapToken', 'donation'));
    }

    public function success($id)
    {
        $donation = Donation::findOrFail($id);
        return view('donation.success', compact('donation'));
    }

    public function checkStatus($id)
    {
        $donation = Donation::with('campaign')->findOrFail($id);

        try {
            $status = Transaction::status($donation->midtrans_order_id);

            if (in_array($status->transaction_status, ['settlement', 'capture'])) {
                $donation->payment_status = 'success';
            } elseif (in_array($status->transaction_status, ['expire', 'cancel', 'deny'])) {
                $donation->payment_status = 'failed';
            } else {
                $donation->payment_status = 'pending';
            }

            $donation->save();
            $donation->campaign->updateCollectedAmount();
        } catch (\Exception $e) {
            \Log::error('Midtrans status check failed: ' . $e->getMessage());
        }

        return response()->json([
            'status'    => $donation->payment_status,
            'progress'  => $donation->campaign->progress_percentage,
            'collected' => $donation->campaign->formatted_collected,
            'donors'    => $donation->campaign->donations()->count()
        ]);
    }

    public function adminIndex(Request $request)
    {
        $query = Donation::with(['campaign', 'user'])->latest();

        if ($request->status) {
            $query->where('payment_status', $request->status);
        }

        if ($request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }

        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->search) {
            $query->where('donor_name', 'like', "%{$request->search}%");
        }

        $donations = $query->paginate(10)->withQueryString();
        $campaigns = Campaign::all();

        return view('admin.donations.index', compact('donations', 'campaigns'));
    }

    public function adminShow(Donation $donation)
    {
        $donation->load(['campaign', 'user']);
        return view('admin.donations.show', compact('donation'));
    }
}
