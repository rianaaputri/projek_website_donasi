<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;

class CreatorDashboardController extends Controller
{
    public function index()
    {
        return view('auth.creator-dashboard');
    }
}