<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    /**
     * Handle the email verification link.
     */
public function __invoke(Request $request)
{
    // Cek apakah link valid & belum expired
    if (! URL::hasValidSignature($request)) {
        return redirect()->route('verification.expired');
    }

    $user = $request->user();

    if ($user->hasVerifiedEmail()) {
        return redirect()->route('home')->with('status', 'Email sudah diverifikasi.');
    }

    if ($user->markEmailAsVerified()) {
        event(new Verified($user));
    }

    return redirect()->route('home')->with('status', 'Email berhasil diverifikasi.');
}
}