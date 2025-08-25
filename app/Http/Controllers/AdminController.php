<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        try {
            $query = User::select('*');

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Filter by role
            if ($request->filled('role') && Schema::hasColumn('users', 'role')) {
                $query->where('role', $request->role);
            }

            // Filter by status
            if ($request->filled('status') && Schema::hasColumn('users', 'is_active')) {
                $query->where('is_active', $request->status === 'active');
            }

            // Filter by verification status
            if ($request->filled('verification') && Schema::hasColumn('users', 'email_verified_at')) {
                if ($request->verification === 'verified') {
                    $query->whereNotNull('email_verified_at');
                } else {
                    $query->whereNull('email_verified_at');
                }
            }

            $users = $query->latest()->paginate(15);

            // Tambah data statistik manual
            foreach ($users as $user) {
                $user->campaigns_count = 0;
                $user->donations_count = 0;
                $user->donations_total = 0;
                
                if (Schema::hasTable('campaigns')) {
                    $user->campaigns_count = DB::table('campaigns')
                        ->where('user_id', $user->id)
                        ->count();
                }
                
                if (Schema::hasTable('donations')) {
                    $user->donations_count = DB::table('donations')
                        ->where('user_id', $user->id)
                        ->count();
                        
                    $user->donations_total = DB::table('donations')
                        ->where('user_id', $user->id)
                        ->sum('amount') ?? 0;
                }
            }

            $roles = $this->getRoleOptions();
            $statuses = $this->getStatusOptions();

            return view('admin.users.index', compact('users', 'roles', 'statuses'));
            
        } catch (\Exception $e) {
            Log::error('User index error: ' . $e->getMessage());
            
            $users = User::paginate(15);
            $roles = [];
            $statuses = [];
            
            return view('admin.users.index', compact('users', 'roles', 'statuses'))
                ->with('error', 'Terjadi kesalahan saat memuat data users.');
        }
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = $this->getRoleOptions();
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $roles = array_keys($this->getRoleOptions());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'role' => 'required|string|in:' . implode(',', $roles),
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $validated['avatar'] = $avatarPath;
            }

            // Hash password
            $validated['password'] = Hash::make($validated['password']);
            
            // Default values
            $validated['is_active'] = $validated['is_active'] ?? true;
            $validated['email_verified_at'] = now(); // Auto verify admin created users

            $user = User::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();

            if (isset($validated['avatar']) && Storage::disk('public')->exists($validated['avatar'])) {
                Storage::disk('public')->delete($validated['avatar']);
            }

            Log::error('User store error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat membuat user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $campaignsCount = 0;
        $donationsCount = 0;
        $donationsTotal = 0;
        $recentCampaigns = collect();
        $recentDonations = collect();
        
        try {
            if (Schema::hasTable('campaigns')) {
                $campaignsCount = DB::table('campaigns')
                    ->where('user_id', $user->id)
                    ->count();
                    
                $recentCampaignsData = DB::table('campaigns')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                $recentCampaigns = collect($recentCampaignsData);
            }

            if (Schema::hasTable('donations')) {
                $donationsCount = DB::table('donations')
                    ->where('user_id', $user->id)
                    ->count();
                    
                $donationsTotal = DB::table('donations')
                    ->where('user_id', $user->id)
                    ->sum('amount') ?? 0;

                $recentDonationsData = DB::table('donations')
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
                
                $recentDonations = collect($recentDonationsData);
            }
            
        } catch (\Exception $e) {
            Log::error('User statistics calculation error: ' . $e->getMessage());
        }

        return view('admin.users.show', compact(
            'user',
            'campaignsCount',
            'donationsCount', 
            'donationsTotal',
            'recentCampaigns',
            'recentDonations'
        ));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $roles = $this->getRoleOptions();
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $roles = array_keys($this->getRoleOptions());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'role' => 'required|string|in:' . implode(',', $roles),
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $oldAvatar = $user->avatar;

            if ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $validated['avatar'] = $avatarPath;
            }

            // Only update password if provided
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $validated['is_active'] = $validated['is_active'] ?? false;

            $user->update($validated);

            // Delete old avatar if new one uploaded
            if (isset($validated['avatar']) && $oldAvatar && Storage::disk('public')->exists($oldAvatar)) {
                Storage::disk('public')->delete($oldAvatar);
            }

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();

            if (isset($validated['avatar']) && Storage::disk('public')->exists($validated['avatar'])) {
                Storage::disk('public')->delete($validated['avatar']);
            }

            Log::error('User update error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            // Check if user has campaigns
            $campaignCount = 0;
            if (Schema::hasTable('campaigns')) {
                $campaignCount = DB::table('campaigns')
                    ->where('user_id', $user->id)
                    ->count();
            }
            
            if ($campaignCount > 0) {
                return back()->with('error', 'Tidak dapat menghapus user yang memiliki campaign aktif.');
            }

            // Check if user has donations
            $donationCount = 0;
            if (Schema::hasTable('donations')) {
                $donationCount = DB::table('donations')
                    ->where('user_id', $user->id)
                    ->count();
            }
            
            if ($donationCount > 0) {
                return back()->with('error', 'Tidak dapat menghapus user yang memiliki riwayat donasi.');
            }

            $avatarPath = $user->avatar;

            $user->delete();

            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            DB::commit();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User berhasil dihapus!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User destroy error: ' . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menghapus user: ' . $e->getMessage());
        }
    }

    /**
     * Update user status (active/inactive).
     */
    public function updateStatus(Request $request, User $user)
    {
        if (!Schema::hasColumn('users', 'is_active')) {
            return response()->json([
                'success' => false,
                'message' => 'Fitur status tidak tersedia'
            ], 400);
        }

        $validated = $request->validate([
            'is_active' => 'required|boolean'
        ]);

        try {
            $user->update(['is_active' => $validated['is_active']]);

            $message = $validated['is_active'] ? 'User diaktifkan' : 'User dinonaktifkan';

            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('User status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify user email.
     */
    public function verifyEmail(User $user)
    {
        try {
            $user->update([
                'email_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email user berhasil diverifikasi'
            ]);

        } catch (\Exception $e) {
            Log::error('User email verification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show user's campaigns.
     */
    public function campaigns(User $user)
    {
        try {
            if (!Schema::hasTable('campaigns')) {
                return redirect()
                    ->route('admin.users.show', $user)
                    ->with('error', 'Tabel campaign tidak ditemukan.');
            }

            $campaigns = DB::table('campaigns')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('admin.users.campaigns', compact('user', 'campaigns'));
            
        } catch (\Exception $e) {
            Log::error('User campaigns error: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Terjadi kesalahan saat memuat data campaign.');
        }
    }

    /**
     * Show user's donations.
     */
    public function donations(User $user)
    {
        try {
            if (!Schema::hasTable('donations')) {
                return redirect()
                    ->route('admin.users.show', $user)
                    ->with('error', 'Tabel donasi tidak ditemukan.');
            }

            $donations = DB::table('donations')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('admin.users.donations', compact('user', 'donations'));
            
        } catch (\Exception $e) {
            Log::error('User donations error: ' . $e->getMessage());
            
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Terjadi kesalahan saat memuat data donasi.');
        }
    }

    /**
     * Bulk actions for users.
     */
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,verify,delete',
            'users' => 'required|array|min:1',
            'users.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $users = User::whereIn('id', $validated['users'])->get();
            $count = 0;

            foreach ($users as $user) {
                switch ($validated['action']) {
                    case 'activate':
                        if (Schema::hasColumn('users', 'is_active')) {
                            $user->update(['is_active' => true]);
                            $count++;
                        }
                        break;
                    case 'deactivate':
                        if (Schema::hasColumn('users', 'is_active')) {
                            $user->update(['is_active' => false]);
                            $count++;
                        }
                        break;
                    case 'verify':
                        $user->update(['email_verified_at' => now()]);
                        $count++;
                        break;
                    case 'delete':
                        // Check dependencies before deleting
                        $campaignCount = Schema::hasTable('campaigns') ? 
                            DB::table('campaigns')->where('user_id', $user->id)->count() : 0;
                        $donationCount = Schema::hasTable('donations') ? 
                            DB::table('donations')->where('user_id', $user->id)->count() : 0;
                            
                        if ($campaignCount == 0 && $donationCount == 0) {
                            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                                Storage::disk('public')->delete($user->avatar);
                            }
                            $user->delete();
                            $count++;
                        }
                        break;
                }
            }

            DB::commit();

            $actionMessages = [
                'activate' => 'diaktifkan',
                'deactivate' => 'dinonaktifkan',
                'verify' => 'diverifikasi',
                'delete' => 'dihapus'
            ];

            return redirect()
                ->route('admin.users.index')
                ->with('success', "{$count} user berhasil {$actionMessages[$validated['action']]}.");

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('User bulk action error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get role options.
     */
    private function getRoleOptions()
    {
        try {
            if (method_exists(User::class, 'getRoles')) {
                return User::getRoles();
            }

            return [
                'user' => 'User',
                'admin' => 'Admin',
                'moderator' => 'Moderator'
            ];
        } catch (\Exception $e) {
            Log::error('Get role options error: ' . $e->getMessage());
            return [
                'user' => 'User',
                'admin' => 'Admin'
            ];
        }
    }

    /**
     * Get status options.
     */
    private function getStatusOptions()
    {
        return [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif'
        ];
    }
}