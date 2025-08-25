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
     * Display a listing of all users and admins from users table only.
     */
    public function listAdmins(Request $request)
    {
        try {
            // Ambil data dari tabel users saja
            $query = DB::table('users')
                ->select(
                    'id',
                    'name', 
                    'email',
                    'phone',
                    'address',
                    'role',
                    'is_active',
                    'email_verified_at',
                    'created_at',
                    'updated_at'
                );

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
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active' ? 1 : 0);
            }

            // Filter by verification status
            if ($request->filled('verification')) {
                if ($request->verification === 'verified') {
                    $query->whereNotNull('email_verified_at');
                } else {
                    $query->whereNull('email_verified_at');
                }
            }

            $users = $query->orderBy('created_at', 'desc')->paginate(15);

            // Tambah data statistik untuk setiap user
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

            return view('admin.list-admins', compact('users'));
            
        } catch (\Exception $e) {
            Log::error('User listing error: ' . $e->getMessage());
            
            return back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }

    /**
     * Update user role (change between user and admin).
     */
    public function updateRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'new_role' => 'required|string|in:user,admin',
            'current_role' => 'required|string|in:user,admin'
        ]);

        try {
            DB::beginTransaction();

            $userId = $validated['user_id'];
            $newRole = $validated['new_role'];
            $currentRole = $validated['current_role'];

            // Jika role tidak berubah, tidak perlu update
            if ($currentRole === $newRole) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role sudah sesuai, tidak ada perubahan.'
                ]);
            }

            // Cek apakah user ada
            $user = DB::table('users')->where('id', $userId)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            // Update role di tabel users
            DB::table('users')->where('id', $userId)->update([
                'role' => $newRole,
                'updated_at' => now()
            ]);

            DB::commit();

            $roleNames = [
                'user' => 'User',
                'admin' => 'Admin'
            ];

            return response()->json([
                'success' => true,
                'message' => "Role berhasil diubah menjadi {$roleNames[$newRole]}!"
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Role update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user status (active/inactive).
     */
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'is_active' => 'required|boolean'
        ]);

        try {
            // Cek apakah user ada
            $user = DB::table('users')->where('id', $validated['user_id'])->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            DB::table('users')->where('id', $validated['user_id'])->update([
                'is_active' => $validated['is_active'],
                'updated_at' => now()
            ]);

            $message = $validated['is_active'] ? 'User berhasil diaktifkan' : 'User berhasil dinonaktifkan';

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
     * Display a listing of all users (both regular users and admins).
     */
    public function manageUsers(Request $request)
    {
        return $this->listAdmins($request);
    }

    /**
     * Verify user email.
     */
    public function verifyEmail(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer'
        ]);

        try {
            $userId = $validated['user_id'];

            // Cek apakah user ada
            $user = DB::table('users')->where('id', $userId)->first();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan.'
                ], 404);
            }

            DB::table('users')->where('id', $userId)->update([
                'email_verified_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil diverifikasi'
            ]);

        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show user details.
     */
    public function showUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer'
        ]);

        try {
            $userId = $validated['user_id'];

            $user = DB::table('users')->where('id', $userId)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            // Tambah statistik user
            $user->campaigns_count = 0;
            $user->donations_count = 0;
            $user->donations_total = 0;

            if (Schema::hasTable('campaigns')) {
                $user->campaigns_count = DB::table('campaigns')
                    ->where('user_id', $userId)
                    ->count();
            }
            
            if (Schema::hasTable('donations')) {
                $user->donations_count = DB::table('donations')
                    ->where('user_id', $userId)
                    ->count();
                    
                $user->donations_total = DB::table('donations')
                    ->where('user_id', $userId)
                    ->sum('amount') ?? 0;
            }

            return response()->json([
                'success' => true,
                'user' => $user
            ]);

        } catch (\Exception $e) {
            Log::error('Show user error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display admin dashboard.
     */
    public function dashboard()
    {
        try {
            // Statistik dasar
            $totalUsers = DB::table('users')->count();
            $totalAdmins = DB::table('admins')->count();
            $totalCampaigns = Schema::hasTable('campaigns') ? DB::table('campaigns')->count() : 0;
            $totalDonations = Schema::hasTable('donations') ? DB::table('donations')->sum('amount') : 0;

            return view('admin.dashboard', compact(
                'totalUsers',
                'totalAdmins', 
                'totalCampaigns',
                'totalDonations'
            ));

        } catch (\Exception $e) {
            Log::error('Admin dashboard error: ' . $e->getMessage());
            return view('admin.dashboard')->with('error', 'Terjadi kesalahan saat memuat dashboard.');
        }
    }
}