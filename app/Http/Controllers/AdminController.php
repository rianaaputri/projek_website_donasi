<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns.
     */
    public function index(Request $request)
    {
        try {
            $query = Campaign::select('*');

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Filter by category
            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            $campaigns = $query->latest()->paginate(10);

            // Tambah data donasi manual
            foreach ($campaigns as $campaign) {
                $campaign->donations_count = 0;
                $campaign->donations_sum_amount = 0;
                
                if (Schema::hasTable('donations')) {
                    $campaign->donations_count = DB::table('donations')
                        ->where('campaign_id', $campaign->id)
                        ->count();
                    
                    $campaign->donations_sum_amount = DB::table('donations')
                        ->where('campaign_id', $campaign->id)
                        ->sum('amount') ?? 0;
                }
            }

            $categories = $this->getCategoryOptions();
            $statuses = $this->getStatusOptions();

            return view('admin.campaigns.index', compact('campaigns', 'categories', 'statuses'));
            
        } catch (\Exception $e) {
            Log::error('Campaign index error: ' . $e->getMessage());
            
            $campaigns = Campaign::paginate(10);
            $categories = [];
            $statuses = [];
            
            return view('admin.campaigns.index', compact('campaigns', 'categories', 'statuses'))
                ->with('error', 'Terjadi kesalahan saat memuat data campaigns.');
        }
    }

    /**
     * Show the form for creating a new campaign.
     */
    public function create()
    {
        $users = $this->getActiveUsers();
        $categories = $this->getCategoryOptions();
        
        return view('admin.campaigns.create', compact('users', 'categories'));
    }

    /**
     * Store a newly created campaign.
     */
    public function store(Request $request)
    {
        $categories = array_keys($this->getCategoryOptions());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1000',
            'category' => 'required|string|in:' . implode(',', $categories),
            'end_date' => 'required|date|after:today',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
            'status' => 'sometimes|in:active,inactive,completed,cancelled'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('campaigns', 'public');
                $validated['image'] = $imagePath;
            }

            // Default status & verification
            $validated['status'] = $validated['status'] ?? 'draft';
            $validated['collected_amount'] = 0;
            $validated['verification_status'] = 'pending';

            $campaign = Campaign::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.campaigns.index')
                ->with('success', 'Campaign berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();

            if (isset($validated['image']) && Storage::disk('public')->exists($validated['image'])) {
                Storage::disk('public')->delete($validated['image']);
            }

            Log::error('Campaign store error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat campaign: ' . $e->getMessage());
        }
    }

    public function show(Campaign $campaign)
    {
        $totalDonations = 0;
        $totalAmount = 0;
        $recentDonations = collect();
        
        try {
            if (Schema::hasTable('donations')) {
                $totalDonations = DB::table('donations')
                    ->where('campaign_id', $campaign->id)
                    ->count();
                    
                $totalAmount = DB::table('donations')
                    ->where('campaign_id', $campaign->id)
                    ->sum('amount') ?? 0;

                $recentDonationsData = DB::table('donations')
                    ->where('campaign_id', $campaign->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
                
                $recentDonations = collect($recentDonationsData);
            }
            
        } catch (\Exception $e) {
            Log::error('Campaign donations calculation error: ' . $e->getMessage());
        }
        
        $progressPercentage = $campaign->target_amount > 0 
            ? min(100, ($totalAmount / $campaign->target_amount) * 100) 
            : 0;

        return view('admin.campaigns.show', compact(
            'campaign', 
            'totalDonations', 
            'totalAmount', 
            'progressPercentage', 
            'recentDonations'
        ));
    }

    public function edit(Campaign $campaign)
    {
        $users = $this->getActiveUsers();
        $categories = $this->getCategoryOptions();
        
        return view('admin.campaigns.edit', compact('campaign', 'users', 'categories'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $categories = array_keys($this->getCategoryOptions());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_amount' => 'required|numeric|min:1000',
            'category' => 'required|string|in:' . implode(',', $categories),
            'end_date' => 'required|date|after_or_equal:today',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:active,inactive,completed,cancelled'
        ]);

        try {
            DB::beginTransaction();

            $oldImage = $campaign->image;

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('campaigns', 'public');
                $validated['image'] = $imagePath;
            }

            // Jangan reset verification_status kalau tidak diubah
            $validated['verification_status'] = $campaign->verification_status ?? 'pending';

            $campaign->update($validated);

            if (isset($validated['image']) && $oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            DB::commit();

            return redirect()
                ->route('admin.campaigns.index')
                ->with('success', 'Campaign berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();

            if (isset($validated['image']) && Storage::disk('public')->exists($validated['image'])) {
                Storage::disk('public')->delete($validated['image']);
            }

            Log::error('Campaign update error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Campaign $campaign)
    {
        try {
            DB::beginTransaction();

            $donationCount = 0;
            if (Schema::hasTable('donations')) {
                $donationCount = DB::table('donations')
                    ->where('campaign_id', $campaign->id)
                    ->count();
            }
            
            if ($donationCount > 0) {
                return back()->with('error', 'Tidak dapat menghapus campaign yang sudah memiliki donasi.');
            }

            $imagePath = $campaign->image;

            $campaign->delete();

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            DB::commit();

            return redirect()
                ->route('admin.campaigns.index')
                ->with('success', 'Campaign berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Campaign destroy error: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menghapus campaign: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,inactive,completed,cancelled'
        ]);

        try {
            $campaign->update(['status' => $validated['status']]);

            $statusMessages = [
                'active' => 'Campaign diaktifkan',
                'inactive' => 'Campaign dinonaktifkan', 
                'completed' => 'Campaign diselesaikan',
                'cancelled' => 'Campaign dibatalkan'
            ];

            return response()->json([
                'success' => true,
                'message' => $statusMessages[$validated['status']]
            ]);

        } catch (\Exception $e) {
            Log::error('Campaign status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function donations(Campaign $campaign)
    {
        try {
            if (!Schema::hasTable('donations')) {
                return redirect()
                    ->route('admin.campaigns.show', $campaign)
                    ->with('error', 'Tabel donasi tidak ditemukan.');
            }

            $donationsData = DB::table('donations')
                ->where('campaign_id', $campaign->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('admin.campaigns.donations', compact('campaign', 'donationsData'));
            
        } catch (\Exception $e) {
            Log::error('Campaign donations error: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.campaigns.show', $campaign)
                ->with('error', 'Terjadi kesalahan saat memuat data donasi.');
        }
    }

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
                        $campaign->update(['status' => 'active']);
                        $count++;
                        break;
                    case 'deactivate':
                        $campaign->update(['status' => 'inactive']);
                        $count++;
                        break;
                    case 'delete':
                        $donationCount = Schema::hasTable('donations') ? 
                            DB::table('donations')->where('campaign_id', $campaign->id)->count() : 0;
                        if ($donationCount == 0) {
                            if ($campaign->image && Storage::disk('public')->exists($campaign->image)) {
                                Storage::disk('public')->delete($campaign->image);
                            }
                            $campaign->delete();
                            $count++;
                        }
                        break;
                }
            }

            DB::commit();

            $actionMessages = [
                'activate' => 'diaktifkan',
                'deactivate' => 'dinonaktifkan',
                'delete' => 'dihapus'
            ];

            return redirect()
                ->route('admin.campaigns.index')
                ->with('success', "{$count} campaign berhasil {$actionMessages[$validated['action']]}.");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Campaign bulk action error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function getActiveUsers()
    {
        try {
            $query = User::select('id', 'name', 'email');
            
            if (Schema::hasColumn('users', 'role')) {
                $query->where('role', 'user');
            }

            if (Schema::hasColumn('users', 'is_active')) {
                $query->where('is_active', true);
            }
            
            return $query->get();
        } catch (\Exception $e) {
            Log::error('Get active users error: ' . $e->getMessage());
            return User::select('id', 'name', 'email')->get();
        }
    }

    private function getCategoryOptions()
    {
        try {
            if (method_exists(Campaign::class, 'getCategories')) {
                return Campaign::getCategories();
            }

            return [
                'kesehatan' => 'Kesehatan',
                'pendidikan' => 'Pendidikan', 
                'infrastruktur' => 'Infrastruktur',
                'bencana_alam' => 'Bencana Alam',
                'kemanusiaan' => 'Kemanusiaan',
                'lingkungan' => 'Lingkungan'
            ];
        } catch (\Exception $e) {
            Log::error('Get category options error: ' . $e->getMessage());
            return [
                'kesehatan' => 'Kesehatan',
                'pendidikan' => 'Pendidikan', 
                'infrastruktur' => 'Infrastruktur',
                'bencana_alam' => 'Bencana Alam',
                'kemanusiaan' => 'Kemanusiaan',
                'lingkungan' => 'Lingkungan'
            ];
        }
    }

    private function getStatusOptions()
    {
        try {
            if (method_exists(Campaign::class, 'getStatuses')) {
                return Campaign::getStatuses();
            }
            
            return [
                'active' => 'Aktif',
                'inactive' => 'Tidak Aktif',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan'
            ];
        } catch (\Exception $e) {
            Log::error('Get status options error: ' . $e->getMessage());
            return [
                'active' => 'Aktif',
                'inactive' => 'Tidak Aktif',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan'
            ];
        }
    }

    /**
     * Halaman verifikasi campaign
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
     * Approve campaign
     */
    public function verifyApprove($id)
    {
        $campaign = Campaign::findOrFail($id);
        
        $campaign->update([
            'verification_status' => 'approved',
            'status' => 'active', 
        ]);

        return redirect()->route('admin.campaigns.verify')
            ->with('success', 'Campaign berhasil diverifikasi dan diaktifkan.');
    }

    /**
     * Reject campaign
     */
    public function verifyReject($id)
    {
        $campaign = Campaign::findOrFail($id);

        $campaign->update([
            'verification_status' => 'rejected',
            'rejection_reason' => request('rejection_reason') ?? 'Tidak sesuai kebijakan platform.',
        ]);

        return redirect()->route('admin.campaigns.verify')
            ->with('success', 'Campaign berhasil ditolak.');
    }
}