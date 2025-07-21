<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donation;

class DonationController extends Controller
{
    public function create()
    {
        return view('donation.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email',
            'nominal' => 'nullable|numeric',
            'nominal_custom' => 'nullable|numeric',
            'komentar' => 'nullable|string',
        ]);

        Donation::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'nominal' => $request->nominal,
            'nominal_custom' => $request->nominal_custom,
            'komentar' => $request->komentar,
        ]);

        return redirect()->route('donasi.create')->with('success', 'Donasi berhasil disimpan!');
    }
}
