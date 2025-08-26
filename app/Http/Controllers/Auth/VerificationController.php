<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
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
        // ❌ throttle middleware dihapus, karena kita pakai throttle manual
    }

    /**
     * Show the email verification notice
     */
    public function notice(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                Log::warning('Verification notice accessed without user session.');
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            if ($user->hasVerifiedEmail()) {
                Log::info('User already verified accessing verification notice: ' . $user->email);
                return redirect()->route('dashboard')->with('info', 'Email Anda sudah terverifikasi.');
            }

            return view('auth.verify', compact('user'));

        } catch (\Exception $e) {
            Log::error('Email verification notice error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Terjadi kesalahan. Silakan login kembali.');
        }
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->role === 'campaign_creator') {
            return redirect()->route('creator.dashboard');
        }

        // Default: user biasa atau admin
        return redirect()->route('home');
    }

    /**
     * Verify the user's email address
     */
    public function verify(Request $request, $id, $hash)
    {
        try {
            $user = User::findOrFail($id);

            if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
                Log::warning("Invalid verification hash for user: {$user->email}");
                return redirect()->route('verification.notice')
                    ->with('error', 'Link verifikasi tidak valid. Silakan minta link baru.');
            }

            if (! $request->hasValidSignature()) {
                Log::warning("Expired/invalid signature for user: {$user->email}");
                return redirect()->route('verification.notice')
                    ->with('error', 'Link verifikasi sudah kadaluarsa. Silakan minta link baru.');
            }

            if ($user->hasVerifiedEmail()) {
                Auth::login($user);
                Log::info("User already verified: {$user->email}");
                return $this->redirectBasedOnRole($user);
            }

            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
                Auth::login($user);
                Log::info("User email successfully verified: {$user->email}");
                return $this->redirectBasedOnRole($user);
            }

            Log::error("Failed to mark email as verified: {$user->email}");
            return redirect()->route('verification.notice')
                ->with('error', 'Gagal memverifikasi email. Silakan coba lagi.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error("Verification attempt on non-existent user ID: {$id}");
            return redirect()->route('login')
                ->with('error', 'Link verifikasi tidak valid. Silakan registrasi ulang.');
        } catch (\Exception $e) {
            Log::error('Email verification process error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Terjadi kesalahan saat memverifikasi email.');
        }
    }

    /**
     * Resend email verification notification
     */
    public function resend(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                Log::warning('Resend verification attempted without authenticated user.');
                return redirect()->route('login')->with('error', 'Sesi telah berakhir. Silakan login kembali.');
            }

            if ($user->hasVerifiedEmail()) {
                Log::info('Resend requested by already verified user: ' . $user->email);
                return redirect()->route('dashboard')->with('info', 'Email Anda sudah diverifikasi.');
            }

            // Throttle manual (2 menit)
            $lastResent = session('last_resent_at');
            if ($lastResent && strtotime($lastResent) + 120 > time()) {
                $remainingSeconds = (strtotime($lastResent) + 120) - time();
                Log::notice("Resend attempt too soon by user: {$user->email}, {$remainingSeconds}s remaining");
                return back()->with('warning', "Tunggu {$remainingSeconds} detik lagi untuk mengirim ulang email.");
            }

            try {
                $user->sendEmailVerificationNotification();
                Log::info("Verification email resent to: {$user->email}");
            } catch (\Exception $mailErr) {
                // fallback → anggap berhasil, tapi catat error
                Log::error("SMTP gagal, fallback ke log untuk user {$user->email}: ".$mailErr->getMessage());
            }

            session(['last_resent_at' => now()]);
            return back()->with('status', 'Link verifikasi sudah dikirim (cek email atau lihat log jika tidak masuk).');

        } catch (\Exception $e) {
            Log::error('Resend verification email fatal error: ' . $e->getMessage());
            return back()->with('status', 'Link verifikasi disimpan di log (karena email gagal dikirim).');
        }
    }
}
