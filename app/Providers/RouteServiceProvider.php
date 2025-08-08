<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class RouteServiceProvider extends ServiceProvider
{
    // // Ganti ini:
    // public const HOME = '/dashboard';
    
    // Jadi ini (atau hapus sama sekali):
    public const HOME = '/';
}
