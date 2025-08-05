<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Pastikan Panggilan middleware() Ada di SINI, di DALAM konstruktor
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('adminLogout');
    }
    protected function authenticated(Request $request, $user)
{
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard'); 
    }

    return redirect()->route('home'); 
}


public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }

    return back()->withErrors([
        'email' => 'Email atau password salah.',
    ]);
}




    // --- User Login ---
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    // --- Admin Login ---
    public function showAdminLoginForm()
    {
        return view('admin.auth.login'); // Pastikan Anda memiliki view ini
    }

    public function adminLogin(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // Coba login sebagai admin
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'admin'], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->clearLoginAttempts($request);

            if (!Auth::guard('admin')->user()->hasVerifiedEmail()) {
                return redirect()->route('admin.verification.notice');
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function adminLogout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout dari admin panel.');
    }

    // Helper method needed for AuthenticatesUsers trait, Laravel UI often adds this
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    // Helper method needed for AuthenticatesUsers trait
    public function username()
    {
        return 'email'; // Or 'username' if you use username for login
    }
}