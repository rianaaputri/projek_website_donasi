<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Midtrans\Config;

class MidtransController extends Controller
{
    public function handleCallback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notification = $request->all();
            $orderId = $notification['order_id'];
            $donation = Donation::where('midtrans_order_id', $orderId)->firstOrFail();
            
            $transactionStatus = $notification['transaction_status'];
            $previousStatus = $donation->payment_status;
            
            $donation->payment_status = match($transactionStatus) {
                'capture', 'settlement' => 'success',
                'pending' => 'pending',
                'deny', 'expire', 'cancel' => 'failed',
                default => 'failed'
            };

            $donation->payment_method = $notification['payment_type'] ?? null;
            $donation->save();

            if ($previousStatus !== 'success' && $donation->payment_status === 'success') {
                $donation->campaign->updateCollectedAmount();
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json(['success' => false], 500);
        }
    }
}