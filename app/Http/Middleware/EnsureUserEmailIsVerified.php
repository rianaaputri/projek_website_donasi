<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserEmailIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Pastikan user sudah login
        if (Auth::check()) {
            $user = Auth::user();

            // Jika belum verifikasi email
            if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
                // Logout otomatis kalau mau paksa keluar (opsional)
                // Auth::logout();
                // return redirect()->route('login')->with('warning', 'Silakan verifikasi email terlebih dahulu.');

                // Atau arahkan ke halaman notice verifikasi
                return redirect()->route('verification.notice')
                    ->with('warning', 'Silakan verifikasi email terlebih dahulu untuk mengakses halaman ini.');
            }
        }

        return $next($request);
    }
}
