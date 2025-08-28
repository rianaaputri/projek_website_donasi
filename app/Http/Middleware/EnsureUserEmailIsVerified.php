<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EnsureUserEmailIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = $request->user();

        if ($user && method_exists($user, 'hasVerifiedEmail') && ! $user->hasVerifiedEmail()) {
            Log::warning("User mencoba akses tanpa verifikasi email", [
                'user_id' => $user->id,
                'email'   => $user->email,
                'route'   => $request->path(),
            ]);

            return redirect()->route('verification.notice')
                ->with('error', 'Akun Anda belum bisa mengakses halaman ini. Silakan verifikasi email terlebih dahulu.');
        }

        return $next($request);
    }
}
