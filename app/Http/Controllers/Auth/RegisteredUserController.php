<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan form registrasi
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi user baru
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ✅ Buat user baru
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'user', // role default
            'is_active' => 1,      // default aktif
        ]);

        // ✅ Login otomatis supaya bisa akses halaman verifikasi
        Auth::login($user);

        // ✅ Trigger event Registered → otomatis kirim email verifikasi
        event(new Registered($user));

        // ✅ Redirect ke halaman verifikasi
        return redirect()->route('verification.notice')
            ->with('success', '✅ Registrasi berhasil! Silakan cek email Anda untuk verifikasi akun. 
                                Jika email tidak masuk, klik tombol kirim ulang.');
    }
}
