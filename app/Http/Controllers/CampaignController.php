<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::latest()->paginate(10);
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'target_amount' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required'
        ]);

        if ($request->file('image')) {
            $image = $request->file('image')->store('campaign-images', 'public');
            $validatedData['image'] = $image;
        }

        $validatedData['collected_amount'] = 0;
        $validatedData['status'] = 'active';
        $validatedData['verification_status'] = 'accepted'; // Admin buat langsung diterima

        Campaign::create($validatedData);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign berhasil dibuat.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'target_amount' => 'required|numeric',
            'end_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required'
        ]);

        if ($request->file('image')) {
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $image = $request->file('image')->store('campaign-images', 'public');
            $validatedData['image'] = $image;
        }

        $validatedData['slug'] = Str::slug($validatedData['title']);
        $campaign->update($validatedData);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign berhasil diperbarui.');
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }
        $campaign->delete();
        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign berhasil dihapus.');
    }

    // Verifikasi: pending → accepted / rejected
    public function verifyIndex()
    {
        $pendingCampaigns = Campaign::with('user')
            ->where('verification_status', 'pending')
            ->latest()
            ->get();

        return view('admin.campaigns.verify', compact('pendingCampaigns'));
    }

    public function verifyApprove($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->verification_status = 'accepted';
        $campaign->status = 'active'; // bisa juga tetap 'draft' sampai user publish
        $campaign->save();

        return redirect()->route('admin.campaigns.verify')
            ->with('success', 'Campaign berhasil diterima dan akan muncul di halaman utama.');
    }

    public function verifyReject($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->verification_status = 'rejected';
        $campaign->status = 'draft';
        $campaign->save();

        return redirect()->route('admin.campaigns.verify')
            ->with('info', 'Campaign ditolak dan tidak akan muncul di halaman utama.');
    }
}