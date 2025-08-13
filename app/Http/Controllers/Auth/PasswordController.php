<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'different:current_password',
            ],
        ]);

        $user = $request->user();

        // Cek password lama
        if (!Hash::check($validated['current_password'], $user->password)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'errors' => [
                        'current_password' => ['Password saat ini salah.']
                    ]
                ], 422);
            }

            throw ValidationException::withMessages([
                'current_password' => ['Password saat ini salah.']
            ])->errorBag('updatePassword');
        }

        // Update password
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Jika AJAX, kirim JSON
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Password berhasil diubah!'
            ], 200);
        }

        // Jika tidak, redirect seperti biasa
        return back()->with('status', 'password-updated');
    }
}