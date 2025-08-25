<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan form login
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // ✅ Cek apakah email sudah diverifikasi
        if (method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirect ke halaman login dengan pesan + link verifikasi
            return redirect()->route('login')->with('warning', 
                'Akun anda belum diverifikasi. 
                 <a href="'.route('verification.notice').'" class="fw-bold">Klik di sini untuk verifikasi</a>'
            );
        }// ✅ Cek apakah email sudah diverifikasi
if (method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail()) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Redirect balik ke login, tapi kasih pesan + link
    return redirect()->route('login')
        ->with('warning', 'Akun anda belum bisa login, silakan verifikasi email terlebih dahulu. 
            <a href="'.route('verification.notice').'" class="fw-bold">Klik di sini untuk verifikasi</a>');
}


        // ✅ Kalau sudah diverifikasi, lanjut login normal
        $request->session()->regenerate();

        // Redirect berdasarkan role
        return $user->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    }

    /**
     * Proses logout
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Berhasil logout.');
    }
}
