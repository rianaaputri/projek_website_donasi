<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Donation;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt login first (lebih aman)
        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Login gagal. Email atau password salah.'])->withInput();
        }

        $user = Auth::user();

        // Pastikan user punya role admin
        if ($user->role !== 'admin') {
            Auth::logout();
            return back()->withErrors(['email' => 'Akses ditolak. Hanya admin yang bisa login.']);
        }

        // Pastikan email sudah diverifikasi
        if (! $user->hasVerifiedEmail()) {
            Auth::logout();
            return back()->withErrors(['email' => 'Email belum diverifikasi. Silakan cek email Anda.']);
        }

        // Redirect ke intended (jika ada) atau ke admin dashboard
        return redirect()->intended(route('admin.dashboard'));
    }

    public function showRegister()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // ubah jika perlu ends_with
            'password' => 'required|min:6|confirmed',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        try {
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
            ]);

            // Kirim email verifikasi (pastikan User implements MustVerifyEmail)
            event(new Registered($admin));

            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi sebelum login.');
        } catch (\Exception $e) {
            // Optional: log error with $e->getMessage()
            return back()->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'])->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('/login')->with('success', 'Berhasil logout.');
    }

    // Dashboard method
    public function dashboard()
    {
        // Ambil statistik untuk dashboard
        $stats = [
            'total_campaigns' => Campaign::count(),
            'total_donations' => Donation::count(),
            'total_collected' => Donation::sum('amount'),
            'total_users' => User::where('role', '!=', 'admin')->count(), // Exclude admin dari count
        ];

        // Ambil campaign terbaru
        $recent_campaigns = Campaign::with('donations')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function($campaign) {
                $campaign->current_amount = $campaign->donations->sum('amount');
                return $campaign;
            });

        // Ambil donasi terbaru
        $recent_donations = Donation::with('campaign')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_campaigns', 'recent_donations'));
    }

    // Method untuk menampilkan form add admin
    public function showAddAdminForm()
    {
        return view('admin.add-admin');
    }

    // Method untuk menyimpan admin baru
    public function storeAdmin(Request $request)
    {
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6|confirmed',
    ]);

    try {
        $admin = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.add-admin')
            ->with('success', 'Admin account berhasil dibuat! Email: ' . $admin->email);

    } catch (\Exception $e) {
        return back()
            ->withErrors(['error' => 'Error: ' . $e->getMessage()])
            ->withInput();
    }
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama harus diisi.',
            'name.max' => 'Nama maksimal 255 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        try {
            // Buat admin baru
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'email_verified_at' => now(), // Auto verify admin accounts
            ]);

            // Log aktivitas (optional)
            \Log::info('New admin created', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'created_by' => auth()->user()->email
            ]);

            return redirect()->route('admin.add-admin')->with('success', 'Admin account berhasil dibuat! Admin baru dapat login langsung tanpa verifikasi email.');

        } catch (\Exception $e) {
            // Log error
            \Log::error('Error creating admin', [
                'error' => $e->getMessage(),
                'attempted_email' => $request->email
            ]);

            return back()->withErrors(['error' => 'Terjadi kesalahan saat membuat akun admin. Silakan coba lagi.'])->withInput();
        }
    }

    // Method untuk melihat daftar admin (optional)
    public function listAdmins()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.list-admins', compact('admins'));
    }

    // Method untuk menghapus admin (optional)
    public function deleteAdmin(Request $request, $id)
    {
        try {
            $admin = User::where('role', 'admin')->findOrFail($id);
            
            // Tidak bisa menghapus diri sendiri
            if ($admin->id === auth()->user()->id) {
                return back()->withErrors(['error' => 'Tidak dapat menghapus akun sendiri.']);
            }

            // Pastikan minimal ada 1 admin
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withErrors(['error' => 'Tidak dapat menghapus admin terakhir.']);
            }

            $admin->delete();

            return back()->with('success', 'Admin berhasil dihapus.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus admin.']);
        }
    }
}