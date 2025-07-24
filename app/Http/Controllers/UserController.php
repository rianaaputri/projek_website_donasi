<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
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

        // Trigger email verification
        event(new Registered($user));

        return redirect()->route('user.login')->with('success', 'Registrasi berhasil! Cek email kamu untuk verifikasi akun ya! 📧');
    }

    // Show Login Page
    public function showLogin()
    {
        return view('auth.user-login');
    }

    // Handle Login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Email atau password salah bro! 🤔')->withInput();
        }

        if ($user->role !== 'user') {
            return back()->with('error', 'Kamu ga punya akses user nih! 🚫');
        }

        // Check email verification
        if (!$user->hasVerifiedEmail()) {
            return back()->with('error', 'Email kamu belum diverifikasi! Cek inbox dulu ya 📮')->withInput();
        }

        session(['user_id' => $user->id]);
        return redirect()->route('user.dashboard');
    }

    // Handle Logout
    public function logout()
    {
        session()->forget('user_id');
        return redirect()->route('user.login');
    }

    // Email Verification Notice
    public function verificationNotice()
    {
        return view('auth.verify-email');
    }

    // Resend Email Verification
    public function resendVerification(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan!');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('user.login')->with('success', 'Email sudah diverifikasi! Silakan login 🎉');
        }

        $user->sendEmailVerificationNotification();
        return back()->with('success', 'Email verifikasi sudah dikirim ulang! 📧');
    }
}