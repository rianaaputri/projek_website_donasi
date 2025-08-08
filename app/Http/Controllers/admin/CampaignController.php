<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CampaignController extends Controller
{
    public function index()
    {
        // Menggunakan get() bukan paginate() untuk menghindari error pagination
        $campaigns = Campaign::orderBy('created_at', 'desc')->get();
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'target_amount' => 'required|numeric|min:1',
            'end_date' => 'required|date|after:today',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|in:bencana,tempat ibadah,pendidikan,kesehatan,sosial'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($validatedData['title']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('campaign-images', $imageName, 'public');
            $validatedData['image'] = $imagePath;
        }

        // Generate slug
        $validatedData['slug'] = Str::slug($validatedData['title']);
        
        // Set default values
        $validatedData['collected_amount'] = 0;
        $validatedData['status'] = 'active';

        Campaign::create($validatedData);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign berhasil dibuat!');
    }

    public function show(Campaign $campaign)
    {
        return view('admin.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'target_amount' => 'required|numeric|min:1',
            'end_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|in:bencana,tempat ibadah,pendidikan,kesehatan,sosial',
            'status' => 'nullable|in:active,inactive,completed'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($campaign->image && Storage::disk('public')->exists($campaign->image)) {
                Storage::disk('public')->delete($campaign->image);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . Str::slug($validatedData['title']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('campaign-images', $imageName, 'public');
            $validatedData['image'] = $imagePath;
        }

        // Update slug
        $validatedData['slug'] = Str::slug($validatedData['title']);
        
        // Keep current status if not provided
        if (!isset($validatedData['status'])) {
            $validatedData['status'] = $campaign->status;
        }

        $campaign->update($validatedData);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign berhasil diupdate!');
    }

    public function destroy(Campaign $campaign)
    {
        try {
            // Delete image if exists
            if ($campaign->image && Storage::disk('public')->exists($campaign->image)) {
                Storage::disk('public')->delete($campaign->image);
            }
            
            $campaign->delete();

            return redirect()->route('admin.campaigns.index')
                ->with('success', 'Campaign berhasil dihapus!');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.campaigns.index')
                ->with('error', 'Gagal menghapus campaign. Silakan coba lagi.');
        }
    }
}