<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DonationController extends Controller
{
    public function create($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        
        if ($campaign->status !== 'active' || !$campaign->is_active) {
            return redirect()->route('campaign.show', $campaignId)
                ->with('error', 'Campaign ini sudah tidak aktif');
        }

        return view('donation.create', compact('campaign'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'campaign_id' => 'required|exists:campaigns,id',
            'donor_name' => 'required|string|max:255',
            'donor_email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:10000', // Minimum 10rb
            'comment' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $campaign = Campaign::findOrFail($request->campaign_id);
        
        if ($campaign->status !== 'active') {
            return back()->with('error', 'Campaign sudah tidak aktif');
        }

        $donation = Donation::create([
            'campaign_id' => $request->campaign_id,
            'donor_name' => $request->donor_name,
            'donor_email' => $request->donor_email,
            'amount' => $request->amount,
            'comment' => $request->comment,
            'status' => 'pending'
        ]);

        // TODO: Integrate with Midtrans here
        // For now, we'll redirect to success page
        
        return redirect()->route('donation.success', $donation->id)
            ->with('success', 'Donasi berhasil dibuat!');
    }

    public function success($donationId)
    {
        $donation = Donation::with('campaign')->findOrFail($donationId);
        
        return view('donation.success', compact('donation'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;

class DonationController extends Controller
{
    public function create()
    {
        return view('donation.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'nominal' => 'nullable|numeric',
            'nominal_custom' => 'nullable|numeric',
            'komentar' => 'nullable|string',
        ]);

        Donation::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nominal' => $request->nominal,
            'nominal_custom' => $request->nominal_custom,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('donasi.create')->with('success', 'Donasi berhasil disimpan!');
    }
}
