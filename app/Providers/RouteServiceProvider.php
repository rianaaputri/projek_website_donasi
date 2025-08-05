<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Jalur default untuk redirect pengguna setelah login.
     */
    public const HOME = '/';
    public const ADMIN_HOME = '/admin/dashboard';

    /**
     * Boot method untuk routing & rate limiting.
     */
    public function boot(): void
    {
        $this->configureRateLimiting();

        // Definisikan route web dan api
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });

        // Jika Anda ingin membuat group middleware 'admin' (tidak umum di ServiceProvider)
        // lebih baik pindahkan ini ke Kernel.php jika digunakan secara global
        // Namun jika tetap ingin di sini, pastikan AdminMiddleware ada
        Route::middlewareGroup('admin', [
            \App\Http\Middleware\AdminMiddleware::class,
        ]);
    }

    /**
     * Konfigurasi Rate Limiting (default Laravel).
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
