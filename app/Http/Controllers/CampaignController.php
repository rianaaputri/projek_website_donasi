<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns
     */
    public function index(Request $request)
    {
        $query = Campaign::active();
        
        // Filter by category
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        
        // Filter urgent campaigns
        if ($request->filled('urgent')) {
            $query->where('is_urgent', true);
        }
        
        // Search by title or description
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('description', 'LIKE', '%' . $request->search . '%');
            });
        }
        
        // Sort
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'urgent':
                $query->orderBy('is_urgent', 'desc')->orderBy('created_at', 'desc');
                break;
            case 'ending_soon':
                $query->orderBy('end_date', 'asc');
                break;
            case 'most_funded':
                $query->orderBy('current_amount', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $campaigns = $query->paginate(12);
        
        // Get categories for filter
        $categories = Campaign::select('category')
            ->distinct()
            ->pluck('category')
            ->toArray();
        
        return view('campaigns.index', compact('campaigns', 'categories'));
    }

    /**
     * Show campaign creation form
     */
    public function create()
    {
        return view('campaigns.create');
    }

    /**
     * Store a new campaign
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'target_amount' => 'required|numeric|min:1',
            'end_date' => 'required|date|after:today',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_urgent' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $validated['is_urgent'] = $request->has('is_urgent');

        $campaign = Campaign::create($validated);

        return redirect()
            ->route('campaign.detail', $campaign->id)
            ->with('success', 'Kampanye berhasil dibuat!');
    }

    /**
     * Display the specified campaign (alias method)
     */
    public function detail($id)
    {
        return $this->show($id);
    }

    /**
     * Display the specified campaign
     */
    public function show($id)
    {
        $campaign = Campaign::with([
            'donations' => function($query) {
                $query->successful()
                      ->latest()
                      ->take(10);
            },
            'comments' => function($query) {
                $query->approved()
                      ->latest()
                      ->take(5);
            }
        ])->findOrFail($id);

        // Increment view count (optional)
        // You can add a views column to campaigns table
        
        return view('campaigns.detail', compact('campaign'));
    }

    /**
     * Show campaign edit form
     */
    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified campaign
     */
    public function update(Request $request, $id)
    {
        $campaign = Campaign::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'target_amount' => 'required|numeric|min:1',
            'end_date' => 'required|date|after_or_equal:today',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_urgent' => 'boolean',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $validated['image'] = $request->file('image')->store('campaigns', 'public');
        }

        $validated['is_urgent'] = $request->has('is_urgent');
        $validated['is_active'] = $request->has('is_active');

        $campaign->update($validated);

        return redirect()
            ->route('campaign.detail', $campaign->id)
            ->with('success', 'Kampanye berhasil diperbarui!');
    }

    /**
     * Remove the specified campaign
     */
    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        
        // Check if campaign has donations
        if ($campaign->donations()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Kampanye tidak dapat dihapus karena sudah memiliki donasi.');
        }

        // Delete image
        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return redirect()
            ->route('campaign.index')
            ->with('success', 'Kampanye berhasil dihapus!');
    }

    /**
     * Get campaigns by category (AJAX)
     */
    public function byCategory(Request $request, $category)
    {
        $campaigns = Campaign::active()
            ->byCategory($category)
            ->latest()
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('campaigns.partials.campaign-grid', compact('campaigns'))->render(),
                'pagination' => $campaigns->links()->render()
            ]);
        }

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Search campaigns (AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $campaigns = Campaign::active()
            ->where(function($q) use ($query) {
                $q->where('title', 'LIKE', '%' . $query . '%')
                  ->orWhere('description', 'LIKE', '%' . $query . '%');
            })
            ->latest()
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('campaigns.partials.campaign-grid', compact('campaigns'))->render(),
                'pagination' => $campaigns->links()->render()
            ]);
        }

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Get campaign statistics
     */
    public function stats()
    {
        $stats = [
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::active()->count(),
            'total_raised' => Campaign::sum('current_amount'),
            'total_donors' => Campaign::sum('donors_count')
        ];

        return response()->json($stats);
    }
}