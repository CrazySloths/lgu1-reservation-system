<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Auth\CitizenAuthController;
use App\Http\Controllers\CitizenDashboardController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Admin\ReservationReviewController;
use App\Http\Controllers\PaymentSlipController;

// ============================================
// ADMIN PORTAL ROUTES (Protected)
// ============================================

// Group all admin routes with LGU session timeout and role-based protection  
Route::middleware(['auth', 'lgu.session.timeout', 'role:admin'])->prefix('admin')->group(function () {
    // Dashboard (Main Overview)
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/quick-stats', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'getQuickStats'])->name('admin.dashboard.quick-stats');
    
    // Facility Management
Route::get('/facilities', [FacilityController::class, 'index'])->name('facility.list');
    Route::get('/facilities/{id}', function($id) {
        return redirect()->route('facility.list')->with('edit_facility', $id);
    })->name('facilities.show');
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
    
    // Payment Slip Management
    Route::get('/payment-slips', [PaymentSlipController::class, 'adminIndex'])->name('admin.payment-slips.index');
    Route::post('/payment-slips/{id}/mark-paid', [PaymentSlipController::class, 'markAsPaid'])->name('admin.payment-slips.mark-paid');
    Route::post('/payment-slips/mark-expired', [PaymentSlipController::class, 'markExpired'])->name('admin.payment-slips.mark-expired');

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
// STAFF PORTAL ROUTES (Protected)
// ============================================

// Group all staff routes with LGU session timeout and role-based protection
Route::middleware(['auth', 'lgu.session.timeout', 'role:staff'])->prefix('staff')->group(function () {
    // Staff Dashboard (Main Overview)
    Route::get('/dashboard', [\App\Http\Controllers\Staff\StaffDashboardController::class, 'index'])->name('staff.dashboard');
    
    // Booking Requirement Verification
    Route::get('/verification', [\App\Http\Controllers\Staff\RequirementVerificationController::class, 'index'])->name('staff.verification.index');
    Route::get('/verification/{booking}', [\App\Http\Controllers\Staff\RequirementVerificationController::class, 'show'])->name('staff.verification.show');
    Route::post('/verification/{booking}/approve', [\App\Http\Controllers\Staff\RequirementVerificationController::class, 'approve'])->name('staff.verification.approve');
    Route::post('/verification/{booking}/reject', [\App\Http\Controllers\Staff\RequirementVerificationController::class, 'reject'])->name('staff.verification.reject');
    
    // Staff Statistics and Reports
    Route::get('/my-stats', [\App\Http\Controllers\Staff\StaffDashboardController::class, 'myStats'])->name('staff.stats');
});

// ============================================
// CITIZEN PORTAL ROUTES
// ============================================

// LGU Authentication Routes (Public)
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [\App\Http\Controllers\Auth\LGUAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::get('/auth', [\App\Http\Controllers\Auth\LGUAuthController::class, 'handleTokenLogin'])->name('admin.lgu.auth');
    });
    
    // LGU Logout (for authenticated users)
    Route::post('/logout', [\App\Http\Controllers\Auth\LGUAuthController::class, 'logout'])->name('admin.logout');
});

// Logout Route (for both admin and citizen)
Route::post('/logout', [CitizenAuthController::class, 'logout'])->name('logout');

// Citizen Authentication Routes (Public)
Route::prefix('citizen')->group(function () {
    // Guest routes (not authenticated)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [CitizenAuthController::class, 'showLoginForm'])->name('citizen.login');
        Route::post('/login', [CitizenAuthController::class, 'login'])->name('citizen.login.submit');
        Route::get('/register', [CitizenAuthController::class, 'showRegistrationForm'])->name('citizen.register');
        Route::post('/register', [CitizenAuthController::class, 'register'])->name('citizen.register.submit');
        
        // Authentication Security Routes (during registration)
        Route::get('/verify', [CitizenAuthController::class, 'showVerificationForm'])->name('citizen.auth.verify');
        Route::get('/verify-email', [CitizenAuthController::class, 'verifyEmail'])->name('citizen.auth.verify-email');
        Route::post('/verify-phone', [CitizenAuthController::class, 'verifyPhone'])->name('citizen.auth.verify-phone');
        Route::post('/resend-email-verification', [CitizenAuthController::class, 'resendEmailVerification'])->name('citizen.auth.resend-email');
        Route::post('/resend-sms-verification', [CitizenAuthController::class, 'resendSmsVerification'])->name('citizen.auth.resend-sms');
    });

    // Authenticated citizen routes
    Route::middleware(['auth', 'role:citizen'])->group(function () {
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
});

// Generic login route (for Laravel's default auth redirects)
Route::get('/login', function () {
    // If someone is trying to access admin routes, redirect to admin login
    // Otherwise, redirect to citizen login
    $intendedUrl = session()->get('url.intended', '');
    
    if (str_contains($intendedUrl, '/admin')) {
        return redirect()->route('admin.login');
    }
    
    return redirect()->route('citizen.login');
})->name('login');

// Redirect root to appropriate portal based on authentication
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->isStaff()) {
            return redirect()->route('staff.dashboard');
        } elseif (auth()->user()->isCitizen()) {
            return redirect()->route('citizen.dashboard');
        }
    }
    return redirect()->route('citizen.login');
})->name('home');