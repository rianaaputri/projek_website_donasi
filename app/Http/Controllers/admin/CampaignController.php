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
            // Use raw query builder to avoid relationship issues
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

            // Manually add donation counts and sums for each campaign
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

            // Get filter options
            $categories = $this->getCategoryOptions();
            $statuses = $this->getStatusOptions();

            return view('admin.campaigns.index', compact('campaigns', 'categories', 'statuses'));
            
        } catch (\Exception $e) {
            Log::error('Campaign index error: ' . $e->getMessage());
            
            // Ultimate fallback
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
        // Fix: Check if is_active column exists, if not just get users with role 'user'
        $users = $this->getActiveUsers();
        $categories = Campaign::getCategories();
        
        return view('admin.campaigns.create', compact('users', 'categories'));
    }

    /**
     * Store a newly created campaign.
     */
   /**
 * Store a newly created campaign.
 */
public function store(Request $request)
{
    $categories = array_keys(Campaign::getCategories());
    
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

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaigns', 'public');
            $validated['image'] = $imagePath;
        }

        // Set default status if not provided
        $validated['status'] = $validated['status'] ?? 'active';
        
        // HAPUS BARIS INI yang menyebabkan error:
        // $validated['current_amount'] = 0;
        
        // Set collected_amount to 0 instead (if column exists)
        $validated['collected_amount'] = 0;

        $campaign = Campaign::create($validated);

        DB::commit();

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign berhasil dibuat!');

    } catch (\Exception $e) {
        DB::rollback();
        
        // Delete uploaded image if exists
        if (isset($validated['image']) && Storage::disk('public')->exists($validated['image'])) {
            Storage::disk('public')->delete($validated['image']);
        }

        return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat membuat campaign: ' . $e->getMessage());
    }
}

    /**
     * Display the specified campaign.
     */
    public function show(Campaign $campaign)
    {
        // Initialize default values
        $totalDonations = 0;
        $totalAmount = 0;
        $recentDonations = collect();
        
        try {
            // Calculate donation statistics safely
            if (Schema::hasTable('donations')) {
                $totalDonations = DB::table('donations')
                    ->where('campaign_id', $campaign->id)
                    ->count();
                    
                $totalAmount = DB::table('donations')
                    ->where('campaign_id', $campaign->id)
                    ->sum('amount') ?? 0;
                
                // Get recent donations with raw query to avoid relationship issues
                $recentDonationsData = DB::table('donations')
                    ->where('campaign_id', $campaign->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();
                
                $recentDonations = collect($recentDonationsData);
            }
            
        } catch (\Exception $e) {
            Log::error('Campaign donations calculation error: ' . $e->getMessage());
            // Keep default values
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

    /**
     * Show the form for editing the campaign.
     */
    public function edit(Campaign $campaign)
    {
        // Fix: Use helper method to get active users
        $users = $this->getActiveUsers();
        $categories = Campaign::getCategories();
        
        return view('admin.campaigns.edit', compact('campaign', 'users', 'categories'));
    }

    /**
     * Update the specified campaign.
     */
    public function update(Request $request, Campaign $campaign)
{
    $categories = array_keys(Campaign::getCategories() ?? []);

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

        // Upload gambar baru kalau ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaigns', 'public');
            $validated['image'] = $imagePath;
        }

        $campaign->update($validated);

        // Hapus gambar lama kalau ada gambar baru
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

        return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}


    /**
     * Remove the specified campaign.
     */
    public function destroy(Campaign $campaign)
    {
        try {
            DB::beginTransaction();

            // Check if campaign has donations using raw query
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

            // Delete image file
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            DB::commit();

            return redirect()
                ->route('admin.campaigns.index')
                ->with('success', 'Campaign berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Terjadi kesalahan saat menghapus campaign: ' . $e->getMessage());
        }
    }

    /**
     * Update campaign status.
     */
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
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get campaign donations.
     */
    public function donations(Campaign $campaign)
    {
        try {
            if (!Schema::hasTable('donations')) {
                return redirect()
                    ->route('admin.campaigns.show', $campaign)
                    ->with('error', 'Tabel donasi tidak ditemukan.');
            }

            // Get donations using raw query to avoid relationship issues
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

    /**
     * Debug method to check campaign relationships
     */
    public function debug(Campaign $campaign)
    {
        if (!app()->environment('local')) {
            abort(404);
        }

        $debug = [
            'campaign_id' => $campaign->id,
            'campaign_methods' => get_class_methods($campaign),
            'donations_table_exists' => Schema::hasTable('donations'),
            'users_table_exists' => Schema::hasTable('users'),
            'donation_model_exists' => class_exists('App\Models\Donation'),
        ];

        if (Schema::hasTable('donations')) {
            $debug['donations_columns'] = Schema::getColumnListing('donations');
            $debug['donations_count'] = DB::table('donations')->where('campaign_id', $campaign->id)->count();
        }

        return response()->json($debug);
    }

    /**
     * Get available campaign categories.
     * @deprecated Use Campaign::getCategories() instead
     */
    private function getCampaignCategories()
    {
        return Campaign::getCategories();
    }

    /**
     * Bulk actions for campaigns.
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
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper method to get active users based on available columns
     */
    private function getActiveUsers()
    {
        $query = User::select('id', 'name', 'email');
        
        // Check if we have role functionality
        if (method_exists(User::class, 'role')) {
            $query->role('user');
        } else {
            // If no role method, check if role column exists
            if (Schema::hasColumn('users', 'role')) {
                $query->where('role', 'user');
            }
        }
        
        // Check if is_active column exists
        if (Schema::hasColumn('users', 'is_active')) {
            $query->where('is_active', true);
        } else if (method_exists(User::class, 'active')) {
            // If there's an active scope but no is_active column, 
            // it might be using a different column name
            try {
                $query->active();
            } catch (\Exception $e) {
                // If active scope fails, just continue without it
            }
        }
        
        return $query->get();
    }

    /**
     * Get category options safely
     */
    private function getCategoryOptions()
    {
        try {
            if (method_exists(Campaign::class, 'getCategories')) {
                return Campaign::getCategories();
            }
            
            // Fallback default categories
            return [
                'kesehatan' => 'Kesehatan',
                'pendidikan' => 'Pendidikan', 
                'infrastruktur' => 'Infrastruktur',
                'bencana_alam' => 'Bencana Alam',
                'kemanusiaan' => 'Kemanusiaan',
                'lingkungan' => 'Lingkungan'
            ];
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get status options safely
     */
    private function getStatusOptions()
    {
        try {
            if (method_exists(Campaign::class, 'getStatuses')) {
                return Campaign::getStatuses();
            }
            
            // Fallback default statuses
            return [
                'active' => 'Aktif',
                'inactive' => 'Tidak Aktif',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan'
            ];
        } catch (\Exception $e) {
            return [];
        }
    }
}