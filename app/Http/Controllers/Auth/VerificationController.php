<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VerificationController extends Controller
{
    use VerifiesEmails;

    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('auth')->only(['notice', 'resend']);
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function notice(Request $request)
    {
        $expires_at = $request->user()->verificationLinkExpiresAt();
        return view('auth.verify-email', compact('expires_at'));
    }

    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        // Expired check
        if (now()->greaterThan($user->verificationLinkExpiresAt())) {
            return redirect()->route('verification.notice')
                ->with('error', 'expired');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('message', 'already-verified');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        Auth::logout();
        return redirect()->route('login')->with('success', 'Email berhasil diverifikasi! Silakan login.');
    }

    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('resent', true);
    }
}
