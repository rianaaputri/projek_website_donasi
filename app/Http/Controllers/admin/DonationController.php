<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Campaign;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with(['campaign', 'user'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }
        
        $donations = $query->paginate(10);
        $campaigns = Campaign::all();

        return view('admin.donations.index', compact('donations', 'campaigns'));
    }

    public function show(Donation $donation)
    {
        $donation->load(['campaign', 'user']);
        return view('admin.donations.show', compact('donation'));
    }

    public function updateStatus(Request $request, Donation $donation)
    {
        $validated = $request->validate(['status' => 'required|in:pending,success,failed']);
        $donation->update($validated);
        return back()->with('success', 'Donation status updated successfully');
    }
}
