<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    /**
     * Tampilkan daftar campaigns.
     */
    public function index(Request $request)
    {
        try {
            $query = Campaign::with('user');

            // HANYA TAMPILKAN CAMPAIGN YANG SUDAH DIVERIFIKASI (APPROVED ATAU REJECTED)
            $query->whereIn('verification_status', ['approved', 'rejected']);

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            $campaigns = $query->withCount('donations')
                               ->withSum('donations', 'amount')
                               ->latest()
                               ->paginate(10);

            $categories = $this->getCategoryOptions();
            $statuses = $this->getStatusOptions();
            $verificationStatuses = $this->getVerificationStatusOptions();

            return view('admin.campaigns.index', compact('campaigns', 'categories', 'statuses', 'verificationStatuses'));
        } catch (\Exception $e) {
            Log::error('Campaign index error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data campaigns.');
        }
    }

    /**
     * Tampilkan formulir untuk membuat campaign baru.
     */
    public function create()
    {
        $users = User::select('id', 'name')->get();
        $categories = $this->getCategoryOptions();
        return view('admin.campaigns.create', compact('users', 'categories'));
    }

    /**
     * Simpan campaign yang baru dibuat.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1000',
            'category' => ['required', 'string', Rule::in(array_keys($this->getCategoryOptions()))],
            'end_date' => 'required|date|after:today',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            DB::beginTransaction();
            $imagePath = $request->file('image')->store('campaigns', 'public');
            $validated['image'] = $imagePath;
            // ATUR STATUS AWAL HANYA UNTUK MENUNGGU VERIFIKASI
            $validated['status'] = 'draft';
            $validated['collected_amount'] = 0;
            $validated['verification_status'] = 'pending';
            Campaign::create($validated);
            DB::commit();
            
            return redirect()->route('admin.campaigns.verify')->with('success', 'Campaign berhasil dibuat! Menunggu verifikasi.');
        } catch (\Exception $e) {
            DB::rollback();
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            Log::error('Campaign store error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan saat membuat campaign: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan detail campaign yang ditentukan.
     */
    public function show(Campaign $campaign)
    {
        $campaign->load('donations.user');
        $totalAmount = $campaign->donations->sum('amount');
        $totalDonations = $campaign->donations->count();
        $recentDonations = $campaign->donations->sortByDesc('created_at')->take(10);
        $progressPercentage = $campaign->target_amount > 0 ? min(100, ($totalAmount / $campaign->target_amount) * 100) : 0;
        return view('admin.campaigns.show', compact('campaign', 'totalDonations', 'totalAmount', 'progressPercentage', 'recentDonations'));
    }

    /**
     * Tampilkan formulir untuk mengedit campaign.
     */
    public function edit(Campaign $campaign)
    {
        $users = User::select('id', 'name')->get();
        $categories = $this->getCategoryOptions();
        return view('admin.campaigns.edit', compact('campaign', 'users', 'categories'));
    }

    /**
     * Update campaign yang ditentukan.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1000',
            'category' => ['required', 'string', Rule::in(array_keys($this->getCategoryOptions()))],
            'end_date' => 'required|date|after_or_equal:today',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
            'status' => ['required', Rule::in(array_keys($this->getStatusOptions()))],
        ]);

        try {
            DB::beginTransaction();
            $oldImage = $campaign->image;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('campaigns', 'public');
                $validated['image'] = $imagePath;
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            $campaign->update($validated);
            DB::commit();
            return redirect()->route('admin.campaigns.index')->with('success', 'Campaign berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollback();
            if (isset($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            Log::error('Campaign update error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus campaign dari penyimpanan.
     */
    public function destroy(Campaign $campaign)
    {
        try {
            if ($campaign->donations()->count() > 0) {
                return back()->with('error', 'Tidak dapat menghapus campaign yang sudah memiliki donasi.');
            }
            
            DB::beginTransaction();
            $imagePath = $campaign->image;
            $campaign->delete();
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            DB::commit();
            return redirect()->route('admin.campaigns.index')->with('success', 'Campaign berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Campaign destroy error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus campaign: ' . $e->getMessage());
        }
    }

    /**
     * Update status campaign.
     */
    public function updateStatus(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(array_keys($this->getStatusOptions()))]
        ]);

        try {
            if ($validated['status'] === 'active' && $campaign->verification_status !== 'approved') {
                return response()->json(['success' => false, 'message' => 'Campaign belum diverifikasi. Tidak dapat diaktifkan.'], 400);
            }
            
            if ($campaign->verification_status === 'rejected' && $validated['status'] === 'active') {
                return response()->json(['success' => false, 'message' => 'Campaign yang ditolak tidak dapat diaktifkan.'], 400);
            }

            $campaign->update(['status' => $validated['status']]);

            return response()->json([
                'success' => true,
                'message' => 'Status campaign berhasil diubah.'
            ]);
        } catch (\Exception $e) {
            Log::error('Campaign status update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Tampilkan daftar donasi untuk campaign tertentu.
     */
    public function donations(Campaign $campaign)
    {
        try {
            $donationsData = $campaign->donations()->with('user')->orderBy('created_at', 'desc')->paginate(15);
            return view('admin.campaigns.donations', compact('campaign', 'donationsData'));
        } catch (\Exception $e) {
            Log::error('Campaign donations error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data donasi.');
        }
    }

    /**
     * Lakukan aksi massal pada campaign.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'campaigns' => 'required|array|min:1',
            'campaigns.*' => 'exists:campaigns,id'
        ]);

        try {
            DB::beginTransaction();
            $campaigns = Campaign::whereIn('id', $validated['campaigns'])->get();
            $count = 0;

            foreach ($campaigns as $campaign) {
                switch ($validated['action']) {
                    case 'activate':
                        if ($campaign->verification_status === 'approved') {
                            $campaign->update(['status' => 'active']);
                            $count++;
                        }
                        break;
                    case 'deactivate':
                        $campaign->update(['status' => 'inactive']);
                        $count++;
                        break;
                    case 'delete':
                        if ($campaign->donations()->count() == 0) {
                            if ($campaign->image) {
                                Storage::disk('public')->delete($campaign->image);
                            }
                            $campaign->delete();
                            $count++;
                        }
                        break;
                }
            }
            DB::commit();

            $actionMessages = ['activate' => 'diaktifkan', 'deactivate' => 'dinonaktifkan', 'delete' => 'dihapus'];
            $message = "{$count} campaign berhasil {$actionMessages[$validated['action']]}.";
            if ($validated['action'] === 'activate' && $count < count($validated['campaigns'])) {
                $message .= " Beberapa campaign tidak diaktifkan karena belum diverifikasi.";
            }

            return redirect()->route('admin.campaigns.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Campaign bulk action error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Dapatkan daftar pilihan kategori.
     */
    private function getCategoryOptions()
    {
        return [
            'kesehatan' => 'Kesehatan',
            'pendidikan' => 'Pendidikan',
            'infrastruktur' => 'Infrastruktur',
            'bencana_alam' => 'Bencana Alam',
            'kemanusiaan' => 'Kemanusiaan',
            'lingkungan' => 'Lingkungan'
        ];
    }

    /**
     * Dapatkan daftar pilihan status.
     */
    private function getStatusOptions()
    {
        return [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'draft' => 'Draft',
            'pending' => 'Menunggu',
            'rejected' => 'Ditolak'
        ];
    }

    /**
     * Dapatkan daftar pilihan status verifikasi.
     */
    private function getVerificationStatusOptions()
    {
        return [
            'pending' => 'Menunggu Verifikasi',
            'approved' => 'Diverifikasi',
            'rejected' => 'Ditolak',
        ];
    }

    /**
     * Halaman verifikasi campaign.
     */
    public function verifyIndex()
    {
        $pendingCampaigns = Campaign::where('verification_status', 'pending')
            ->with('user')
            ->latest()
            ->get();
        return view('admin.campaigns.verify', compact('pendingCampaigns'));
    }

    /**
     * Setujui campaign.
     */
    public function verifyApprove(Request $request, Campaign $campaign)
    {
        $validated = $request->validate(['admin_notes' => 'sometimes|nullable|string|max:500']);

        try {
            if ($campaign->verification_status !== 'pending') {
                return redirect()->route('admin.campaigns.verify')->with('error', 'Campaign ini sudah diproses sebelumnya.');
            }

            DB::beginTransaction();
            $campaign->update([
                'verification_status' => 'approved',
                'status' => 'active',
                'verified_at' => now(),
                'verified_by' => auth()->id(),
                'admin_notes' => $validated['admin_notes'] ?? null
            ]);
            DB::commit();
            
            // REDIRECT KE HALAMAN MANAGE CAMPAIGNS (INDEX) SETELAH DIVERIFIKASI
            return redirect()->route('admin.campaigns.index')->with('success', 'Campaign "' . $campaign->title . '" berhasil diverifikasi dan diaktifkan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Campaign verify approve error: ' . $e->getMessage());
            return redirect()->route('admin.campaigns.verify')->with('error', 'Terjadi kesalahan saat memverifikasi campaign.');
        }
    }

    /**
     * Tolak campaign.
     */
    public function verifyReject(Request $request, Campaign $campaign)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            if ($campaign->verification_status !== 'pending') {
                return back()->with('error', 'Campaign ini sudah diproses sebelumnya.');
            }

            DB::beginTransaction();
            $campaign->update([
                'verification_status' => 'rejected',
                'status' => 'inactive',
                'rejection_reason' => $request->input('rejection_reason'),
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
            ]);
            DB::commit();
            
            return redirect()->route('admin.campaigns.index')->with('success', 'Campaign "' . $campaign->title . '" berhasil ditolak dan statusnya diubah menjadi inactive.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Campaign verify reject error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menolak campaign.');
        }
    }
}