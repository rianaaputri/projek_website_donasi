<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // Show Admin Register Page
    public function showRegister()
    {
        return view('auth.admin-register'); // Ganti ke admin-register
    }

    // Handle Admin Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.unique' => 'Email sudah terdaftar!',
            'password.required' => 'Password wajib diisi!',
            'password.min' => 'Password minimal 6 karakter!',
            'password.confirmed' => 'Konfirmasi password tidak cocok!',
        ]);

        // Buat admin baru
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Login admin otomatis
        Auth::guard('admin')->login($admin);

        // Trigger event untuk email verification
        event(new Registered($admin));

        // Redirect ke verification notice
        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi admin berhasil! Silakan verifikasi email Anda.');
    }

    // Show Admin Login Page
    public function showLogin()
    {
        return view('auth.admin-login'); // Ganti ke admin-login
    }

    // Handle Admin Login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            $admin = Auth::guard('admin')->user();

            // Cek email verification
            if (!$admin->hasVerifiedEmail()) {
                // JANGAN logout, redirect ke verification
                return redirect()->route('verification.notice')
                    ->with('error', 'Verifikasi email dulu dong! 📧');
            }

            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Login gagal! Email atau password salah.'
        ])->withInput();
    }

    // Handle Admin Logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')->with('success', 'Berhasil logout!');
    }
}