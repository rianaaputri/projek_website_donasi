<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard'; // Akan ditimpa oleh logic di routes/web.php

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Show the email verification notice for regular users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // Tambahkan logika jika ingin menampilkan pesan error jika email_verified_at tidak terisi
        if ($request->user()->hasVerifiedEmail()) {
            return redirect($this->redirectPath());
        }

        // Contoh: jika ada session error khusus, bisa diteruskan ke view
        return view('auth.verify-email'); // View untuk user biasa
    }

    /**
     * Show the email verification notice for admin users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showAdmin(Request $request)
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->hasVerifiedEmail()) {
            return redirect(route('admin.dashboard'));
        }
        return view('auth.admin-verify-email'); // View khusus untuk admin
    }
}