<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    /**
     * Tampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login.
     */
    public function login(Request $request)
    {
        // Validasi input singkat
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login dengan credentials
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Jika model User menggunakan verifikasi email, pastikan terverifikasi
            if (method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Email kamu belum diverifikasi.');
            }

            // Regenerasi session untuk keamanan
            $request->session()->regenerate();

            // Redirect berdasarkan role — gunakan route constants (RouteServiceProvider) atau named routes
            if ($user->role === 'admin') {
                // Redirect ke admin dashboard
                return redirect()->intended(route('admin.dashboard'));
            }

            // Default: redirect ke home
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Logout user.
     *
     * Admin -> redirected to login page.
     * Regular user -> redirected to home.
     */
    public function logout(Request $request)
    {
        // Tangkap role sebelum logout (karena setelah logout auth()->user() null)
        $role = auth()->check() ? auth()->user()->role : null;

        Auth::logout();

        // Invalidate session & CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect berdasarkan role sebelumnya
        if ($role === 'admin') {
            return redirect()->route('login')->with('success', 'Berhasil logout.');
        }

        return redirect(RouteServiceProvider::HOME)->with('success', 'Berhasil logout.');
    }
}
        