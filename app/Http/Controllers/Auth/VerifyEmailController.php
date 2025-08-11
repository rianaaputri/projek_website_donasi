<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            Log::debug('User email already verified at: ' . $request->user()->email_verified_at);
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        Log::debug('Attempting to verify email for user: ' . $request->user()->email);
        
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
            Log::debug('Email verified successfully at: ' . $request->user()->email_verified_at);
            Log::debug('Verification data: ', [
                'user_id' => $request->user()->id,
                'email' => $request->user()->email,
                'verified_at' => $request->user()->email_verified_at,
            ]);
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}