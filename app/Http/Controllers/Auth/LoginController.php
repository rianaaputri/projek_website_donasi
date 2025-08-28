<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Exception;

class LoginController extends Controller
{
    /**
     * ✅ Tampilkan halaman login
     */
    public function showLoginForm()
    {
        try {
            Log::info("Akses halaman login");
            return view('auth.login');
        } catch (Exception $e) {
            Log::error("Gagal load halaman login: " . $e->getMessage());
            abort(500, "Terjadi kesalahan pada server.");
        }
    }

    /**
     * ✅ Proses login user
     */
    public function login(Request $request)
    {
        try {
            Log::info("Proses login dimulai", ['email' => $request->email]);

            $credentials = $request->validate([
                'email'    => ['required', 'email'],
                'password' => ['required'],
            ]);

            if (Auth::attempt($credentials, $request->filled('remember'))) {
                $user = Auth::user();
                Log::info("Login berhasil", ['user_id' => $user->id, 'email' => $user->email]);

                // 🔒 Cek apakah email sudah diverifikasi
                if (method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail()) {
                    Log::warning("User mencoba login tanpa verifikasi email", ['user_id' => $user->id]);

                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    // 🚨 ALERT + LINK ke verifikasi
                    return redirect()->route('login')->with([
                        'warning' => 'Akun Anda belum diverifikasi. 
                            <a href="'.route('verification.notice').'" class="underline text-blue-600">
                                Klik di sini untuk verifikasi email
                            </a>.'
                    ]);
                }

                // 🔒 Regenerasi session untuk keamanan
                $request->session()->regenerate();

                // 🔀 Redirect sesuai role
                return $user->role === 'admin'
                    ? redirect()->intended(route('admin.dashboard'))->with('success', 'Selamat datang Admin!')
                    : redirect()->intended(RouteServiceProvider::HOME)->with('success', 'Login berhasil. Selamat datang!');
            }

            // ⚠️ Jika login gagal → cek user
            $user = User::where('email', $request->email)->first();

            if (! $user) {
                Log::warning("Login gagal: email tidak ditemukan", ['email' => $request->email]);
                return back()->withErrors([
                    'email' => 'Email tidak ditemukan dalam sistem kami.',
                ])->withInput();
            }

            Log::warning("Login gagal: password salah", ['email' => $request->email]);
            return back()->withErrors([
                'password' => 'Password yang Anda masukkan salah.',
            ])->withInput();

        } catch (Exception $e) {
            Log::error("Error saat login: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'Terjadi kesalahan saat login. Silakan coba lagi.');
        }
    }

    /**
     * ✅ Proses logout user
     */
    public function logout(Request $request)
    {
        try {
            $role = auth()->check() ? auth()->user()->role : null;
            $email = auth()->check() ? auth()->user()->email : null;

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info("User logout", ['email' => $email, 'role' => $role]);

            return $role === 'admin'
                ? redirect()->route('login')->with('success', 'Berhasil logout dari akun Admin.')
                : redirect(RouteServiceProvider::HOME)->with('success', 'Berhasil logout.');
        } catch (Exception $e) {
            Log::error("Error saat logout: " . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat logout.');
        }
    }
}
