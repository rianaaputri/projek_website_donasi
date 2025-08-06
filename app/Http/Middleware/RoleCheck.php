<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleCheck
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $role = null)
    {
        // Debug log untuk troubleshooting (opsional)
        // \Log::info('RoleCheck middleware called', [
        //     'user_id' => auth()->id(),
        //     'user_role' => auth()->user()->role ?? 'no role',
        //     'required_role' => $role,
        //     'url' => $request->url()
        // ]);

        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Jika parameter role diberikan, cek role specific
        if ($role) {
            if ($user->role !== $role) {
                // \Log::warning('Role mismatch', [
                //     'user_role' => $user->role,
                //     'required_role' => $role
                // ]);
                abort(403, "Access denied. {$role} role required. Your role: {$user->role}");
            }
        }

        return $next($request);
    }
}