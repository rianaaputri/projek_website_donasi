<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign; 

class CampaignController extends Controller
{
    /**
     * Menampilkan daftar semua campaign donasi
     */
    public function index()
    {
        $campaigns = Campaign::latest()->get(); // Ambil semua data campaign terbaru
        return view('campaigns.index', compact('campaigns')); // Kirim ke view
    }
}
