<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Auth\CitizenAuthController;
use App\Http\Controllers\CitizenDashboardController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Admin\ReservationReviewController;
use App\Http\Controllers\PaymentSlipController;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Staff\RequirementVerificationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\Admin\ScheduleConflictController;

// ============================================
// AUTHENTICATION DISABLED FOR LOCAL DEVELOPMENT
// ============================================
// TODO: Re-enable these routes when deploying to production

// Route::middleware(['web'])->group(function () {
//     Route::get('/sso/login', [SsoController::class, 'login'])->name('sso.login');
// });

// // Helpful redirect for users who access the system directly
// Route::get('/login', function() {
//     return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
// })->name('login');

// ============================================
// ADMIN PORTAL ROUTES (Protected)
// ============================================

// Group all admin routes with admin authentication
// AUTHENTICATION DISABLED FOR LOCAL DEVELOPMENT - Middleware commented out
Route::prefix('admin')/* ->middleware('admin.auth') */->group(function () {
     // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/quick-stats', [AdminDashboardController::class, 'getQuickStats'])->name('admin.dashboard.quick-stats');

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
    Route::get('/api/usage-data', [AnalyticsController::class, 'getUsageData'])->name('admin.api.usage_data');
    Route::get('/reservation-status', [FacilityController::class, 'showUserBookings'])->name('reservation.status');

    // AI System Management
    Route::get('/test-ai', [FacilityController::class, 'testAISystem'])->name('admin.test.ai');



    // Announcement Management
    Route::get('/announcements', [AnnouncementController::class, 'adminIndex'])->name('admin.announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('/announcements/{id}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
    Route::put('/announcements/{id}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
    Route::post('/announcements/{id}/toggle-status', [AnnouncementController::class, 'toggleStatus'])->name('admin.announcements.toggle-status');
    Route::post('/announcements/{id}/toggle-pin', [AnnouncementController::class, 'togglePin'])->name('admin.announcements.toggle-pin');

    // Reservation Review Management
    Route::get('/reservations', [ReservationReviewController::class, 'index'])->name('admin.reservations.index');
    Route::get('/reservations/{id}', [ReservationReviewController::class, 'show'])->name('admin.reservations.show');
    Route::post('/reservations/{id}/approve', [ReservationReviewController::class, 'approve'])->name('admin.reservations.approve');
    Route::post('/reservations/{id}/reject', [ReservationReviewController::class, 'reject'])->name('admin.reservations.reject');
    Route::get('/reservations/{id}/document/{type}/download', [ReservationReviewController::class, 'downloadDocument'])->name('admin.reservations.download');
    Route::get('/reservations/{id}/document/{type}/preview', [ReservationReviewController::class, 'previewDocument'])->name('admin.reservations.preview');

    // Schedule Conflict Management
    Route::get('/schedule-conflicts', [ScheduleConflictController::class, 'index'])->name('admin.schedule.conflicts');

    // City Event Management (Mayor Authorized)
    Route::get('/city-events', [\App\Http\Controllers\Admin\CityEventController::class, 'index'])->name('admin.city-events.index');
    Route::get('/city-events/calendar', [\App\Http\Controllers\Admin\CityEventController::class, 'calendar'])->name('admin.city-events.calendar');
    Route::get('/city-events/create', [\App\Http\Controllers\Admin\CityEventController::class, 'create'])->name('admin.city-events.create');
    Route::post('/city-events', [\App\Http\Controllers\Admin\CityEventController::class, 'store'])->name('admin.city-events.store');
    Route::get('/city-events/{id}', [\App\Http\Controllers\Admin\CityEventController::class, 'show'])->name('admin.city-events.show');
    Route::get('/city-events/{id}/edit', [\App\Http\Controllers\Admin\CityEventController::class, 'edit'])->name('admin.city-events.edit');
    Route::put('/city-events/{id}', [\App\Http\Controllers\Admin\CityEventController::class, 'update'])->name('admin.city-events.update');
    Route::delete('/city-events/{id}', [\App\Http\Controllers\Admin\CityEventController::class, 'destroy'])->name('admin.city-events.destroy');

    // Payment Slip Management
    Route::get('/payment-slips', [PaymentSlipController::class, 'adminIndex'])->name('admin.payment-slips.index');
    Route::post('/payment-slips/{id}/mark-paid', [PaymentSlipController::class, 'markAsPaid'])->name('admin.payment-slips.mark-paid');
    Route::post('/payment-slips/mark-expired', [PaymentSlipController::class, 'markExpired'])->name('admin.payment-slips.mark-expired');

    // Legacy routes (kept for backward compatibility)
Route::get('/new-reservation', [FacilityController::class, 'newReservation'])->name('new-reservation');
    Route::post('/reservations', [FacilityController::class, 'storeReservation'])->name('reservations.store');
Route::get('/reservations/status', [FacilityController::class, 'reservationStatus'])->name('reservations.status');
});

// ============================================
// STAFF PORTAL ROUTES
// ============================================

// Group all staff routes (same pattern as admin - no middleware)
Route::prefix('staff')->middleware('web')->group(function () {
    // Staff Dashboard (Main Overview) - Now handles SSO authentication too
    Route::get('/dashboard', [SsoController::class, 'handleStaffDashboard'])->name('staff.dashboard');
    
    // Booking Requirement Verification
    Route::get('/verification', [RequirementVerificationController::class, 'index'])->name('staff.verification.index');
    Route::get('/verification/{booking}', [RequirementVerificationController::class, 'show'])->name('staff.verification.show');
    Route::post('/verification/{booking}/approve', [RequirementVerificationController::class, 'approve'])->name('staff.verification.approve');
    Route::post('/verification/{booking}/reject', [RequirementVerificationController::class, 'reject'])->name('staff.verification.reject');
    
    // Staff Statistics and Reports
    Route::get('/my-stats', [StaffDashboardController::class, 'myStats'])->name('staff.stats');
});

// Alternative admin access routes (redirect to protected admin routes)
Route::get('/facilities', function() {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('facility.list');
    }
    return redirect()->route('admin.dashboard');
});

// Individual facility view/edit routes
Route::get('/facilities/{id}', function($id) {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('facility.list')->with('edit_facility', $id);
    }
    return redirect()->route('admin.dashboard');
})->name('facilities.show');

