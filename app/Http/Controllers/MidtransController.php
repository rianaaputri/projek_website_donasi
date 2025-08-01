<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function handleCallback(Request $request)
    {
        // --- 1. Inisialisasi Konfigurasi Midtrans ---
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false); // Sesuaikan dengan APP_ENV Anda
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // --- 2. Buat instance notifikasi dari Midtrans ---
        try {
            $notif = new Notification();
            // --- DEBUGGING SEMENTARA ---
            // dd($notif->jsonSerialize()); // <-- HAPUS BARIS INI SETELAH BERHASIL MELIHAT OUTPUTNYA
            // --- END DEBUGGING ---
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
        // $transactionTime = $notif->transaction_time; // Waktu transaksi - Tidak digunakan langsung di sini, tapi bisa disave jika perlu

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
                    $newPaymentStatus = 'success';
                } elseif ($fraudStatus == 'challenge') {
                    $newPaymentStatus = 'pending'; // Perlu verifikasi manual
                } else {
                    $newPaymentStatus = 'failed'; // Status fraud lainnya
                }
            } else {
                // Should not happen for 'capture' as it's credit card specific
                $newPaymentStatus = 'failed';
            }
        } elseif ($transactionStatus == 'settlement') {
            // Untuk non-kartu kredit, 'settlement' berarti pembayaran berhasil
            $newPaymentStatus = 'success';
        } elseif ($transactionStatus == 'pending') {
            // Menunggu pembayaran
            $newPaymentStatus = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            // Pembayaran ditolak, dibatalkan, atau kadaluarsa
            $newPaymentStatus = 'failed';
        } elseif ($transactionStatus == 'refund' || $transactionStatus == 'partial_refund') {
            // Pembayaran dikembalikan (opsional, tergantung kebutuhan Anda)
            $newPaymentStatus = 'refunded';
        } else {
            // Status tidak dikenal
            $newPaymentStatus = $donation->payment_status; // Tetap pada status sebelumnya
            Log::warning('Unknown transaction status received: ' . $transactionStatus . ' for Order ID: ' . $orderId);
        }

        // --- 5. Perbarui Kolom Lain yang Penting ---
        // Hanya perbarui jika statusnya berubah atau jika ini adalah status final yang sukses/gagal
        // Menggunakan 'isDirty' untuk menghindari update yang tidak perlu, tapi pastikan juga untuk update data penting lainnya
        $updateNeeded = false;
        if ($donation->payment_status !== $newPaymentStatus) {
            $donation->payment_status = $newPaymentStatus;
            $updateNeeded = true;
        }

        if ($updateNeeded || $newPaymentStatus === 'success' || $newPaymentStatus === 'failed') {
            $donation->payment_method = $paymentType;
            $donation->transaction_id = $notif->transaction_id; // ID transaksi dari Midtrans
            $donation->midtrans_response = json_encode($notif->jsonSerialize()); // Simpan seluruh respon notifikasi dalam bentuk JSON string

            // Hanya set paid_at jika statusnya sukses/berhasil dan belum di-set
            if ($donation->payment_status === 'success' && is_null($donation->paid_at)) {
                $donation->paid_at = date('Y-m-d H:i:s'); // Waktu saat pembayaran berhasil
            }
        }

        // Simpan perubahan ke database
        $donation->save();

        // Update collected_amount di Campaign
        if ($donation->payment_status === 'success' && $donation->campaign) {
            $donation->campaign->updateCollectedAmount();
        }


        Log::info('Final Payment status for Order ID ' . $orderId . ': ' . $donation->payment_status);

        return response()->json(['message' => 'Payment status updated'], 200);
    }
}