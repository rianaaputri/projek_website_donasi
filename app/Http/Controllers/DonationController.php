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
    }

    public function showDonationForm($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        return view('donation.form', compact('campaign'));
    }

   public function create($campaignId)
{
    $campaign = Campaign::findOrFail($campaignId); 
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
            'message' => 'nullable|string|max:1000',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $donation = Donation::create([
            'campaign_id' => $request->campaign_id,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'donor_phone' => $request->donor_phone,
            'amount' => $request->amount,
            'message' => $request->message,
            'is_anonymous' => $request->is_anonymous ?? false,
            'payment_status' => 'pending',
            'midtrans_order_id' => 'DONATE-' . strtoupper(Str::random(10)),
        ]);

        return redirect()->route('donation.payment', $donation->id);
    }

    public function payment($id)
    {
        $donation = Donation::findOrFail($id);

        $params = [
            'transaction_details' => [
                'order_id' => $donation->midtrans_order_id,
                'gross_amount' => $donation->amount,
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'email' => $donation->donor_email,
            ],
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
    $donation = Donation::findOrFail($id);

    try {
        $status = \Midtrans\Transaction::status($donation->midtrans_order_id);

        if (in_array($status->transaction_status, ['settlement', 'capture'])) {
            $donation->payment_status = 'success';
        } elseif (in_array($status->transaction_status, ['expire', 'cancel', 'deny'])) {
            $donation->payment_status = 'failed';
        } else {
            $donation->payment_status = 'pending';
        }

        $donation->save();

    } catch (\Exception $e) {
        \Log::error('Midtrans status check failed: ' . $e->getMessage());
    }

    return response()->json([
        'status' => $donation->payment_status,
        'progress' => $donation->campaign->progress_percentage,
        'collected' => $donation->campaign->formatted_collected,
        'donors' => $donation->campaign->donations()->count()
    ]);
}

}