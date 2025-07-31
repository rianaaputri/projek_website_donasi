<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation; // Pastikan ini di-import
use Illuminate\Support\Facades\Log;
use Midtrans\Config;     // Import Midtrans\Config
use Midtrans\Notification; // Import Midtrans\Notification

class MidtransController extends Controller
{
    // Ubah nama metode dari 'callback' menjadi 'handleCallback' agar sesuai dengan route Anda
    public function handleCallback(Request $request)
    {
        // --- 1. Inisialisasi Konfigurasi Midtrans ---
        // Ini SANGAT PENTING agar notifikasi dari Midtrans dapat divalidasi
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false); // Sesuaikan dengan APP_ENV Anda
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // --- 2. Buat instance notifikasi dari Midtrans ---
        // Ini akan secara otomatis memvalidasi notifikasi yang masuk
        try {
            $notif = new Notification();
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['message' => 'Invalid Midtrans Notification'], 400);
        }

        // Ambil data penting dari notifikasi
        $transactionStatus = $notif->transaction_status;
        $orderId = $notif->order_id;
        $fraudStatus = $notif->fraud_status;
        $paymentType = $notif->payment_type;
        $grossAmount = $notif->gross_amount; // Jumlah pembayaran
        $transactionTime = $notif->transaction_time; // Waktu transaksi

        // --- 3. Cari donasi berdasarkan midtrans_order_id ---
        $donation = Donation::where('midtrans_order_id', $orderId)->first();

        if (!$donation) {
            Log::error('Donation not found for order ID: ' . $orderId);
            return response()->json(['message' => 'Donation not found'], 404);
        }

        // --- Log data status yang masuk (untuk debugging) ---
        Log::info('Midtrans Callback for Order ID: ' . $orderId);
        Log::info('Transaction Status: ' . $transactionStatus);
        Log::info('Payment Type: ' . $paymentType);
        Log::info('Fraud Status: ' . $fraudStatus);
        Log::info('Gross Amount: ' . $grossAmount);
        Log::info('Current Donation Status in DB: ' . $donation->payment_status);


        // --- 4. Logika Pemrosesan Status Transaksi & Pembaruan Kolom Lainnya ---
        if ($transactionStatus == 'capture') {
            // Untuk kartu kredit, status 'capture' berarti dana sudah ditangkap
            if ($paymentType == 'credit_card') {
                if ($fraudStatus == 'accept') {
                    $donation->payment_status = 'success';
                } elseif ($fraudStatus == 'challenge') {
                    $donation->payment_status = 'pending'; // Perlu verifikasi manual
                } else {
                    $donation->payment_status = 'failed'; // Status fraud lainnya
                }
            }
        } elseif ($transactionStatus == 'settlement') {
            // Untuk non-kartu kredit, 'settlement' berarti pembayaran berhasil
            $donation->payment_status = 'success';
        } elseif ($transactionStatus == 'pending') {
            // Menunggu pembayaran
            $donation->payment_status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            // Pembayaran ditolak, dibatalkan, atau kadaluarsa
            $donation->payment_status = 'failed';
        } elseif ($transactionStatus == 'refund' || $transactionStatus == 'partial_refund') {
            // Pembayaran dikembalikan (opsional, tergantung kebutuhan Anda)
            $donation->payment_status = 'refunded';
        }

        // --- 5. Perbarui Kolom Lain yang Penting ---
        // Hanya perbarui jika statusnya berubah atau jika ini adalah status final
        if ($donation->isDirty('payment_status') || $donation->payment_status === 'success' || $donation->payment_status === 'failed') {
            $donation->payment_method = $paymentType;
            $donation->transaction_id = $notif->transaction_id; // ID transaksi dari Midtrans
            $donation->midtrans_response = json_encode($notif); // Simpan seluruh respon notifikasi
            
            // Hanya set paid_at jika statusnya sukses/berhasil
            if ($donation->payment_status === 'success') {
                $donation->paid_at = date('Y-m-d H:i:s'); // Waktu saat pembayaran berhasil
            }
        }

        $donation->save();

        Log::info('Payment status updated for Order ID ' . $orderId . ': ' . $donation->payment_status);

        return response()->json(['message' => 'Payment status updated'], 200);
    }
}