<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
{
    /**
     * Tampilkan halaman registrasi user.
     */
    public function showRegister()
    {
        return view('auth.user-register');
    }

    /**
     * Proses registrasi user baru.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|min:2|max:255',
            'email'    => [
                'required', 'email', 'unique:users,email',
                'regex:/^.+@gmail\.com$/i'
            ],
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required'     => 'Nama wajib diisi ya!',
            'name.min'          => 'Nama minimal 2 karakter ya!',
            'email.required'    => 'Email wajib diisi ya!',
            'email.email'       => 'Format email tidak valid!',
            'email.unique'      => 'Email ini sudah terdaftar, coba email lain ya!',
            'email.regex'       => 'Email harus menggunakan @gmail.com ya!',
            'password.required' => 'Password wajib diisi ya!',
            'password.min'      => 'Password minimal 6 karakter ya!',
            'password.confirmed'=> 'Konfirmasi password tidak cocok!',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        event(new Registered($user)); // Kirim email verifikasi
        Auth::login($user); // Auto-login setelah registrasi

        return redirect()->route('verification.notice')
            ->with('success', 'Registrasi berhasil! Silakan cek email untuk verifikasi 📧');
    }

    /**
     * Tampilkan halaman login.
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login user.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('web')->user();

            if (!$user->hasVerifiedEmail()) {
                // Perbaikan: Ganti pesan dan pastikan logout tidak terjadi jika tujuannya redirect ke notice
                return redirect()->route('verification.notice')
                    ->with('warning', 'Silakan verifikasi email dulu ya!');
            }

            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->with('error', 'Email atau password salah!')->withInput();
    }

    /**
     * Proses logout user.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Berhasil logout dari sistem');
    }
}