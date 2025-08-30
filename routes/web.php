<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Auth\CitizenAuthController;
use App\Http\Controllers\CitizenDashboardController;

// ============================================
// ADMIN PORTAL ROUTES (Protected)
// ============================================

// Group all admin routes with role-based protection
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Facility Management
    Route::get('/facilities', [FacilityController::class, 'index'])->name('facility.list');
    Route::post('/facilities', [FacilityController::class, 'store'])->name('facilities.store');
    Route::put('/facilities/{facility_id}', [FacilityController::class, 'update'])->name('facilities.update');
    Route::delete('/facilities/{facility_id}', [FacilityController::class, 'destroy'])->name('facilities.destroy');
    
    // Booking Management
    Route::get('/bookings/approval', [FacilityController::class, 'approvalDashboard'])->name('bookings.approval');
    Route::post('/bookings', [FacilityController::class, 'storeBooking'])->name('bookings.store');
    Route::post('/bookings/{id}/approve', [FacilityController::class, 'approveBooking'])->name('bookings.approve');
    Route::post('/bookings/{id}/reject', [FacilityController::class, 'rejectBooking'])->name('bookings.reject');
    
    // Calendar and Events
    Route::get('/calendar', [FacilityController::class, 'calendar'])->name('calendar');
    Route::get('/facilities/{facility_id}/events', [FacilityController::class, 'getEvents'])->name('facilities.events');
    
    // Reports and Analytics
    Route::get('/ai-forecast', [FacilityController::class, 'forecast'])->name('forecast');
    Route::get('/reservation-status', [FacilityController::class, 'showUserBookings'])->name('reservation.status');
    
    // AI System Management
    Route::get('/test-ai', [FacilityController::class, 'testAISystem'])->name('admin.test.ai');
    
    // Legacy routes (kept for backward compatibility)
    Route::get('/new-reservation', [FacilityController::class, 'newReservation'])->name('new-reservation');
    Route::post('/reservations', [FacilityController::class, 'storeReservation'])->name('reservations.store');
    Route::get('/reservations/status', [FacilityController::class, 'reservationStatus'])->name('reservations.status');
});

// Alternative admin access routes (redirect to protected admin routes)
Route::get('/facilities', function() {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('facility.list');
    }
    return redirect()->route('citizen.login');
});

Route::get('/calendar', function() {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('calendar');
    }
    return redirect()->route('citizen.login');
});

// ============================================
// CITIZEN PORTAL ROUTES
// ============================================

// Citizen Authentication Routes (Public)
Route::prefix('citizen')->group(function () {
    // Guest routes (not authenticated)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [CitizenAuthController::class, 'showLoginForm'])->name('citizen.login');
        Route::post('/login', [CitizenAuthController::class, 'login'])->name('citizen.login.submit');
        Route::get('/register', [CitizenAuthController::class, 'showRegistrationForm'])->name('citizen.register');
        Route::post('/register', [CitizenAuthController::class, 'register'])->name('citizen.register.submit');
    });

    // Authenticated citizen routes
    Route::middleware(['auth', 'role:citizen'])->group(function () {
        Route::get('/dashboard', [CitizenDashboardController::class, 'index'])->name('citizen.dashboard');
        Route::get('/reservations', [CitizenDashboardController::class, 'reservations'])->name('citizen.reservations');
        Route::get('/reservation-history', [CitizenDashboardController::class, 'reservationHistory'])->name('citizen.reservation.history');
        Route::get('/profile', [CitizenDashboardController::class, 'profile'])->name('citizen.profile');
        Route::put('/profile', [CitizenDashboardController::class, 'updateProfile'])->name('citizen.profile.update');
        Route::post('/logout', [CitizenAuthController::class, 'logout'])->name('citizen.logout');
        
        // AI-Enhanced reservation store route
        Route::post('/reservations/store', [FacilityController::class, 'storeReservationWithAI'])->name('citizen.reservations.store');
        
        // API endpoint for AI recommendations
        Route::post('/api/recommendations', [FacilityController::class, 'getAIRecommendations'])->name('citizen.api.recommendations');
    });
});

// Redirect root to appropriate portal based on authentication
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('facility.list');
        } elseif (auth()->user()->isCitizen()) {
            return redirect()->route('citizen.dashboard');
        }
    }
    return redirect()->route('citizen.login');
})->name('home');