// Handle PUT requests to /facilities/{id} (forward to admin controller)
Route::put('/facilities/{id}', function(Request $request, $id) {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return app(\App\Http\Controllers\FacilityController::class)->update($request, $id);
    }
    return redirect()->route('admin.dashboard');
});

Route::get('/calendar', function() {
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('calendar');
    }
    return redirect()->route('admin.dashboard');
});

// ============================================
// CITIZEN PORTAL ROUTES
// ============================================


// Logout Route (for both admin and citizen)
Route::post('/logout', [CitizenAuthController::class, 'logout'])->name('logout');

// Citizen Authentication Routes (No middleware - auth handled by SsoController)
Route::prefix('citizen')->group(function () {
    Route::get('/dashboard', [CitizenDashboardController::class, 'index'])->name('citizen.dashboard');
    Route::get('/reservations', [CitizenDashboardController::class, 'reservations'])->name('citizen.reservations');
    Route::get('/reservation-history', [CitizenDashboardController::class, 'reservationHistory'])->name('citizen.reservation.history');
    Route::get('/availability', [CitizenDashboardController::class, 'viewAvailability'])->name('citizen.availability');
    Route::get('/bulletin-board', [AnnouncementController::class, 'citizenIndex'])->name('citizen.bulletin.board');
    
    // Payment Slips
    Route::get('/payment-slips', [PaymentSlipController::class, 'citizenIndex'])->name('citizen.payment-slips.index');
    Route::get('/payment-slips/{id}', [PaymentSlipController::class, 'citizenShow'])->name('citizen.payment-slips.show');
    Route::get('/payment-slips/{id}/download', [PaymentSlipController::class, 'citizenDownloadPdf'])->name('citizen.payment-slips.download');
    Route::get('/profile', [CitizenDashboardController::class, 'profile'])->name('citizen.profile');
    Route::put('/profile', [CitizenDashboardController::class, 'updateProfile'])->name('citizen.profile.update');
    Route::post('/logout', [CitizenAuthController::class, 'logout'])->name('citizen.logout');
    
    // Two-Factor Authentication Routes (for authenticated users)
    Route::get('/security/setup-2fa', [CitizenAuthController::class, 'showTwoFactorSetup'])->name('citizen.security.setup-2fa');
    Route::post('/security/enable-2fa', [CitizenAuthController::class, 'enableTwoFactor'])->name('citizen.security.enable-2fa');
    
    // AI-Enhanced reservation store route
    Route::post('/reservations/store', [FacilityController::class, 'storeReservationWithAI'])->name('citizen.reservations.store');
    
    // API endpoint for AI recommendations
    Route::post('/api/recommendations', [FacilityController::class, 'getAIRecommendations'])->name('citizen.api.recommendations');
    
    // API endpoint for facility availability data
    Route::get('/api/facility/{facilityId}/bookings', [CitizenDashboardController::class, 'getFacilityBookings'])->name('citizen.api.facility.bookings');
    
    // Announcement attachment download
    Route::get('/announcements/{id}/download', [AnnouncementController::class, 'downloadAttachment'])->name('citizen.announcements.download');
});

// ============================================
// CITIZEN AUTHENTICATION ROUTES
// ============================================

// ============================================
// ROOT REDIRECT FOR LOCAL DEVELOPMENT
// ============================================
// Redirects to admin dashboard by default - adjust URL as needed
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
})->name('home');


