<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister() {
        return view('auth.user-register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $admin->sendEmailVerificationNotification();

        return redirect('/admin/login')->with('success', 'Registrasi berhasil! Cek email untuk verifikasi.');
    }

    public function showLogin() {
        return view('auth.login');
    }

    public function userRegister()
    {
        // Method ini harus ada!
        return view('auth.user-register'); // atau return apa aja
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $admin = Auth::guard('admin')->user();

            if (!$admin->hasVerifiedEmail()) {
                Auth::guard('admin')->logout();
                return back()->withErrors(['email' => 'Verifikasi email dulu dong.']);
            }

            return redirect('/admin/dashboard');
        }

        return back()->withErrors(['email' => 'Login gagal! Email atau password salah.']);
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}
