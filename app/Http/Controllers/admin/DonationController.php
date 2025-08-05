<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Donation::with(['campaign', 'user'])->latest()->paginate(15);
        return view('admin.donations.index', compact('donations'));
    }

    public function show(Donation $donation)
    {
        $donation->load(['campaign', 'user']);
        return view('admin.donations.show', compact('donation'));
    }

    public function updateStatus(Request $request, Donation $donation)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,cancelled',
        ]);

        $donation->update([
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->back()->with('success', 'Status donasi berhasil diperbarui.');
    }
}
