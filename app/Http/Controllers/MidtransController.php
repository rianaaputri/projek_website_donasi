<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;
use App\Models\Campaign;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    protected $midtransService;
    
    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }
    
    public function callback(Request $request)
    {
        try {
            $notification = $this->midtransService->handleNotification();
            
            $orderId = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $paymentType = $notification->payment_type;
            $fraudStatus = isset($notification->fraud_status) ? $notification->fraud_status : null;
            
            Log::info('Midtrans Callback', [
                'order_id' => $orderId,
                'status' => $transactionStatus,
                'payment_type' => $paymentType
            ]);
            
            $donation = Donation::where('order_id', $orderId)->first();
            
            if (!$donation) {
                Log::warning('Donation not found for order_id: ' . $orderId);
                return response('Donation not found', 404);
            }
            
            if ($transactionStatus == 'capture') {
                if ($paymentType == 'credit_card') {
                    if ($fraudStatus == 'challenge') {
                        $donation->update([
                            'status' => 'pending',
                            'payment_type' => $paymentType
                        ]);
                    } else {
                        $donation->update([
                            'status' => 'success',
                            'payment_type' => $paymentType
                        ]);
                        $this->updateCampaignTotal($donation);
                    }
                }
            } elseif ($transactionStatus == 'settlement') {
                $donation->update([
                    'status' => 'success',
                    'payment_type' => $paymentType
                ]);
                $this->updateCampaignTotal($donation);
                
            } elseif ($transactionStatus == 'pending') {
                $donation->update([
                    'status' => 'pending',
                    'payment_type' => $paymentType
                ]);
                
            } elseif ($transactionStatus == 'deny') {
                $donation->update([
                    'status' => 'failed',
                    'payment_type' => $paymentType
                ]);
                
            } elseif ($transactionStatus == 'expire') {
                $donation->update([
                    'status' => 'failed',
                    'payment_type' => $paymentType
                ]);
                
            } elseif ($transactionStatus == 'cancel') {
                $donation->update([
                    'status' => 'cancelled',
                    'payment_type' => $paymentType
                ]);
            }
            
            return response('OK', 200);
            
        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }
    
    private function updateCampaignTotal($donation)
    {
        $campaign = Campaign::find($donation->campaign_id);
        if ($campaign) {
            $totalTerkumpul = Donation::where('campaign_id', $donation->campaign_id)
                                    ->where('status', 'success')
                                    ->sum('nominal');
            
            $campaign->update(['terkumpul' => $totalTerkumpul]);
            
            // Check if campaign target is reached
            if ($totalTerkumpul >= $campaign->target) {
                $campaign->update(['status' => 'completed']);
            }
        }
    }
}