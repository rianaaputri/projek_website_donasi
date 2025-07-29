<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Get the post-login redirect path.
     * Override the default redirectPath to handle 'redirect_to' parameter
     * and Laravel's intended URL.
     *
     * @return string
     */
    protected function redirectPath()
    {
        // Prioritaskan URL yang dimaksudkan yang disimpan oleh middleware 'auth'
        // Ini akan digunakan ketika pengguna mencoba mengakses rute yang dilindungi auth dan dialihkan ke login.
        if (session()->has('url.intended')) {
            return session()->get('url.intended');
        }

        // Kemudian, periksa apakah ada parameter 'redirect_to' di request (dari Blade)
        if (request()->has('redirect_to')) {
            return request()->get('redirect_to');
        }

        // Jika tidak ada keduanya, gunakan default Laravel (biasanya /home)
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }
}
