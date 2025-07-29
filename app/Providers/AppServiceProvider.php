<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{

    public function register()
{
    $this->app->singleton(\App\Services\MidtransService::class, function ($app) {
        return new \App\Services\MidtransService();
    });
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
            ->subject('Verifikasi Email Admin')
            ->line('Klik tombol di bawah untuk verifikasi email kamu.')
            ->action('Verifikasi Sekarang', $url);
        });
    }
    
}
