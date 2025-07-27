<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class UserController extends Controller
{
    // Show Register Page
    public function showRegister()
    {
        return view('auth.user-register');
    }

    // Handle Register - INI YANG DIPERBAIKI!
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255|min:2',
            'email'    => 'required|email|unique:users,email|regex:/^.+@gmail\.com$/i',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi ya!',
            'name.min' => 'Nama minimal 2 karakter ya!',
            'email.required' => 'Email wajib diisi ya!',
            'email.email' => 'Format email tidak valid!',
            'email.unique' => 'Email ini sudah terdaftar, coba email lain ya!',
            'email.regex' => 'Email harus menggunakan @gmail.com ya!',
            'password.required' => 'Password wajib diisi ya!',
            'password.min' => 'Password minimal 6 karakter ya!',
            'password.confirmed' => 'Konfirmasi password tidak cocok!',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Buat user baru (email_verified_at akan null otomatis)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
            // JANGAN set email_verified_at, biarkan null
        ]);

        // Login user otomatis setelah registrasi
        Auth::login($user);

        // Trigger event untuk kirim email verifikasi
        event(new Registered($user));

        // PERBAIKAN UTAMA: Redirect ke verification.notice, BUKAN ke login!
        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi. 📧');
    }

    // Show Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle Login - DIPERBAIKI!
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Gunakan Auth::attempt() Laravel standard
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Cek email verification
            if (!$user->hasVerifiedEmail()) {
                // JANGAN logout, biar bisa akses halaman verification
                return redirect()->route('verification.notice')
                    ->with('error', 'Email kamu belum diverifikasi! Cek inbox dulu ya 📮');
            }

            // Login berhasil, redirect berdasarkan role
            $request->session()->regenerate();
            
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('home'));
            }
        }

        return back()->with('error', 'Email atau password salah!')->withInput();
    }

    // Handle Logout - DIPERBAIKI!
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Berhasil logout! 👋');
    }
}