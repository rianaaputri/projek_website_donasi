<?php

namespace App\Http\Controllers;

use App\Models\Admin; 
use App\Models\User;  
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException; // Pastikan ini diimport
use Illuminate\Support\Facades\Log; // Pastikan ini diimport

class AuthController extends Controller
{
    // --- Metode untuk Menampilkan Halaman Login (Universal) ---
    public function showLogin()
    {
        return view('admin.auth.login'); 
    }

    // --- Metode untuk Menangani Proses Login (Universal) ---
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        Log::info('--- LOGIN ATTEMPT START ---');
        Log::info('Request IP: ' . $request->ip());
        Log::info('User-Agent: ' . $request->header('User-Agent'));
        Log::info('Attempting login for email: ' . $request->email);
        Log::info('Remember me: ' . ($request->filled('remember') ? 'Yes' : 'No'));

        // ===============================================================
        // DEBUGGING STEP 1: Coba login sebagai ADMIN
        // ===============================================================
        $adminAttempt = Auth::guard('admin')->attempt($credentials, $request->filled('remember'));
        
        if ($adminAttempt) {
            $request->session()->regenerate();
            Log::info('Admin login SUCCESS for email: ' . $request->email);
            Log::info('Admin current user after login: ' . json_encode(Auth::guard('admin')->user()));
            Log::info('Redirecting admin to: ' . session()->get('url.intended', route('admin.dashboard')));
            return redirect()->intended(route('admin.dashboard')); 
        }
        
        Log::warning('Admin login FAILED for email: ' . $request->email);
        Log::warning('Reasons for admin login failure (check credentials, password hash, config/auth.php, and admin table):');
        // Anda bisa menambahkan debug lebih lanjut di sini jika perlu, misal:
        // dump(Auth::guard('admin')->validate($credentials)); // ini akan return boolean true/false
        // dump($credentials); 

        // ===============================================================
        // DEBUGGING STEP 2: Coba login sebagai USER BIASA (jika admin gagal)
        // ===============================================================
        $userAttempt = Auth::guard('web')->attempt($credentials, $request->filled('remember'));

        if ($userAttempt) {
            $request->session()->regenerate();
            Log::info('User login SUCCESS for email: ' . $request->email);
            Log::info('User current user after login: ' . json_encode(Auth::guard('web')->user()));
            Log::info('Redirecting user to: ' . session()->get('url.intended', route('home')));
            return redirect()->intended(route('home')); 
        }

        // ===============================================================
        // DEBUGGING STEP 3: Kedua login gagal
        // ===============================================================
        Log::error('Both admin and user login FAILED for email: ' . $request->email);
        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    // --- Metode untuk Menangani Proses Logout (Universal) ---
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login');
    }

    // --- Metode showRegister dan register admin Anda ---
    // Jika Anda ingin admin dan user register dari tempat berbeda, biarkan metode ini di AuthController
    // dan pastikan form register admin mengarah ke AuthController@register
    public function showRegister()
    {
        return view('auth.admin-register'); // Contoh, sesuaikan dengan view register admin Anda
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email', 
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.unique' => 'Email sudah terdaftar!',
            'password.required' => 'Password wajib diisi!',
            'password.min' => 'Password minimal 6 karakter!',
            'password.confirmed' => 'Konfirmasi password tidak cocok!',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Langsung login admin setelah register
        Auth::guard('admin')->login($admin); 
        event(new Registered($admin));

        // Arahkan ke dashboard admin setelah register dan login
        return redirect()->route('admin.dashboard')
            ->with('success', 'Registrasi admin berhasil! Silakan verifikasi email Anda.');
    }
}