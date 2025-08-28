<?php

use Illuminate\Support\Facades\Route;

// Homepage (Dashboard na agad)
Route::get('/', function () {
    return view('dashboard'); 
})->name('home');

Route::get('/facilities', function () {
    $facilities = [
        (object)[
            'name' => 'Municipal Gymnasium',
            'location' => 'Poblacion',
            'capacity' => 500,
            'status' => 'Available',
        ],
        (object)[
            'name' => 'Multi-purpose Hall',
            'location' => 'Barangay San Isidro',
            'capacity' => 200,
            'status' => 'Not Available',
        ],
        (object)[
            'name' => 'Conference Room',
            'location' => 'LGU Building',
            'capacity' => 50,
            'status' => 'Available',
        ],
    ];

    return view('FacilityList', compact('facilities'));
})->name('facility.list');