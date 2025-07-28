<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan admin login dengan guard 'admin'
        if (!Auth::guard('admin')->check()) {
            // Redirect ke halaman login admin jika belum login sebagai admin
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai admin untuk mengakses halaman ini.');
        }

        // Jika admin sudah login, lanjutkan request
        return $next($request);
    }
}