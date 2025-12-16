<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the Admin dashboard.
     */
    public function index()
    {
        return view('admin.dashboard', [
            'title' => 'Admin Dashboard',
            'user' => auth()->user(),
        ]);
    }
}

