<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class RouteServiceProvider extends ServiceProvider
{
   
    public const HOME = '/';

    public function boot(): void
    {
        // ...existing code...

        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });

        // Add this to redirect unauthenticated users
        $this->app['router']->middlewareGroup('admin', [
            \App\Http\Middleware\AdminMiddleware::class,
        ]);
    }
}
