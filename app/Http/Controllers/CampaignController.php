<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    private function getEnumValues($table, $column)
    {
        $type = DB::select("SHOW COLUMNS FROM {$table} WHERE Field = ?", [$column])[0]->Type;

        preg_match('/enum\((.*)\)/', $type, $matches);

        return collect(explode(',', $matches[1]))
            ->map(fn($value) => trim($value, "'"))
            ->toArray();
    }

    public function index()
    {
        $campaigns = Campaign::all();
        return view('campaign.index', compact('campaigns'));
    }

    public function create()
    {
        $categories = $this->getEnumValues('campaigns', 'category');
        return view('campaign.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $categories = $this->getEnumValues('campaigns', 'category');

        $validated = $request->validate([
            'title'           => 'required',
            'description'     => 'required',
            'category'        => ['required', Rule::in($categories)],
            'target_amount'   => 'required|numeric',
            'image'           => 'nullable|string',
            'status'          => 'required|in:active,completed,inactive',
            'is_active'       => 'nullable|boolean',
        ]);

        Campaign::create($validated);

        return redirect()->route('campaign.index')->with('success', 'Campaign berhasil ditambah!');
    }

    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        $categories = $this->getEnumValues('campaigns', 'category');

        return view('campaign.edit', compact('campaign', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $categories = $this->getEnumValues('campaigns', 'category');

        $validated = $request->validate([
            'title'           => 'required',
            'description'     => 'required',
            'category'        => ['required', Rule::in($categories)],
            'target_amount'   => 'required|numeric',
            'image'           => 'nullable|string',
            'status'          => 'required|in:active,completed,inactive',
            'is_active'       => 'nullable|boolean',
        ]);

        $campaign = Campaign::findOrFail($id);
        $campaign->update($validated);

        return redirect()->route('campaign.index')->with('success', 'Campaign berhasil diupdate!');
    }

    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();

        return redirect()->route('campaign.index')->with('success', 'Campaign berhasil dihapus!');
    }
}