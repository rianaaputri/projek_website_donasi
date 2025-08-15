<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    /**
     * Tampilkan form buat campaign
     */
    public function create()
    {
        return view('user.campaigns.create');
    }

    /**
     * Simpan campaign baru ke database
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'target_amount' => 'required|integer|min:10000',
            'category' => 'required|string|max:100',
            'end_date' => 'nullable|date|after:today',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'title.required' => 'Judul campaign wajib diisi.',
            'description.required' => 'Deskripsi campaign wajib diisi.',
            'description.min' => 'Deskripsi minimal 50 karakter.',
            'target_amount.required' => 'Target donasi wajib diisi.',
            'target_amount.min' => 'Target donasi minimal Rp 10.000.',
            'category.required' => 'Kategori campaign wajib dipilih.',
            'end_date.after' => 'Tanggal berakhir harus setelah hari ini.',
            'thumbnail.image' => 'File harus berupa gambar.',
            'thumbnail.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = $request->only(['title', 'description', 'target_amount', 'category', 'end_date']);
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';
        $data['current_amount'] = 0;

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('campaigns', 'public');
        }

        $campaign = Campaign::create($data);

        return redirect()->route('campaign.history')
            ->with('success', 'Campaign berhasil diajukan dan sedang menunggu verifikasi admin.');
    }

    /**
     * Tampilkan history campaign milik user
     */
    public function history()
    {
        $campaigns = Campaign::where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
            
        return view('user.campaigns.history', compact('campaigns'));
    }

    /**
     * Tampilkan detail campaign milik user
     */
    public function detail($id)
    {
        $campaign = Campaign::with(['user', 'donations.user'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hitung statistik campaign
        $campaign->donors_count = $campaign->donations->groupBy('user_id')->count();
        $campaign->recent_donations = $campaign->donations()
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        return view('user.campaigns.detail', compact('campaign'));
    }

    /**
     * Tampilkan form edit campaign
     */
    public function edit($id)
    {
        $campaign = Campaign::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hanya campaign dengan status pending atau rejected yang bisa diedit
        if (!in_array($campaign->status, ['pending', 'rejected'])) {
            return redirect()->route('campaign.history')
                ->with('error', 'Campaign dengan status ' . $campaign->status . ' tidak dapat diedit.');
        }

        return view('user.campaigns.edit', compact('campaign'));
    }

    /**
     * Update campaign
     */
    public function update(Request $request, $id)
    {
        $campaign = Campaign::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validasi status
        if (!in_array($campaign->status, ['pending', 'rejected'])) {
            return redirect()->route('campaign.history')
                ->with('error', 'Campaign dengan status ' . $campaign->status . ' tidak dapat diedit.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'target_amount' => 'required|integer|min:10000',
            'category' => 'required|string|max:100',
            'end_date' => 'nullable|date|after:today',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'target_amount', 'category', 'end_date']);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama jika ada
            if ($campaign->thumbnail) {
                Storage::disk('public')->delete($campaign->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('campaigns', 'public');
        }

        // Reset status ke pending jika sebelumnya rejected
        if ($campaign->status === 'rejected') {
            $data['status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        $campaign->update($data);

        return redirect()->route('campaign.detail', $campaign->id)
            ->with('success', 'Campaign berhasil diperbarui dan akan direview ulang oleh admin.');
    }

    /**
     * Hapus campaign (soft delete)
     */
    public function destroy($id)
    {
        $campaign = Campaign::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Hanya campaign pending yang bisa dihapus
        if ($campaign->status !== 'pending') {
            return redirect()->route('campaign.history')
                ->with('error', 'Hanya campaign dengan status pending yang dapat dihapus.');
        }

        // Hapus thumbnail jika ada
        if ($campaign->thumbnail) {
            Storage::disk('public')->delete($campaign->thumbnail);
        }

        $campaign->delete();

        return redirect()->route('campaign.history')
            ->with('success', 'Campaign berhasil dihapus.');
    }

    /**
     * Toggle status campaign (untuk testing - sebaiknya dihapus di production)
     */
    public function toggleStatus($id)
    {
        if (!app()->environment('local')) {
            abort(403, 'Action not allowed in production.');
        }

        $campaign = Campaign::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $nextStatus = [
            'pending' => 'approved',
            'approved' => 'active',
            'active' => 'completed',
            'completed' => 'pending',
            'rejected' => 'pending',
        ];

        $campaign->update([
            'status' => $nextStatus[$campaign->status] ?? 'pending'
        ]);

        return redirect()->back()
            ->with('success', 'Status campaign berhasil diubah ke: ' . $campaign->status);
    }

    /**
     * Duplicate campaign (untuk resubmit campaign yang rejected)
     */
    public function duplicate($id)
    {
        $originalCampaign = Campaign::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $newCampaignData = $originalCampaign->only([
            'title', 'description', 'target_amount', 'category', 'thumbnail'
        ]);
        
        $newCampaignData['user_id'] = Auth::id();
        $newCampaignData['status'] = 'pending';
        $newCampaignData['current_amount'] = 0;
        $newCampaignData['title'] = $originalCampaign->title . ' (Copy)';

        $newCampaign = Campaign::create($newCampaignData);

        return redirect()->route('campaign.edit', $newCampaign->id)
            ->with('success', 'Campaign berhasil diduplikasi. Silakan edit dan ajukan kembali.');
    }
}