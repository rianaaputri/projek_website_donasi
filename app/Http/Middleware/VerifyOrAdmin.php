<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyOrAdmin
{
    /**
     * Handle an incoming request.
     * 
     * Admin bisa langsung masuk, user harus verified dulu
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        
        // Kalo admin, langsung lanjut tanpa cek verifikasi
        if ($user && $user->role === 'admin') {
            return $next($request);
        }
        
        // Kalo user biasa, harus verified dulu
        if ($user && !$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
        
        return $next($request);
    }
}