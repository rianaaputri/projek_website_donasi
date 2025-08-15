<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class VerificationController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function __construct()
    {
        $this->middleware('auth')->only(['notice', 'resend']);
        // Remove signed middleware dari constructor karena kita handle manual
        $this->middleware('throttle:6,1')->only(['verify', 'resend']);
    }

    /**
     * Show the email verification notice
     */
    public function notice(Request $request)
    {
        try {
            $user = $request->user();
            
            // Check if user exists
            if (!$user) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }
            
            // Check if user is already verified
            if ($user->hasVerifiedEmail()) {
                return redirect()->route('dashboard');
            }

            return view('auth.verify-email', ['user' => $user]);
        } catch (\Exception $e) {
            Log::error('Email verification notice error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan. Silakan login kembali.');
        }
    }

    /**
     * Verify the user's email address
     */
    public function verify(Request $request, $id, $hash)
    {
        try {
            // Find user by ID
            $user = User::findOrFail($id);
            
            // Check if hash matches
            if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
                // Login user untuk bisa akses verification notice
                Auth::login($user);
                return redirect()->route('verification.notice')->with('error', 'Link verifikasi tidak valid. Silakan minta link baru.');
            }

            // Check if link has expired (default 5 minutes)
            if (! $request->hasValidSignature()) {
                // Login user untuk bisa akses verification notice
                Auth::login($user);
                return redirect()->route('verification.notice')->with('error', 'Link verifikasi sudah kadaluarsa. Silakan minta link baru.');
            }

            // Check if email is already verified
            if ($user->hasVerifiedEmail()) {
                Auth::login($user);
                return redirect()->route('dashboard')->with('success', 'Email sudah terverifikasi sebelumnya.');
            }

            // Mark email as verified
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
                Log::info('User email verified: ' . $user->email);
                
                // Login user after successful verification
                Auth::login($user);
                
                return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi! Selamat datang!');
            } else {
                Log::error('Failed to mark email as verified for user: ' . $user->email);
                Auth::login($user);
                return redirect()->route('verification.notice')->with('error', 'Gagal memverifikasi email. Silakan coba lagi.');
            }
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('login')->with('error', 'Link verifikasi tidak valid. Silakan registrasi ulang.');
        } catch (\Exception $e) {
            Log::error('Email verification error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat memverifikasi email.');
        }
    }

    /**
     * Resend email verification notification
     */
    public function resend(Request $request)
    {
        try {
            $user = $request->user();
            
            // Check if user exists
            if (!$user) {
                return redirect()->route('login')->with('error', 'Sesi telah berakhir. Silakan login kembali.');
            }

            // Check if user is already verified
            if ($user->hasVerifiedEmail()) {
                return redirect()->route('dashboard');
            }

            // Check cooldown (2 minutes)
            $lastResent = session('last_resent_at');
            if ($lastResent && strtotime($lastResent) + 120 > time()) {
                $remainingSeconds = (strtotime($lastResent) + 120) - time();
                return back()->with('error', "Tunggu {$remainingSeconds} detik lagi untuk mengirim ulang email.");
            }

            // Send verification email
            $user->sendEmailVerificationNotification();
            Log::info('Verification email resent to: ' . $user->email);
            
            // Store last resent time in session
            session(['last_resent_at' => date('Y-m-d H:i:s')]);
            
            return back()->with('resent', true)->with('status', 'Link verifikasi telah dikirim ulang ke email Anda.');
            
        } catch (\Exception $e) {
            Log::error('Resend verification email error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengirim ulang email verifikasi. Silakan coba lagi.');
        }
    }
}