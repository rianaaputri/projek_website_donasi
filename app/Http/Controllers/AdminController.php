<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class AdminController extends Controller
{
    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        // Attempt login first (lebih aman)
        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'Login gagal. Email atau password salah.'])->withInput();
        }

        $user = Auth::user();

        // Pastikan user punya role admin
        if ($user->role !== 'admin') {
            Auth::logout();
            return back()->withErrors(['email' => 'Akses ditolak. Hanya admin yang bisa login.']);
        }

        // Pastikan email sudah diverifikasi
        if (! $user->hasVerifiedEmail()) {
            Auth::logout();
            return back()->withErrors(['email' => 'Email belum diverifikasi. Silakan cek email Anda.']);
        }

        // Redirect ke intended (jika ada) atau ke admin dashboard
        return redirect()->intended(route('admin.dashboard'));
    }

    public function showRegister()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // ubah jika perlu ends_with
            'password' => 'required|min:6|confirmed',
        ], [
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        try {
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
            ]);

            // Kirim email verifikasi (pastikan User implements MustVerifyEmail)
            event(new Registered($admin));

            return redirect()->route('/login')->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi sebelum login.');
        } catch (\Exception $e) {
            // Optional: log error with $e->getMessage()
            return back()->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'])->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('/login')->with('success', 'Berhasil logout.');
    }
}
