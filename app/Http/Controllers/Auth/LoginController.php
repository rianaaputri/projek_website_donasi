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

            // Verifikasi email (jika diperlukan)
            if (method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail()) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Email kamu belum diverifikasi.');
            }

            // Regenerasi session
            $request->session()->regenerate();

            // ✅ CEK: Apakah ada donasi yang tertunda sebelum login?
            if (session()->has('pending_donation_id')) {
                $donationId = session()->pull('pending_donation_id'); // ambil dan hapus
                return redirect()->route('donation.payment', $donationId);
            }

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        $role = auth()->check() ? auth()->user()->role : null;

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'admin') {
            return redirect()->route('login')->with('success', 'Berhasil logout.');
        }

        return redirect(RouteServiceProvider::HOME)->with('success', 'Berhasil logout.');
    }
}
