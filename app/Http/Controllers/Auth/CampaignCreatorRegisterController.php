<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class CampaignCreatorRegisterController extends Controller
{
    /**
     * Tampilkan form registrasi campaign creator
     */
    public function showRegistrationForm()
    {
        return view('auth.campaign_creator_register');
    }

    /**
     * Proses registrasi campaign creator
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'ends_with:@gmail.com',
                'unique:users'
            ],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'email.ends_with' => 'Harap gunakan email Gmail (berakhiran @gmail.com).',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // ✅ Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'campaign_creator',
        ]);

        // ✅ Login user
        Auth::login($user);

        // ✅ Trigger verifikasi
        event(new Registered($user));

        // ✅ Redirect ke halaman verifikasi
        return redirect()->route('verification.notice');
    }
}