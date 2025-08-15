<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            // kalau belum login, arahkan ke login
            return redirect('/login');
        }

        if (Auth::user()->role !== 'admin') {
            // kalau login tapi bukan admin, arahkan ke halaman user
            return redirect('/home'); 
        }

        return $next($request);
    }
}
