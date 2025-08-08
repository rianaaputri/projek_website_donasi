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
        $campaigns = Campaign::latest()->paginate(10);
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
            'target_amount' => 'required|numeric',
            'end_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required'
        ]);

        if ($request->file('image')) {
            $image = $request->file('image')->store('campaign-images', 'public');
            $validatedData['image'] = $image;
        }

        $validatedData['slug'] = Str::slug($validatedData['title']);
        Campaign::create($validatedData);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign created successfully');
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
            'target_amount' => 'required|numeric',
            'end_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'category' => 'required'
        ]);

        if ($request->file('image')) {
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $image = $request->file('image')->store('campaign-images', 'public');
            $validatedData['image'] = $image;
        }

        $validatedData['slug'] = Str::slug($validatedData['title']);
        $campaign->update($validatedData);

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign updated successfully');
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }
        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campaign deleted successfully');
    }
}