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
        $this->middleware('throttle:6,1')->only(['verify', 'resend']);
    }

    /**
     * ✅ Halaman notice (belum verifikasi / link expired)
     */
    public function notice(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')
                ->with('success', 'Email sudah terverifikasi.');
        }

        // kirim flag expired kalau ada
        $expired = session('expired', false);

        return view('auth.verify-email', compact('user', 'expired'));
    }

    /**
     * ✅ Proses verifikasi email
     */
    public function verify(EmailVerificationRequest $request)
    {
        $user = User::findOrFail($request->route('id'));

        // Validasi hash (link kadaluarsa / invalid)
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            Log::warning("❌ Link verifikasi invalid / expired", [
                'user_id' => $user->id,
                'email'   => $user->email,
            ]);

            return redirect()->route('verification.notice')
                ->with('expired', true) // flag untuk view
                ->with('error', 'Oops! Link verifikasi Anda sudah tidak berlaku. Silakan minta link baru.');
        }

        // Kalau sudah diverifikasi sebelumnya
        if ($user->hasVerifiedEmail()) {
            Auth::login($user);
            return redirect()->route('dashboard')
                ->with('success', 'Email sudah diverifikasi sebelumnya.');
        }

        // Proses verifikasi baru
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            Log::info('✅ Email verified: ' . $user->email);
        }

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    }

    /**
     * ✅ Kirim ulang email verifikasi
     */
    public function resend(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login')
                ->with('error', 'Sesi telah berakhir. Silakan login kembali.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard')
                ->with('success', 'Email sudah diverifikasi.');
        }

        // Cooldown 2 menit
        $lastResent = session('last_resent_at');
        if ($lastResent && now()->diffInSeconds($lastResent) < 120) {
            $remaining = 120 - now()->diffInSeconds($lastResent);
            return back()->with('error', "Tunggu {$remaining} detik lagi untuk mengirim ulang email.");
        }

        $user->sendEmailVerificationNotification();
        session(['last_resent_at' => now()]);
        Log::info("📧 Verification email resent to: {$user->email}");

        return back()
            ->with('resent', true)
            ->with('status', 'Link verifikasi baru sudah dikirim ke email Anda.');
    }
}
