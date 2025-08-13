<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Generate Snap Token dengan parameter langsung
     */
    public function getSnapToken(array $params)
    {
        return Snap::getSnapToken($params);
    }

    /**
     * Generate Snap Token berdasarkan model Donasi
     */
    public function createSnapToken($donation)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $donation->midtrans_order_id,
                'gross_amount' => (int) $donation->amount,
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'email' => $donation->donor_email,
                'phone' => $donation->donor_phone,
            ],
            'item_details' => [
                [
                    'id' => $donation->campaign_id,
                    'name' => 'Donasi untuk ' . $donation->campaign->title,
                    'price' => (int) $donation->amount,
                    'quantity' => 1,
                ]
            ],
            'callbacks' => [
                'finish' => route('donation.success', $donation->id),
            ],
        ];

        return Snap::getSnapToken($params);
    }
}
