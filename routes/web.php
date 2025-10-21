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
use Illuminate\Support\Facades\Auth;

// ============================================
// SSO AUTHENTICATION ROUTES - TEMPORARILY DISABLED
// ============================================

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
Auth::routes();

// Group all admin routes with admin authentication
Route::prefix('admin')->middleware('admin.auth')->group(function () {
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
Route::get('/calendar/all-events', [FacilityController::class, 'getAllEvents'])->name('calendar.all-events');
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

    // Maintenance Log Management
    Route::get('/maintenance-logs', [\App\Http\Controllers\Admin\MaintenanceLogController::class, 'index'])->name('admin.maintenance-logs.index');
    Route::get('/maintenance-logs/create', [\App\Http\Controllers\Admin\MaintenanceLogController::class, 'create'])->name('admin.maintenance-logs.create');
    Route::post('/maintenance-logs', [\App\Http\Controllers\Admin\MaintenanceLogController::class, 'store'])->name('admin.maintenance-logs.store');
    Route::get('/maintenance-logs/{id}', [\App\Http\Controllers\Admin\MaintenanceLogController::class, 'show'])->name('admin.maintenance-logs.show');
    Route::get('/maintenance-logs/{id}/edit', [\App\Http\Controllers\Admin\MaintenanceLogController::class, 'edit'])->name('admin.maintenance-logs.edit');
    Route::put('/maintenance-logs/{id}', [\App\Http\Controllers\Admin\MaintenanceLogController::class, 'update'])->name('admin.maintenance-logs.update');
    Route::delete('/maintenance-logs/{id}', [\App\Http\Controllers\Admin\MaintenanceLogController::class, 'destroy'])->name('admin.maintenance-logs.destroy');
    Route::post('/maintenance-logs/{id}/update-status', [\App\Http\Controllers\Admin\MaintenanceLogController::class, 'updateStatus'])->name('admin.maintenance-logs.update-status');

    // Monthly Reports
    Route::get('/monthly-reports', [\App\Http\Controllers\Admin\MonthlyReportController::class, 'index'])->name('admin.monthly-reports.index');
    Route::get('/monthly-reports/export', [\App\Http\Controllers\Admin\MonthlyReportController::class, 'export'])->name('admin.monthly-reports.export');

    // Citizen Feedback Management
    Route::get('/feedback', [\App\Http\Controllers\Admin\CitizenFeedbackController::class, 'index'])->name('admin.feedback.index');
    Route::get('/feedback/{id}', [\App\Http\Controllers\Admin\CitizenFeedbackController::class, 'show'])->name('admin.feedback.show');
    Route::patch('/feedback/{id}/status', [\App\Http\Controllers\Admin\CitizenFeedbackController::class, 'updateStatus'])->name('admin.feedback.update-status');
    Route::post('/feedback/{id}/respond', [\App\Http\Controllers\Admin\CitizenFeedbackController::class, 'respond'])->name('admin.feedback.respond');
    Route::delete('/feedback/{id}', [\App\Http\Controllers\Admin\CitizenFeedbackController::class, 'destroy'])->name('admin.feedback.destroy');

    // City Event Management (Mayor Authorized)
    Route::get('/city-events', [\App\Http\Controllers\Admin\CityEventController::class, 'index'])->name('admin.city-events.index');
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
    
    // Staff Help & Support
    Route::get('/help-support', [\App\Http\Controllers\Staff\HelpSupportController::class, 'index'])->name('staff.help-support');
    Route::post('/help-support/submit-issue', [\App\Http\Controllers\Staff\HelpSupportController::class, 'submitIssue'])->name('staff.help-support.submit-issue');
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
//Route::post('/logout', [CitizenAuthController::class, 'logout'])->name('logout');

// Citizen Authentication Routes (No middleware - auth handled by SsoController)
Route::prefix('citizen')->middleware('auth')->group(function () {
    
    // 🚀 CRITICAL FIX: DASHBOARD ROUTE
    // The dashboard is the landing page for SSO. It must run the SSO middleware first,
    // then apply standard authentication check.
    Route::get('/dashboard', [CitizenDashboardController::class, 'index'])
        ->middleware([
            \App\Http\Middleware\SsoAuthMiddleware::class, 
            'auth:web'                                     
        ])
        ->name('citizen.dashboard');

    Route::middleware('auth:web')->group(function () {
        
        // General Citizen Dashboard Routes
        Route::get('/reservations', [CitizenDashboardController::class, 'reservations'])->name('citizen.reservations');
        Route::get('/reservation-history', [CitizenDashboardController::class, 'reservationHistory'])->name('citizen.reservation.history');
        Route::get('/availability', [CitizenDashboardController::class, 'viewAvailability'])->name('citizen.availability');
        
        // API Endpoints (Needs auth)
        Route::get('/api/facility/{facility_id}/bookings', [CitizenDashboardController::class, 'getFacilityBookings'])->name('citizen.api.facility.bookings');
        Route::get('/api/all-facility-bookings', [CitizenDashboardController::class, 'getAllFacilityBookings'])->name('citizen.api.all.facility.bookings');
        Route::post('/api/recommendations', [FacilityController::class, 'getAIRecommendations'])->name('citizen.api.recommendations');
        Route::get('/api/facility/{facilityId}/bookings', [CitizenDashboardController::class, 'getFacilityBookings'])->name('citizen.api.facility.bookings'); // Duplicate, but kept for safety
        
        // Bulletin/Announcement
        Route::get('/bulletin-board', [AnnouncementController::class, 'citizenIndex'])->name('citizen.bulletin.board');
        Route::get('/announcements/{id}/download', [AnnouncementController::class, 'downloadAttachment'])->name('citizen.announcements.download');
        
        // Payment Slips
        Route::get('/payment-slips', [PaymentSlipController::class, 'citizenIndex'])->name('citizen.payment-slips.index');
        Route::get('/payment-slips/{id}', [PaymentSlipController::class, 'citizenShow'])->name('citizen.payment-slips.show');
        Route::get('/payment-slips/{id}/download', [PaymentSlipController::class, 'citizenDownloadPdf'])->name('citizen.payment-slips.download');
        
        // Profile
        Route::get('/profile', [CitizenDashboardController::class, 'profile'])->name('citizen.profile');
        Route::put('/profile', [CitizenDashboardController::class, 'updateProfile'])->name('citizen.profile.update');
        
        // Help & FAQ
        Route::get('/help-faq', [\App\Http\Controllers\Citizen\HelpFaqController::class, 'index'])->name('citizen.help-faq');
        Route::post('/help-faq/submit', [\App\Http\Controllers\Citizen\HelpFaqController::class, 'submitQuestion'])->name('citizen.help-faq.submit');
        
        // Two-Factor Authentication Routes (for authenticated users)
        Route::get('/security/setup-2fa', [CitizenAuthController::class, 'showTwoFactorSetup'])->name('citizen.security.setup-2fa');
        Route::post('/security/enable-2fa', [CitizenAuthController::class, 'enableTwoFactor'])->name('citizen.security.enable-2fa');
        
        // AI-Enhanced reservation store route
        Route::post('/reservations/store', [FacilityController::class, 'storeReservationWithAI'])->name('citizen.reservations.store');
        
        // Booking Extension Routes
        Route::post('/bookings/{booking}/check-extension-conflict', [\App\Http\Controllers\Citizen\BookingExtensionController::class, 'checkConflict'])->name('citizen.bookings.check-extension');
        Route::post('/bookings/{booking}/extend', [\App\Http\Controllers\Citizen\BookingExtensionController::class, 'extend'])->name('citizen.bookings.extend');
    
    });
    
    // Logout does not require prior authentication check
    Route::post('/logout', [CitizenAuthController::class, 'logout'])->name('citizen.logout');
});

// ============================================
// CITIZEN AUTHENTICATION ROUTES
// ============================================

// ============================================
// HOME ROUTE
// ============================================
Route::get('/', function () {
    return redirect()->route('citizen.dashboard');
})->name('home');


