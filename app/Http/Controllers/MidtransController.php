<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        // Inisialisasi notifikasi dari Midtrans
        $notif = new Notification();

        // Ambil data penting
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $fraud = $notif->fraud_status;
        $order_id = $notif->order_id;

        // Cari donasi berdasarkan midtrans_order_id
        $donation = Donation::where('midtrans_order_id', $order_id)->first();

        if (!$donation) {
            Log::error('Donation not found for order ID: ' . $order_id);
            return response()->json(['message' => 'Donation not found'], 404);
        }

        // Log data status yang masuk
        Log::info('Midtrans Callback for Order ID: ' . $order_id);
        Log::info('Transaction Status: ' . $transaction);
        Log::info('Payment Type: ' . $type);
        Log::info('Fraud Status: ' . $fraud);

        // Update status pembayaran
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                $donation->payment_status = ($fraud == 'challenge') ? 'pending' : 'success';
            }
        } elseif ($transaction == 'settlement') {
            $donation->payment_status = 'success';
        } elseif ($transaction == 'pending') {
            $donation->payment_status = 'pending';
        } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
            $donation->payment_status = 'failed';
        }

        $donation->save();

        Log::info('Payment status updated: ' . $donation->payment_status);

        return response()->json(['message' => 'Payment status updated'], 200);
    }
}
