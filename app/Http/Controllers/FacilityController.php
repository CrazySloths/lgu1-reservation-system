<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacilityController extends Controller
{
    // Show all facilities (Dashboard)
    public function index()
    {
        // Dummy data para sa front-end testing
        $facilities = collect([
            (object)[
                'id' => 1,
                'name' => 'Community Hall',
                'location' => 'Barangay 1',
                'capacity' => 100,
                'status' => 'Available'
            ],
            (object)[
                'id' => 2,
                'name' => 'Sports Complex',
                'location' => 'Barangay 2',
                'capacity' => 250,
                'status' => 'Unavailable'
            ],
            (object)[
                'id' => 3,
                'name' => 'Conference Room',
                'location' => 'City Hall',
                'capacity' => 50,
                'status' => 'Available'
            ]
        ]);

        // Return sa Blade
        return view('FacilityList', compact('facilities'));
    }
}
