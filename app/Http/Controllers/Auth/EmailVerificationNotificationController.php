<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Kalau sudah terverifikasi, langsung arahkan ke dashboard
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // (Opsional) Batasi kirim ulang setiap 5 menit
        if ($user->last_verification_sent_at && now()->diffInMinutes($user->last_verification_sent_at) < 5) {
            return back()->with('error', 'Please wait 5 minutes before requesting another verification email.');
        }

        // Set waktu expired link (10 menit dari sekarang)
        $user->email_verification_expires_at = now()->addMinutes(10);
        $user->last_verification_sent_at = now();
        $user->save();

        // Kirim email verifikasi
        $user->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
