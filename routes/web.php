<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;

// Dashboard route
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Facility CRUD routes
Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');
Route::get('/facilities/create', [FacilityController::class, 'create'])->name('facilities.create');
Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
Route::get('/facilities/{id}', [FacilityController::class, 'show'])->name('facilities.show');
Route::get('/facilities/{id}/edit', [FacilityController::class, 'edit'])->name('facilities.edit');
Route::put('/facilities/{id}', [FacilityController::class, 'update'])->name('facilities.update');
Route::delete('/facilities/{id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
