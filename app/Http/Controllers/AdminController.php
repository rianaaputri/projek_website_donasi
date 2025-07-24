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
        
        // Tambahkan kondisi untuk hanya admin yang bisa login
        $user = User::where('email', $request->email)
                   ->where('role', 'admin')
                   ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar sebagai admin.'])->withInput();
        }

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Cek apakah user adalah admin
            if ($user->role !== 'admin') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akses ditolak. Hanya admin yang bisa login.']);
            }

            // Cek verifikasi email
            if (!$user->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Email belum diverifikasi. Silakan cek email Anda.']);
            }
            
            return redirect()->intended('/admin/dashboard');
        }

        return back()->withErrors(['email' => 'Login gagal. Email atau password salah.'])->withInput();
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
            'email' => 'required|email|unique:users,email|ends_with:@gmail.com',
            'password' => 'required|min:6|confirmed',
        ], [
            'email.ends_with' => 'Email harus menggunakan domain @gmail.com',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 6 karakter.'
        ]);

        try {
            // Buat user admin baru
            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin', // Set role sebagai admin
            ]);

            // Trigger event untuk mengirim email verifikasi
            event(new Registered($admin));

            // Redirect ke halaman login dengan pesan sukses
            return redirect('/admin/login')->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk verifikasi sebelum login.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.'])->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin/login')->with('success', 'Berhasil logout.');
    }
}