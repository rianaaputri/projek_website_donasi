<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyOrAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Jika user adalah admin (login dengan guard 'admin'), biarkan dia lewat tanpa verifikasi email user
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Jika user adalah user biasa (login dengan guard 'web') dan belum verifikasi email
        if (Auth::guard('web')->check() && !Auth::guard('web')->user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // Lanjutkan jika user biasa sudah verifikasi atau admin
        return $next($request);
    }
}