<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Jika tidak ada role yang diminta, izinkan
        if (empty($roles)) {
            return $next($request);
        }

        // Cek apakah role user ada di dalam daftar yang diizinkan
        if (!in_array($user->role, $roles)) {
            abort(403, "Access denied. Role required: " . implode(', ', $roles) . ". Your role: {$user->role}");
        }

        return $next($request);
    }
}