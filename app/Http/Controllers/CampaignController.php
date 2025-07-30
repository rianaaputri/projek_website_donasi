<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Donation;

class CampaignController extends Controller
{
    private function getEnumValues($table, $column)
    {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = ?", [$column])[0]->Type;

        preg_match('/enum\((.*)\)/', $type, $matches);

        return collect(explode(',', $matches[1]))
            ->map(fn($value) => trim($value, "'"))
            ->toArray();
    }

    public function index()
    {
        $campaigns = Campaign::all();
        return view('campaign.index', compact('campaigns'));
    }

   

    public function store(Request $request)
    {
        $categories = $this->getEnumValues('campaigns', 'category');

        $validated = $request->validate([
            'title'           => 'required',
            'description'     => 'required',
            'category'        => ['required', Rule::in($categories)],
            'target_amount'   => 'required|numeric',
            'image'           => 'nullable|string',
            'status'          => 'required|in:active,completed,inactive',
            'is_active'       => 'nullable|boolean',
        ]);

        Campaign::create($validated);

        return redirect()->route('campaign.index')->with('success', 'Campaign berhasil ditambah!');
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        $categories = $this->getEnumValues('campaigns', 'category');

        return view('campaign.edit', compact('campaign', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $categories = $this->getEnumValues('campaigns', 'category');

        $validated = $request->validate([
            'title'           => 'required',
            'description'     => 'required',
            'category'        => ['required', Rule::in($categories)],
            'target_amount'   => 'required|numeric',
            'image'           => 'nullable|string',
            'status'          => 'required|in:active,completed,inactive',
            'is_active'       => 'nullable|timestamp',
        ]);

        $campaign = Campaign::findOrFail($id);
        $campaign->update($validated);

        return redirect()->route('campaign.index')->with('success', 'Campaign berhasil diupdate!');
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();

        return redirect()->route('campaign.index')->with('success', 'Campaign berhasil dihapus!');
    }


public function success($donationId)
{
    $donation = Donation::with('campaign.donations')->findOrFail($donationId);
    $campaign = $donation->campaign;

    // Filter hanya donasi sukses
    $successfulDonations = $campaign->donations->where('payment_status', 'success');

    $totalCollected = $successfulDonations->sum('amount');
    $formattedCollected = 'Rp ' . number_format($totalCollected, 0, ',', '.');
    $formattedTarget = 'Rp ' . number_format($campaign->target_amount, 0, ',', '.');

    $progressPercentage = $campaign->target_amount > 0
        ? min(100, ($totalCollected / $campaign->target_amount) * 100)
        : 0;

    $donorCount = $successfulDonations->count();

    return view('donation.success', compact(
        'donation',
        'campaign',
        'formattedCollected',
        'formattedTarget',
        'progressPercentage',
        'donorCount'
    ));
}

public function show($id)
{
    $campaign = Campaign::with(['donations' => function ($query) {
        $query->where('payment_status', 'success');
    }])->findOrFail($id);

    $successfulDonations = $campaign->donations->count();

    $formattedCollected = 'Rp ' . number_format($campaign->donations->sum('amount'), 0, ',', '.');
    $formattedTarget = 'Rp ' . number_format($campaign->target_amount, 0, ',', '.');

    $progressPercentage = $campaign->target_amount > 0
        ? min(100, ($campaign->donations->sum('amount') / $campaign->target_amount) * 100)
        : 0;

    // Ambil donasi dengan komentar (tidak null)
    $campaignComments = $campaign->donations
        ->whereNotNull('comment')
        ->sortByDesc('created_at');

    // Donatur terbaru (max 10)
    $recentDonors = $campaign->donations->sortByDesc('created_at')->take(10);

    return view('campaigns.show', compact(
        'campaign',
        'formattedCollected',
        'formattedTarget',
        'progressPercentage',
        'successfulDonations',
        'recentDonors',
        'campaignComments'
    ));
}




}