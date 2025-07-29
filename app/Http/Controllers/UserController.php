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

    // Handle Register
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

        // Buat user baru
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user', // Hanya user biasa
        ]);

        // Login user otomatis setelah registrasi
        Auth::login($user);

        // Trigger event untuk kirim email verifikasi
        event(new Registered($user));

        // Redirect ke verification notice
        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi. 📧');
    }

    // Show Login Page
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle Login - FIXED: Hanya untuk user biasa
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        // FIXED: Gunakan guard web secara explicit
        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('web')->user();

            // Cek email verification
            if (!$user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice')
                    ->with('error', 'Email kamu belum diverifikasi! Cek inbox dulu ya 📮');
            }

            // FIXED: User biasa selalu redirect ke home
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->with('error', 'Email atau password salah!')->withInput();
    }

    // Handle Logout
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Berhasil logout dari sistem');
    }
}