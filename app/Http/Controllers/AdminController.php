<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role.check:admin']);
    }

    /**
     * Tampilkan semua user (termasuk admin).
     */
    public function listAdmins(Request $request)
    {
        try {
            $query = User::query()
                ->select('id', 'name', 'email', 'phone', 'address', 'role', 'is_active', 'email_verified_at', 'created_at');

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Filter role
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            // Filter status aktif
            if ($request->filled('status')) {
                $query->where('is_active', $request->status === 'active' ? 1 : 0);
            }

            // Filter verifikasi
            if ($request->filled('verification')) {
                if ($request->verification === 'verified') {
                    $query->whereNotNull('email_verified_at');
                } else {
                    $query->whereNull('email_verified_at');
                }
            }

            $users = $query->orderBy('created_at', 'desc')->paginate(15);

            // Statistik tiap user
            foreach ($users as $user) {
                $user->campaigns_count = Schema::hasTable('campaigns')
                    ? DB::table('campaigns')->where('user_id', $user->id)->count()
                    : 0;

                $user->donations_count = Schema::hasTable('donations')
                    ? DB::table('donations')->where('user_id', $user->id)->count()
                    : 0;

                $user->donations_total = Schema::hasTable('donations')
                    ? DB::table('donations')->where('user_id', $user->id)->sum('amount')
                    : 0;
            }

            return view('admin.list-admins', compact('users'));
        } catch (\Exception $e) {
            Log::error('User listing error: ' . $e->getMessage());
            return back()->with('error', 'Gagal memuat data user.');
        }
    }

    /**
     * Update role user.
     */
    public function updateRole(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'new_role' => 'required|string|in:user,admin,campaign_creator',
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);

            if ($user->id === auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa ubah role akun sendiri.']);
            }

            if ($user->role === $validated['new_role']) {
                return response()->json(['success' => false, 'message' => 'Role sudah sesuai, tidak ada perubahan.']);
            }

            $oldRole = $user->role;
            $user->role = $validated['new_role'];
            $user->save();

            Log::info("Role {$user->email} diubah dari {$oldRole} ke {$user->role} oleh " . auth()->user()->email);

            return response()->json(['success' => true, 'message' => "Role berhasil diubah menjadi {$user->role}."]);
        } catch (\Exception $e) {
            Log::error('Role update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengubah role.']);
        }
    }

    /**
     * Update status aktif/nonaktif.
     */
    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'is_active' => 'required|boolean'
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);

            if ($user->id === auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Tidak bisa ubah status akun sendiri.']);
            }

            $user->is_active = $validated['is_active'];
            $user->save();

            $msg = $validated['is_active'] ? 'User berhasil diaktifkan' : 'User berhasil dinonaktifkan';

            return response()->json(['success' => true, 'message' => $msg]);
        } catch (\Exception $e) {
            Log::error('User status update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengubah status user.']);
        }
    }

    /**
     * Verifikasi email user secara manual.
     */
    public function verifyEmail(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);

            if ($user->hasVerifiedEmail()) {
                return response()->json(['success' => false, 'message' => 'Email sudah terverifikasi.']);
            }

            $user->email_verified_at = now();
            $user->save();

            return response()->json(['success' => true, 'message' => 'Email berhasil diverifikasi.']);
        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal verifikasi email.']);
        }
    }

    /**
     * Detail user.
     */
    public function showUser(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer|exists:users,id'
        ]);

        try {
            $user = User::findOrFail($validated['user_id']);

            $user->campaigns_count = Schema::hasTable('campaigns')
                ? DB::table('campaigns')->where('user_id', $user->id)->count()
                : 0;

            $user->donations_count = Schema::hasTable('donations')
                ? DB::table('donations')->where('user_id', $user->id)->count()
                : 0;

            $user->donations_total = Schema::hasTable('donations')
                ? DB::table('donations')->where('user_id', $user->id)->sum('amount')
                : 0;

            return response()->json(['success' => true, 'user' => $user]);
        } catch (\Exception $e) {
            Log::error('Show user error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menampilkan detail user.']);
        }
    }

    /**
     * Dashboard admin.
     */
    public function dashboard()
    {
        try {
            $totalUsers = User::count();
            $totalAdmins = User::where('role', 'admin')->count();
            $totalCampaigns = Schema::hasTable('campaigns') ? DB::table('campaigns')->count() : 0;
            $totalDonations = Schema::hasTable('donations') ? DB::table('donations')->sum('amount') : 0;

            return view('admin.dashboard', compact(
                'totalUsers',
                'totalAdmins',
                'totalCampaigns',
                'totalDonations'
            ));
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return view('admin.dashboard')->with('error', 'Gagal memuat dashboard.');
        }
    }
}
