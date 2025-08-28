<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set email verification link to expire in 5 minutes
        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(5), // 5 minutes expiry
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });

        // Optional: Customize email verification message
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Email Address')
                ->greeting('Halo!')
                ->line('Klik tombol di bawah untuk memverifikasi email address kamu.')
                ->action('Verifikasi Email', $url)
                ->line('Link verifikasi ini akan kedaluwarsa dalam 5 menit.')
                ->line('Jika kamu tidak membuat akun, tidak perlu melakukan apa-apa.')
                ->salutation('Regards, Tim ' . config('app.name'));
        });
    }
}