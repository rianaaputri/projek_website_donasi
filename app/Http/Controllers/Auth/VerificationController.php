<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function show()
    {
        $expires_at = auth()->user()->getVerificationExpiryTime();
        return view('auth.verify-email', compact('expires_at'));
    }

    public function verify(EmailVerificationRequest $request)
    {
        $user = $request->user();

        if ($user->isVerificationLinkExpired()) {
            return redirect()->route('verification.notice')
                ->with('error', 'expired');
        }

        $request->fulfill();

        auth()->logout();

        return redirect()->route('login')
            ->with('success', 'Email berhasil diverifikasi! Silakan login.');
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }
}
