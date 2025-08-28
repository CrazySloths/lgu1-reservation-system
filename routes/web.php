<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;

// Homepage (Dashboard)
Route::get('/', function () {
    return view('dashboard');
})->name('home');

// Facility Directory and Calendar
Route::get('/facilities', [FacilityController::class, 'index'])->name('facility.list');

// Booking Approval Dashboard
Route::get('/bookings/approval', [FacilityController::class, 'approvalDashboard'])->name('bookings.approval');

// Route to handle the "Add Facility" form submission
Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');

// Route to handle the "Update Facility" form submission
Route::put('/facilities/{facility_id}', [FacilityController::class, 'update'])->name('facilities.update');

// Facility Calendar Bookings
Route::get('/calendar', [FacilityController::class, 'calendar'])->name('calendar');

// Route to fetch events for a specific facility (used by FullCalendar)
Route::get('/facilities/{facility_id}/events', [FacilityController::class, 'getEvents'])->name('facilities.events');

// Route to handle the "Book Facility" form submission
Route::post('/bookings', [FacilityController::class, 'storeBooking'])->name('bookings.store');

// Route to approve a booking
Route::post('/bookings/{id}/approve', [FacilityController::class, 'approveBooking'])->name('bookings.approve');

// Route to reject a booking
Route::post('/bookings/{id}/reject', [FacilityController::class, 'rejectBooking'])->name('bookings.reject');

// New Reservation and Reservation Status
Route::get('/new-reservation', [FacilityController::class, 'newReservation'])->name('new-reservation');
Route::get('/reservations/status', [FacilityController::class, 'reservationStatus'])->name('reservations.status');
Route::delete('/facilities/{facility_id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');