<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    public function create()
    {
        return view('user.campaigns.create');
    }

    public function store(Request $request)
    {
        Log::info('Request input', $request->all()); 
        Log::info('Uploaded file info', [
            'has_file' => $request->hasFile('image'),
            'file_name' => $request->file('image')?->getClientOriginalName(),
            'file_size' => $request->file('image')?->getSize(),
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'target_amount' => 'required|integer|min:10000|max:1000000000', 
            'category' => 'required|string|max:100',
            'end_date' => 'required|date|after:today',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'target_amount', 'category', 'end_date']);
        $data['user_id'] = Auth::id();
        $data['verification_status'] = 'pending';
        $data['collected_amount'] = 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('campaigns', 'public');
        }

        Campaign::create($data);

        return redirect()->route('user.campaigns.history')
            ->with('success', 'Campaign berhasil diajukan dan sedang menunggu verifikasi dari admin.');
    }

    public function history()
    {
        $campaigns = Campaign::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('user.campaigns.campaign-history', compact('campaigns'));
    }

    public function detail($id)
    {
        $campaign = Campaign::with(['user', 'donations.user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $campaign->donors_count = $campaign->donations->groupBy('user_id')->count();
        $campaign->recent_donations = $campaign->donations()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('user.campaigns.detail', compact('campaign'));
    }

    public function donations($id)
    {
        $campaign = Campaign::with(['donations.user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $donations = $campaign->donations()
            ->with('user')
            ->latest()
            ->paginate(10);

        return view('user.campaigns.donations', compact('campaign', 'donations'));
    }

    public function edit($id)
    {
        $campaign = Campaign::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!in_array($campaign->verification_status, ['pending', 'rejected'])) {
            return redirect()->route('user.campaigns.history')
                ->with('error', 'Campaign yang sudah diverifikasi tidak dapat diedit.');
        }

        return view('user.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, $id)
    {
        $campaign = Campaign::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if (!in_array($campaign->verification_status, ['pending', 'rejected'])) {
            return redirect()->route('user.campaigns.history')
                ->with('error', 'Campaign yang sudah diverifikasi tidak dapat diedit.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'target_amount' => 'required|integer|min:10000|max:1000000000',
            'category' => 'required|string|max:100',
            'end_date' => 'required|date|after:today',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'target_amount', 'category', 'end_date']);

        if ($request->hasFile('image')) {
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $data['image'] = $request->file('image')->store('campaigns', 'public');
        }

        if ($campaign->verification_status === 'rejected') {
            $data['verification_status'] = 'pending';
        }

        $campaign->update($data);

        return redirect()->route('user.campaigns.detail', $campaign->id)
            ->with('success', 'Campaign berhasil diperbarui dan menunggu verifikasi ulang.');
    }

    public function destroy($id)
    {
        $campaign = Campaign::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($campaign->verification_status !== 'pending') { 
            return redirect()->route('user.campaigns.history')
                ->with('error', 'Hanya campaign yang sedang menunggu verifikasi yang bisa dihapus.');
        }

        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return redirect()->route('user.campaigns.history')
            ->with('success', 'Campaign berhasil dihapus.');
    }
}