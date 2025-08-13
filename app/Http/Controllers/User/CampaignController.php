<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function create()
    {
        return view('user.campaigns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:health,education,disaster,social,environment',
            'target_amount' => 'required|integer|min:1000',
            'end_date' => 'required|date|after:today',
            'description' => 'required|string|min:30|max:5000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['title', 'category', 'target_amount', 'end_date', 'description']);
        $data['user_id'] = Auth::id();
        $data['collected_amount'] = 0;
        $data['status'] = 'pending'; // Menunggu verifikasi admin
        $data['is_active'] = false;

        // Upload gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('campaigns', 'public');
        }

        Campaign::create($data);

        return redirect()->route('home')->with('success', 'Campaign berhasil dibuat dan menunggu verifikasi admin.');
    }
}