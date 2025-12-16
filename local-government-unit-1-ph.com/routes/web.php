<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Root Route - Redirect to Login
Route::get('/', function () {
    return redirect()->route('login');
});

// CSRF Token Refresh Endpoint - For preventing stale token issues
Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token()
    ]);
});

// Public Facility Routes (No authentication required)
Route::get('/facilities', [App\Http\Controllers\FacilityController::class, 'index'])->name('facilities.index');
Route::get('/facilities/{id}', [App\Http\Controllers\FacilityController::class, 'show'])->name('facilities.show');
Route::post('/facilities/{id}/check-availability', [App\Http\Controllers\FacilityController::class, 'checkAvailability'])->name('facilities.check-availability');

Route::get('/test-email', function () {
    try {
        $testOtp = '123456';
        \Mail::to('llanetacristianpastoril@gmail.com')->send(new \App\Mail\LoginOtpMail($testOtp, 'Test User'));
        return response()->json([
            'status' => 'success',
            'message' => 'Test email sent! Check your inbox (llanetacristianpastoril@gmail.com)',
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Email failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->name('test.email');

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel is working perfectly! 🎉',
        'laravel_version' => app()->version(),
        'php_version' => phpversion(),
        'environment' => config('app.env'),
        'database_auth' => config('database.connections.auth_db.database'),
        'database_facilities' => config('database.connections.facilities_db.database'),
    ]);
})->name('test');

// Authentication Routes
Route::get('/login', function () {
    // Clear any pending OTP sessions when visiting login page directly
    session()->forget(['pending_login_user_id', 'show_otp_step']);
    return view('auth.login');
})->name('login');

// Step 1: Login with Email/Password - Generate and Send OTP (AJAX)
Route::post('/login', function () {
    $email = request('email');
    $password = request('password');
    
    // Get user with roles
    $user = DB::connection('auth_db')
        ->table('users')
        ->select('users.*', 'roles.name as role_name', 'subsystem_roles.role_name as subsystem_role_name')
        ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
        ->leftJoin('subsystem_roles', 'users.subsystem_role_id', '=', 'subsystem_roles.id')
        ->where('email', $email)
        ->where('status', 'active')
        ->first();
    
    if ($user && Hash::check($password, $user->password_hash)) {
        // Check if email is verified
        if (!$user->is_email_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email before logging in.'
            ]);
        }
        
        // Generate 6-digit OTP
        $otp = random_int(100000, 999999);
        $expiresAt = now()->addMinutes(1); // 1 minute expiration
        
        // Store OTP in database
        DB::connection('auth_db')->table('user_otps')->insert([
            'user_id' => $user->id,
            'otp_code' => (string)$otp,
            'expires_at' => $expiresAt,
            'used' => 0,
            'created_at' => now(),
        ]);
        
        // Store pending login user ID in session FIRST
        session(['pending_login_user_id' => $user->id]);
        
        // Send OTP email with better error handling
        $emailSent = false;
        $message = 'OTP sent to your email. Please check your inbox.';
        
        try {
            \Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($otp, $user->full_name));
            $emailSent = true;
        } catch (\Exception $e) {
            // Log detailed error
            \Log::error('OTP email send failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage()
            ]);
            
            // Show warning to user but still allow OTP input
            $message = 'Email service is experiencing issues. Please wait 15-30 seconds and check your inbox, or contact support if OTP does not arrive.';
        }
        
        return response()->json([
            'success' => true,
            'message' => $message,
            'email_sent' => $emailSent
        ]);
    }
    
    return response()->json([
        'success' => false,
        'message' => 'Invalid email or password.'
    ]);
})->name('login.post');

// Step 2: Verify OTP and Complete Login (AJAX)
Route::post('/login/verify-otp', function () {
    $otp = request('otp');
    $userId = session('pending_login_user_id');
    
    if (!$userId) {
        return response()->json([
            'success' => false,
            'message' => 'Session expired. Please try logging in again.'
        ]);
    }
    
    // Validate OTP
    $otpRecord = DB::connection('auth_db')
        ->table('user_otps')
        ->where('user_id', $userId)
        ->where('otp_code', $otp)
        ->where('used', 0)
        ->where('expires_at', '>', now())
        ->orderBy('id', 'desc')
        ->first();
    
    if ($otpRecord) {
        // Mark OTP as used
        DB::connection('auth_db')
            ->table('user_otps')
            ->where('id', $otpRecord->id)
            ->update(['used' => 1]);
        
        // Get user data with roles
        $user = DB::connection('auth_db')
            ->table('users')
            ->select('users.*', 'roles.name as role_name', 'subsystem_roles.role_name as subsystem_role_name')
            ->leftJoin('roles', 'users.role_id', '=', 'roles.id')
            ->leftJoin('subsystem_roles', 'users.subsystem_role_id', '=', 'subsystem_roles.id')
            ->where('users.id', $userId)
            ->first();
        
        if ($user) {
            // Complete login - store user info in session
            session([
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_name' => $user->full_name,
                'user_role' => $user->role_name ?? $user->subsystem_role_name ?? 'citizen',
            ]);
            
            // Clear pending login session
            session()->forget('pending_login_user_id');
            
            // Determine redirect URL based on role
            $redirectUrl = route('citizen.dashboard'); // default
            
            if ($user->role_name === 'super admin') {
                $redirectUrl = route('superadmin.dashboard');
            } elseif ($user->subsystem_role_name === 'Admin') {
                $redirectUrl = route('admin.dashboard');
            } elseif ($user->subsystem_role_name === 'Reservations Staff') {
                $redirectUrl = route('staff.dashboard');
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'redirect' => $redirectUrl
            ]);
        }
    }
    
    return response()->json([
        'success' => false,
        'message' => 'Invalid or expired OTP. Please try again.'
    ]);
})->name('login.verify-otp');

// Resend Login OTP
Route::post('/login/resend-otp', function () {
    $userId = session('pending_login_user_id');
    
    if (!$userId) {
        return response()->json([
            'success' => false,
            'message' => 'Session expired. Please go back to login page.'
        ]);
    }
    
    // Get user info
    $user = DB::connection('auth_db')
        ->table('users')
        ->where('id', $userId)
        ->first(['full_name', 'email']);
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found. Please try logging in again.'
        ]);
    }
    
    // Generate new OTP
    $otp = random_int(100000, 999999);
    $expiresAt = now()->addMinutes(1); // 1 minute expiration
    
    // Mark all previous OTPs as used
    DB::connection('auth_db')
        ->table('user_otps')
        ->where('user_id', $userId)
        ->where('used', 0)
        ->update(['used' => 1]);
    
    // Insert new OTP
    DB::connection('auth_db')->table('user_otps')->insert([
        'user_id' => $userId,
        'otp_code' => (string)$otp,
        'expires_at' => $expiresAt,
        'used' => 0,
        'created_at' => now(),
    ]);
    
    // Send OTP email
    try {
        \Mail::to($user->email)->send(new \App\Mail\LoginOtpMail($otp, $user->full_name));
        
        return response()->json([
            'success' => true,
            'message' => 'New OTP sent to your email. Valid for 1 minute.'
        ]);
    } catch (\Exception $e) {
        // Log error
        \Log::error('Resend OTP email failed: ' . $e->getMessage(), [
            'user_id' => $userId,
            'email' => $user->email
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to send email. Please try again or contact support.'
        ]);
    }
})->name('login.resend-otp');

Route::get('/register', function () {
    // Get districts and barangays for the form
    $districts = DB::connection('auth_db')->table('districts')->orderBy('district_number')->get();
    $barangays = DB::connection('auth_db')->table('barangays')->orderBy('name')->get();
    
    // Check if user is in verification step (like original lgu1_auth)
    $step = 1;
    $verificationEmailSent = false;
    if (session()->has('pending_user_id')) {
        $step = 5;
        $verificationEmailSent = true;
    }
    
    return view('auth.register', compact('districts', 'barangays', 'step', 'verificationEmailSent'));
})->name('register');

// ==================== PHILIPPINE ADDRESS API ENDPOINTS ====================

// Get all regions
Route::get('/api/get-regions', function () {
    $regions = DB::connection('auth_db')
        ->table('regions')
        ->orderBy('name')
        ->get(['id', 'code', 'name', 'long_name']);
    
    return response()->json($regions);
});

// Get provinces by region
Route::get('/api/get-provinces/{regionId}', function ($regionId) {
    $provinces = DB::connection('auth_db')
        ->table('provinces')
        ->where('region_id', $regionId)
        ->orderBy('name')
        ->get(['id', 'code', 'name']);
    
    return response()->json($provinces);
});

// Get cities by province
Route::get('/api/get-cities/{provinceId}', function ($provinceId) {
    $cities = DB::connection('auth_db')
        ->table('cities')
        ->where('province_id', $provinceId)
        ->orderBy('name')
        ->get(['id', 'code', 'name', 'type', 'zip_code']);
    
    return response()->json($cities);
});

// Get districts by city (NEW - ALL cities have districts)
Route::get('/api/get-districts/{cityId}', function ($cityId) {
    $districts = DB::connection('auth_db')
        ->table('districts')
        ->where('city_id', $cityId)
        ->orderBy('district_number')
        ->get(['id', 'district_number', 'name', 'type']);
    
    return response()->json($districts);
});

// Get barangays by district (CORRECT FLOW: City → District → Barangay)
Route::get('/api/get-barangays-by-district/{districtId}', function ($districtId) {
    $barangays = DB::connection('auth_db')
        ->table('barangays')
        ->where('district_id', $districtId)
        ->orderBy('name')
        ->get(['id', 'name', 'alternate_name', 'zip_code']);
    
    return response()->json($barangays);
});

// ==================== REGISTRATION VALIDATION API ENDPOINTS ====================

// API route to check if email is already taken
Route::post('/api/check-email', function () {
    $email = request('email');
    
    if (!$email) {
        return response()->json(['available' => false, 'message' => 'Email is required']);
    }
    
    // Check if email is from an allowed/legitimate provider (ALLOWLIST approach)
    if (!\App\Helpers\DisposableEmailDomains::isAllowed($email)) {
        return response()->json([
            'available' => false,
            'message' => 'Only legitimate email providers are allowed (Gmail, Yahoo, Outlook, Hotmail, iCloud, ProtonMail, etc.). Temporary or disposable email addresses are not permitted.'
        ]);
    }
    
    // Check if email has suspicious patterns (catches Emailnator, etc.)
    if (\App\Helpers\DisposableEmailDomains::hasSuspiciousPattern($email)) {
        return response()->json([
            'available' => false,
            'message' => 'This email address appears to be invalid or from a temporary email service. Please use a standard personal email address.'
        ]);
    }
    
    // Check if email already exists in database
    $user = DB::connection('auth_db')
        ->table('users')
        ->where('email', $email)
        ->first();
    
    if ($user) {
        // If email exists but is NOT verified, allow re-registration
        if ($user->is_email_verified == 0) {
            return response()->json([
                'available' => true,
                'message' => 'Email is available!',
                'note' => 'Previous unverified registration will be replaced'
            ]);
        }
        
        // Email exists and IS verified - cannot register
        return response()->json([
            'available' => false,
            'message' => 'This email is already registered. Please use a different email or try logging in.'
        ]);
    }
    
    return response()->json([
        'available' => true,
        'message' => 'Email is available!'
    ]);
});

// API route to check if mobile number is already taken
Route::post('/api/check-mobile', function () {
    $mobile = request('mobile_number');
    
    if (!$mobile) {
        return response()->json(['available' => false, 'message' => 'Mobile number is required']);
    }
    
    // Validate format (09xxxxxxxxx)
    if (!preg_match('/^09\d{9}$/', $mobile)) {
        return response()->json([
            'available' => false,
            'message' => 'Invalid mobile number format. Must be 11 digits starting with 09 (e.g., 09171234567).'
        ]);
    }
    
    // Check if mobile number already exists in database
    $user = DB::connection('auth_db')
        ->table('users')
        ->where('mobile_number', $mobile)
        ->first();
    
    if ($user) {
        // If mobile exists but is NOT verified, allow re-registration
        if ($user->is_email_verified == 0) {
            return response()->json([
                'available' => true,
                'message' => 'Mobile number is available!',
                'note' => 'Previous unverified registration will be replaced'
            ]);
        }
        
        // Mobile exists and IS verified - cannot register
        return response()->json([
            'available' => false,
            'message' => 'This mobile number is already registered. Please use a different number.'
        ]);
    }
    
    return response()->json([
        'available' => true,
        'message' => 'Mobile number is available!'
    ]);
});

// AI Verification Endpoint (Azure Face API)
Route::post('/api/verify-ai', function () {
    try {
        // Validate required images
        $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), [
            'id_front' => 'required|string',  // Base64 encoded
            'id_back' => 'required|string',   // Base64 encoded
            'selfie' => 'required|string',    // Base64 encoded
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => 'Missing required images',
                'status' => 'failed'
            ], 400);
        }

        // Initialize Face Verification Service
        $faceService = new \App\Services\FaceVerificationService();

        // Perform complete verification
        $results = $faceService->completeVerification([
            'id_front' => request('id_front'),
            'id_back' => request('id_back'),
            'selfie' => request('selfie')
        ]);

        // Check for duplicate IDs in database (only for verified accounts)
        $duplicateCheck = DB::connection('auth_db')->table('users')
            ->where(function($query) use ($results) {
                $query->where('id_front_hash', $results['hashes']['id_front'])
                      ->orWhere('id_back_hash', $results['hashes']['id_back']);
            })
            ->where('is_email_verified', 1) // Only check verified accounts
            ->first();

        if ($duplicateCheck) {
            return response()->json([
                'success' => false,
                'status' => 'failed',
                'error' => 'This ID has already been registered',
                'notes' => ['This valid ID has already been used for registration. Each ID can only be used once.'],
                'face_match_score' => 0,
                'hashes' => $results['hashes']
            ]);
        }

        // Return results
        return response()->json([
            'success' => true,
            'status' => $results['status'],
            'face_match_score' => $results['face_match_score'],
            'id_authenticity_score' => $results['id_authenticity_score'],
            'liveness_score' => $results['liveness_score'],
            'overall_confidence' => $results['overall_confidence'],
            'confidence' => $results['confidence'] ?? 0,
            'notes' => $results['notes'],
            'hashes' => $results['hashes'],
            'error' => $results['error'] ?? null
        ]);

    } catch (\Exception $e) {
        \Log::error('AI Verification API error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'status' => 'manual_review',
            'error' => 'Verification service error: ' . $e->getMessage(),
            'notes' => ['System error - manual review required']
        ], 500);
    }
})->name('api.verify-ai');

Route::post('/register', function () {
    // Validate the request (matching original lgu1_auth validation)
    $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), [
        'username' => 'required|string|max:50',
        'email' => 'required|email|max:100',
        'password' => 'required|string|min:6',
        'full_name' => 'required|string|max:100',
        'birthdate' => 'required|date',
        'mobile_number' => 'required|regex:/^09\d{9}$/',
        'gender' => 'required|in:male,female',
        'civil_status' => 'required|in:single,married,divorced,widowed',
        'nationality' => 'required|string|max:50',
        // NEW Philippine Address System
        'region_id' => 'required|exists:auth_db.regions,id',
        'province_id' => 'required|exists:auth_db.provinces,id',
        'city_id' => 'required|exists:auth_db.cities,id',
        'barangay_id' => 'required|exists:auth_db.barangays,id',
        'district_id' => 'nullable|exists:auth_db.districts,id', // Optional, only for Quezon City
        'current_address' => 'required|string|max:255',
        'zip_code' => 'required|string|max:10',
        'valid_id_type' => 'required|string',
        'valid_id_front_image' => 'required|image|max:5120',
        'valid_id_back_image' => 'required|image|max:5120',
        'selfie_with_id_image' => 'required|image|max:5120',
        'registration_type' => 'required|string',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Note: Email uniqueness is checked upfront via AJAX in Step 1
    // But we still validate as a safety net on the backend
    
    // Check if email is from an allowed provider (safety net)
    if (!\App\Helpers\DisposableEmailDomains::isAllowed(request('email'))) {
        return back()->withErrors(['email' => 'Only legitimate email providers are allowed (Gmail, Yahoo, Outlook, Hotmail, iCloud, ProtonMail, etc.). Temporary or disposable email addresses are not permitted.'])->withInput();
    }
    
    // Check if email has suspicious patterns (safety net)
    if (\App\Helpers\DisposableEmailDomains::hasSuspiciousPattern(request('email'))) {
        return back()->withErrors(['email' => 'This email address appears to be invalid or from a temporary email service. Please use a standard personal email address.'])->withInput();
    }

    // Check if mobile number already exists (must be unique)
    $existingMobile = DB::connection('auth_db')
        ->table('users')
        ->where('mobile_number', request('mobile_number'))
        ->first();

    if ($existingMobile) {
        return back()->withErrors(['mobile_number' => 'This mobile number is already registered. Please use a different number.'])->withInput();
    }

    // ==================== AI VERIFICATION: DUPLICATE ID CHECK ====================
    // Get AI verification data from hidden form field
    $aiDataJson = request('ai_verification_data');
    $aiData = $aiDataJson ? json_decode($aiDataJson, true) : null;
    
    // Check for duplicate IDs using perceptual hashes
    if ($aiData && isset($aiData['idFrontHash'])) {
        $duplicateCheck = DB::connection('auth_db')
            ->table('users')
            ->where(function($query) use ($aiData) {
                $query->where('id_front_hash', $aiData['idFrontHash'])
                      ->orWhere('id_back_hash', $aiData['idBackHash'])
                      ->orWhere('selfie_hash', $aiData['selfieHash']);
            })
            ->first();

        if ($duplicateCheck) {
            return back()->withErrors([
                'valid_id_front_image' => 'This ID has already been registered. Each valid ID can only be used once. If you believe this is an error, please contact support.'
            ])->withInput();
        }
    }
    // ==================== END AI VERIFICATION CHECK ====================

    // Use database transaction to ensure data integrity
    // If anything fails, nothing gets saved to the database
    try {
        DB::connection('auth_db')->beginTransaction();
        
        // Delete any unverified accounts with same email or mobile number
        // This allows users to re-register if they didn't verify their previous attempt
        $email = request('email');
        $mobileNumber = request('mobile_number');
        
        $unverifiedUsers = DB::connection('auth_db')
            ->table('users')
            ->where(function($query) use ($email, $mobileNumber) {
                $query->where('email', $email)
                      ->orWhere('mobile_number', $mobileNumber);
            })
            ->where('is_email_verified', 0)
            ->get();
        
        // Delete unverified accounts and their associated data
        foreach ($unverifiedUsers as $unverifiedUser) {
            // Delete associated OTPs
            DB::connection('auth_db')
                ->table('user_otps')
                ->where('user_id', $unverifiedUser->id)
                ->delete();
            
            // Delete user files if they exist
            if ($unverifiedUser->valid_id_front_image && file_exists(public_path($unverifiedUser->valid_id_front_image))) {
                @unlink(public_path($unverifiedUser->valid_id_front_image));
            }
            if ($unverifiedUser->valid_id_back_image && file_exists(public_path($unverifiedUser->valid_id_back_image))) {
                @unlink(public_path($unverifiedUser->valid_id_back_image));
            }
            if ($unverifiedUser->selfie_with_id_image && file_exists(public_path($unverifiedUser->selfie_with_id_image))) {
                @unlink(public_path($unverifiedUser->selfie_with_id_image));
            }
            
            // Delete the user
            DB::connection('auth_db')
                ->table('users')
                ->where('id', $unverifiedUser->id)
                ->delete();
        }
        
        // Handle file uploads (matching original field names)
        $validIdFront = request()->file('valid_id_front_image');
        $validIdBack = request()->file('valid_id_back_image');
        $selfieWithId = request()->file('selfie_with_id_image');

        // Create unique filenames
        $timestamp = time();
        $validIdFrontPath = 'uploads/ids/' . $timestamp . '_front_' . $validIdFront->getClientOriginalName();
        $validIdBackPath = 'uploads/ids/' . $timestamp . '_back_' . $validIdBack->getClientOriginalName();
        $selfieWithIdPath = 'uploads/ids/' . $timestamp . '_selfie_' . $selfieWithId->getClientOriginalName();

        // Move files to public/uploads/ids
        $validIdFront->move(public_path('uploads/ids'), basename($validIdFrontPath));
        $validIdBack->move(public_path('uploads/ids'), basename($validIdBackPath));
        $selfieWithId->move(public_path('uploads/ids'), basename($selfieWithIdPath));

        // Hash password
        $passwordHash = Hash::make(request('password'));

        // Generate verification token and OTP
        $token = bin2hex(random_bytes(16));
        $otp = random_int(100000, 999999);
        $now = now();
        $expiresAt = now()->addMinutes(1); // 1 minute expiration

        // Insert user
        $userId = DB::connection('auth_db')->table('users')->insertGetId([
            'username' => request('username'),
            'email' => request('email'),
            'full_name' => request('full_name'),
            'password_hash' => $passwordHash,
            'birthdate' => request('birthdate'),
            'mobile_number' => request('mobile_number'),
            'gender' => request('gender'),
            'civil_status' => request('civil_status'),
            'nationality' => request('nationality'),
            // NEW Philippine Address System
            'region_id' => request('region_id'),
            'province_id' => request('province_id'),
            'city_id' => request('city_id'),
            'barangay_id' => request('barangay_id'),
            'district_id' => request('district_id'), // Optional, only for Quezon City
            'current_address' => request('current_address'),
            'zip_code' => request('zip_code'),
            'valid_id_type' => request('valid_id_type'),
            'valid_id_front_image' => $validIdFrontPath,
            'valid_id_back_image' => $validIdBackPath,
            'selfie_with_id_image' => $selfieWithIdPath,
            'id_verification_status' => 'pending',
            // AI Verification Data
            'id_front_hash' => $aiData['idFrontHash'] ?? null,
            'id_back_hash' => $aiData['idBackHash'] ?? null,
            'selfie_hash' => $aiData['selfieHash'] ?? null,
            'face_match_score' => $aiData['faceMatchScore'] ?? null,
            'id_authenticity_score' => $aiData['idAuthenticityScore'] ?? null,
            'liveness_score' => $aiData['livenessScore'] ?? null,
            'ai_verification_status' => $aiData['status'] ?? 'pending',
            'ai_verification_notes' => isset($aiData['notes']) ? json_encode($aiData['notes']) : null,
            'status' => 'inactive',
            'is_email_verified' => 0,
            'email_verification_token' => $token,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Assign role based on registration type
        $registrationType = request('registration_type');
        $registrationMapping = [
            'citizen' => ['role_id' => 2], // Global citizen role
            'applicant' => ['subsystem_id' => 8, 'role_name' => 'Applicant'], // Housing
            'utility_customer' => ['subsystem_id' => 2, 'role_name' => 'Customer'], // Utility
            'facility_user' => ['subsystem_id' => 4, 'role_name' => 'Citizen'], // Public Facilities
            'resident' => ['subsystem_id' => 5, 'role_name' => 'Resident'], // Community Infrastructure
            'road_resident' => ['subsystem_id' => 3, 'role_name' => 'Resident'], // Road and Transportation
            'land_citizen' => ['subsystem_id' => 7, 'role_name' => 'Citizen'], // Land Registration
            'property_owner' => ['subsystem_id' => 6, 'role_name' => 'Property Owner'] // Urban Planning
        ];

        if (isset($registrationMapping[$registrationType])) {
            $mapping = $registrationMapping[$registrationType];
            
            if (isset($mapping['role_id'])) {
                // Global role assignment
                DB::connection('auth_db')->table('users')->where('id', $userId)->update([
                    'role_id' => $mapping['role_id']
                ]);
            } elseif (isset($mapping['subsystem_id']) && isset($mapping['role_name'])) {
                // Subsystem role assignment
                $subsystemRole = DB::connection('auth_db')
                    ->table('subsystem_roles')
                    ->where('subsystem_id', $mapping['subsystem_id'])
                    ->where('role_name', $mapping['role_name'])
                    ->first();
                
                if ($subsystemRole) {
                    DB::connection('auth_db')->table('users')->where('id', $userId)->update([
                        'subsystem_id' => $mapping['subsystem_id'],
                        'subsystem_role_id' => $subsystemRole->id
                    ]);
                }
            }
        }

        // Store OTP
        DB::connection('auth_db')->table('user_otps')->insert([
            'user_id' => $userId,
            'otp_code' => (string)$otp,
            'expires_at' => $expiresAt,
            'used' => 0,
            'created_at' => $now,
        ]);

        // Commit the transaction - all data is now saved
        DB::connection('auth_db')->commit();

        // Store pending user info in session (only after successful DB commit)
        session([
            'pending_user_id' => $userId,
            'pending_user_email' => request('email'),
            'pending_user_name' => request('full_name'),
        ]);

        // Send OTP email (best effort - won't affect registration success)
        try {
            \Mail::to(request('email'))->send(new \App\Mail\RegistrationOtpMail($otp, request('full_name'), $token));
        } catch (\Exception $e) {
            \Log::warning('Registration OTP email send warning: ' . $e->getMessage());
        }

        // Stay on same page and show Step 5 (like original lgu1_auth)
        return redirect()->route('register')->with('success', 'Registration successful! Please check your email for the verification code.');
        
    } catch (\Exception $e) {
        // Rollback the transaction - no data will be saved
        DB::connection('auth_db')->rollBack();
        
        // Delete uploaded files if they exist
        if (isset($validIdFrontPath) && file_exists(public_path($validIdFrontPath))) {
            unlink(public_path($validIdFrontPath));
        }
        if (isset($validIdBackPath) && file_exists(public_path($validIdBackPath))) {
            unlink(public_path($validIdBackPath));
        }
        if (isset($selfieWithIdPath) && file_exists(public_path($selfieWithIdPath))) {
            unlink(public_path($selfieWithIdPath));
        }
        
        // Log the error
        \Log::error('Registration failed: ' . $e->getMessage());
        
        // Return error to user
        return back()->withErrors(['error' => 'Registration failed. Please try again. If the problem persists, contact support.'])->withInput();
    }
})->name('register.post');

// Handle OTP verification for registration (stays on register page)
Route::post('/register/verify-otp', function () {
    $otp = request('otp');
    $userId = session('pending_user_id');
    
    if (!$userId) {
        return redirect()->route('register')->withErrors(['otp' => 'Session expired. Please register again.']);
    }
    
    // Validate OTP
    $otpRecord = DB::connection('auth_db')
        ->table('user_otps')
        ->where('user_id', $userId)
        ->where('otp_code', $otp)
        ->where('used', 0)
        ->where('expires_at', '>', now())
        ->orderBy('id', 'desc')
        ->first();
    
    if ($otpRecord) {
        // Mark OTP as used
        DB::connection('auth_db')
            ->table('user_otps')
            ->where('id', $otpRecord->id)
            ->update(['used' => 1]);
        
        // Update user status - mark as email verified and active
        DB::connection('auth_db')
            ->table('users')
            ->where('id', $userId)
            ->update([
                'is_email_verified' => 1,
                'email_verified_at' => now(),
                'status' => 'active',
                'updated_at' => now()
            ]);
        
        // Mark as completed - set step to 6
        session(['registration_complete' => true]);
        
        // Stay on register page to show success (Step 6)
        return redirect()->route('register')->with('verified', true);
    }
    
    return redirect()->route('register')->withErrors(['otp' => 'Invalid or expired OTP. Please try again.']);
})->name('register.verify-otp');

// Handle resending verification email
Route::post('/register/resend-email', function (Illuminate\Http\Request $request) {
    $userId = session('pending_user_id');
    $userEmail = session('pending_user_email');
    
    if (!$userId || !$userEmail) {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Session expired. Please register again.'], 400);
        }
        return redirect()->route('register')->withErrors(['email' => 'Session expired. Please register again.']);
    }
    
    // Get user details
    $user = DB::connection('auth_db')
        ->table('users')
        ->where('id', $userId)
        ->first(['full_name', 'email_verification_token']);
    
    if ($user) {
        // Generate new OTP
        $otp = random_int(100000, 999999);
        $now = now();
        $expiresAt = now()->addMinutes(1); // 1 minute expiration
        
        // Mark previous OTPs as used
        DB::connection('auth_db')
            ->table('user_otps')
            ->where('user_id', $userId)
            ->where('used', 0)
            ->update(['used' => 1]);
        
        // Store new OTP
        DB::connection('auth_db')->table('user_otps')->insert([
            'user_id' => $userId,
            'otp_code' => (string)$otp,
            'expires_at' => $expiresAt,
            'used' => 0,
            'created_at' => $now,
        ]);
        
        // Send email
        try {
            \Mail::to($userEmail)->send(new \App\Mail\RegistrationOtpMail($otp, $user->full_name, $user->email_verification_token));
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Verification email resent successfully!'], 200);
            }
            return redirect()->route('register')->with('success', 'Verification email resent successfully!');
        } catch (\Exception $e) {
            \Log::error('Resend OTP email failed: ' . $e->getMessage());
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Email service issue. Please wait and try again.'], 500);
            }
            return redirect()->route('register')->with('warning', 'Email service issue. Please wait and try again.');
        }
    }
    
    if ($request->wantsJson() || $request->ajax()) {
        return response()->json(['message' => 'User not found.'], 404);
    }
    return redirect()->route('register')->withErrors(['email' => 'User not found.']);
})->name('register.resend-email');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function () {
    // Handle password reset steps
    
    // Step 1: Send OTP to email
    if (request()->has('send_otp')) {
        $email = request('email');
        
        if (!$email) {
            return back()->with('error', 'Please enter your email address.');
        }
        
        // Check if user exists
        $user = DB::connection('auth_db')
            ->table('users')
            ->where('email', $email)
            ->first();
        
        if (!$user) {
            return back()->with('error', 'No account found with this email address.');
        }
        
        // Check if email is verified
        if (!$user->is_email_verified) {
            return back()->with('error', 'Please verify your email address before resetting your password.');
        }
        
        // Generate OTP
        $otp = random_int(100000, 999999);
        $expiresAt = now()->addMinutes(1); // 1 minute expiration
        
        // Mark old OTPs as used
        DB::connection('auth_db')
            ->table('user_otps')
            ->where('user_id', $user->id)
            ->where('used', 0)
            ->update(['used' => 1]);
        
        // Store new OTP
        DB::connection('auth_db')->table('user_otps')->insert([
            'user_id' => $user->id,
            'otp_code' => (string)$otp,
            'expires_at' => $expiresAt,
            'used' => 0,
            'created_at' => now(),
        ]);
        
        // Send OTP email
        try {
            \Mail::to($user->email)->send(new \App\Mail\PasswordResetOtpMail($otp, $user->full_name));
            
            // Store email in session for next step
            session(['reset_email' => $email, 'reset_user_id' => $user->id, 'step' => 2]);
            
            return back()->with('success_message', 'Verification code sent to your email. Valid for 1 minute.');
        } catch (\Exception $e) {
            \Log::error('Password reset OTP email failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email. Please try again.');
        }
    }
    
    // Resend OTP (from Step 2)
    elseif (request()->has('resend_otp')) {
        $email = session('reset_email');
        $userId = session('reset_user_id');
        
        if (!$email || !$userId) {
            return back()->with('error', 'Session expired. Please start over.');
        }
        
        // Get user info
        $user = DB::connection('auth_db')
            ->table('users')
            ->where('id', $userId)
            ->first();
        
        if (!$user) {
            return back()->with('error', 'User not found.');
        }
        
        // Generate new OTP
        $otp = random_int(100000, 999999);
        $expiresAt = now()->addMinutes(1); // 1 minute expiration
        
        // Mark old OTPs as used
        DB::connection('auth_db')
            ->table('user_otps')
            ->where('user_id', $userId)
            ->where('used', 0)
            ->update(['used' => 1]);
        
        // Store new OTP
        DB::connection('auth_db')->table('user_otps')->insert([
            'user_id' => $userId,
            'otp_code' => (string)$otp,
            'expires_at' => $expiresAt,
            'used' => 0,
            'created_at' => now(),
        ]);
        
        // Send OTP email
        try {
            \Mail::to($user->email)->send(new \App\Mail\PasswordResetOtpMail($otp, $user->full_name));
            
            return back()->with('success_message', 'New verification code sent to your email. Valid for 1 minute.');
        } catch (\Exception $e) {
            \Log::error('Password reset OTP resend failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email. Please try again.');
        }
    }
    
    // Step 2: Verify OTP
    elseif (request()->has('verify_otp')) {
        $otp = request('otp');
        $userId = session('reset_user_id');
        
        if (!$userId) {
            return back()->with('error', 'Session expired. Please start over.');
        }
        
        // Validate OTP
        $otpRecord = DB::connection('auth_db')
            ->table('user_otps')
            ->where('user_id', $userId)
            ->where('otp_code', $otp)
            ->where('used', 0)
            ->where('expires_at', '>', now())
            ->orderBy('id', 'desc')
            ->first();
        
        if ($otpRecord) {
            // Mark OTP as used
            DB::connection('auth_db')
                ->table('user_otps')
                ->where('id', $otpRecord->id)
                ->update(['used' => 1]);
            
            session(['step' => 3, 'otp_verified' => true]);
            return back();
        } else {
            return back()->with('error', 'Invalid or expired verification code.');
        }
    }
    
    // Step 3: Reset Password
    elseif (request()->has('reset_password')) {
        $password = request('password');
        $confirmPassword = request('confirm_password');
        $userId = session('reset_user_id');
        $otpVerified = session('otp_verified');
        
        if (!$userId || !$otpVerified) {
            return back()->with('error', 'Session expired. Please start over.');
        }
        
        // Validate password
        if (strlen($password) < 6) {
            return back()->with('error', 'Password must be at least 6 characters.');
        }
        
        if ($password !== $confirmPassword) {
            return back()->with('error', 'Passwords do not match.');
        }
        
        // Update password
        DB::connection('auth_db')
            ->table('users')
            ->where('id', $userId)
            ->update([
                'password_hash' => Hash::make($password),
                'updated_at' => now()
            ]);
        
        // Clear session
        session()->forget(['reset_email', 'reset_user_id', 'otp_verified']);
        session(['step' => 4]);
        
        return back();
    }
    else {
        return back()->with('error', 'Invalid request.');
    }
})->name('password.update');

// Logout Route
Route::post('/logout', function () {
    session()->flush(); // Clear all session data
    return redirect()->route('login')->with('success', 'You have been logged out.');
})->name('logout');

// Clear registration session (called from Step 6 success)
Route::post('/register/clear-session', function () {
    session()->forget(['pending_user_id', 'pending_user_email', 'pending_user_name', 'registration_complete', 'verified']);
    return response()->json(['success' => true]);
});

// Protected Dashboard Routes
Route::middleware(['auth', 'role:super admin'])->group(function () {
    Route::get('/superadmin/dashboard', function () {
        return view('superadmin.dashboard');
    })->name('superadmin.dashboard');
});

Route::middleware(['auth', 'role:Admin'])->group(function () {
    // TEMPORARY: Test route to verify controller is working
    Route::get('/admin/dashboard', function() {
        $admin = Auth::user() ?? (object)[
            'id' => 1,
            'name' => 'Administrator',
            'email' => 'admin@lgu1.com',
            'role' => 'admin'
        ];
        
        return view('admin.dashboard', [
            'admin' => $admin,
            'pendingApprovalsCount' => 0,
            'pendingApprovals' => collect([]),
            'conflicts' => collect([]),
            'overduePayments' => collect([]),
            'monthlyStats' => [
                'bookings_count' => 0,
                'approved_bookings' => 0,
                'revenue' => 0,
                'pending_revenue' => 0
            ],
            'facilityStats' => collect([]),
            'upcomingReservations' => collect([]),
            'recentActivity' => collect([]),
            'todaysEventsCount' => 0
        ]);
    })->name('admin.dashboard');
    
    Route::get('/admin/dashboard/quick-stats', [App\Http\Controllers\Admin\AdminDashboardController::class, 'getQuickStats'])->name('admin.dashboard.quick-stats');
    
    // Admin Routes (placeholder - to be implemented)
    Route::get('/admin/reservations', function () {
        return 'Reservations page - Coming soon';
    })->name('admin.reservations.index');
    
    Route::get('/admin/reports', function () {
        return 'Reports page - Coming soon';
    })->name('admin.monthly-reports.index');
    
    Route::get('/admin/payment-slips', function () {
        return 'Payment slips page - Coming soon';
    })->name('admin.payment-slips.index');
    
    // Analytics & Insights (AI Feature - replaces old forecast)
    Route::get('/admin/analytics', function () {
        return view('admin.analytics');
    })->name('admin.analytics');
});

Route::middleware(['auth', 'role:Reservations Staff'])->group(function () {
    Route::get('/staff/dashboard', function () {
        return view('staff.dashboard');
    })->name('staff.dashboard');
    
    // Staff Routes (placeholder - to be implemented)
    Route::get('/staff/calendar', function () {
        return 'Calendar page - Coming soon';
    })->name('calendar');
    
    Route::get('/staff/bookings', function () {
        return 'Bookings page - Coming soon';
    })->name('bookings.approval');
    
    Route::get('/staff/verification', function () {
        return 'Verification page - Coming soon';
    })->name('staff.verification.index');
});

// Session ping endpoint - Keep session alive on user activity
Route::post('/ping-session', function () {
    if (!session()->has('user_id')) {
        return response()->json(['status' => 'expired'], 401);
    }
    
    // Update session timestamp
    session()->put('last_activity', time());
    
    return response()->json([
        'status' => 'active',
        'time' => time()
    ]);
})->name('ping-session');

// Citizen Portal Routes - Protected with session timeout
Route::middleware(['auth', 'role:citizen', \App\Http\Middleware\CheckSessionTimeout::class])->group(function () {
    // Dashboard
    Route::get('/citizen/dashboard', [\App\Http\Controllers\Citizen\DashboardController::class, 'index'])->name('citizen.dashboard');
    
    // Facilities
    Route::get('/citizen/facilities', [\App\Http\Controllers\Citizen\FacilityController::class, 'index'])->name('citizen.browse-facilities');
    Route::get('/citizen/facilities/{id}', [\App\Http\Controllers\Citizen\FacilityController::class, 'show'])->name('citizen.facility-details');
    
    // Facility Calendar
    Route::get('/citizen/calendar', [\App\Http\Controllers\Citizen\FacilityCalendarController::class, 'index'])->name('citizen.facility-calendar');
    Route::get('/citizen/calendar/bookings', [\App\Http\Controllers\Citizen\FacilityCalendarController::class, 'getBookingsForDate'])->name('citizen.facility-calendar.bookings');
    
    // Booking System
    Route::get('/citizen/booking/create/{facilityId?}', [\App\Http\Controllers\Citizen\BookingController::class, 'create'])->name('citizen.booking.create');
    Route::post('/citizen/booking/step2', [\App\Http\Controllers\Citizen\BookingController::class, 'step2'])->name('citizen.booking.step2');
    Route::post('/citizen/booking/step3', [\App\Http\Controllers\Citizen\BookingController::class, 'step3'])->name('citizen.booking.step3');
    Route::post('/citizen/booking/store', [\App\Http\Controllers\Citizen\BookingController::class, 'store'])->name('citizen.booking.store');
    Route::get('/citizen/booking/confirmation/{bookingId}', [\App\Http\Controllers\Citizen\BookingController::class, 'confirmation'])->name('citizen.booking.confirmation');
    Route::post('/citizen/booking/check-availability', [\App\Http\Controllers\Citizen\BookingController::class, 'checkAvailability'])->name('citizen.booking.check-availability');
    
    // Reservations
    Route::get('/citizen/reservations', [\App\Http\Controllers\Citizen\ReservationController::class, 'index'])->name('citizen.reservations');
    Route::get('/citizen/reservations/history', [\App\Http\Controllers\Citizen\ReservationController::class, 'history'])->name('citizen.reservation.history');
    Route::get('/citizen/reservations/{id}', [\App\Http\Controllers\Citizen\ReservationController::class, 'show'])->name('citizen.reservations.show');
    Route::post('/citizen/reservations/{id}/cancel', [\App\Http\Controllers\Citizen\ReservationController::class, 'cancel'])->name('citizen.reservations.cancel');
    Route::post('/citizen/reservations/{id}/upload', [\App\Http\Controllers\Citizen\ReservationController::class, 'uploadDocument'])->name('citizen.reservations.upload');
    
    // Payments
    Route::get('/citizen/payments', [\App\Http\Controllers\Citizen\PaymentController::class, 'index'])->name('citizen.payment-slips');
    Route::get('/citizen/payments/{id}', [\App\Http\Controllers\Citizen\PaymentController::class, 'show'])->name('citizen.payment-slips.show');
    Route::post('/citizen/payments/{id}/upload-proof', [\App\Http\Controllers\Citizen\PaymentController::class, 'uploadProof'])->name('citizen.payments.upload-proof');
    
    // Reviews & Feedback
    Route::get('/citizen/reviews/create/{bookingId}', [\App\Http\Controllers\Citizen\ReviewController::class, 'create'])->name('citizen.reviews.create');
    Route::post('/citizen/reviews', [\App\Http\Controllers\Citizen\ReviewController::class, 'store'])->name('citizen.reviews.store');
    Route::get('/citizen/reviews/{id}/edit', [\App\Http\Controllers\Citizen\ReviewController::class, 'edit'])->name('citizen.reviews.edit');
    Route::put('/citizen/reviews/{id}', [\App\Http\Controllers\Citizen\ReviewController::class, 'update'])->name('citizen.reviews.update');
    Route::delete('/citizen/reviews/{id}', [\App\Http\Controllers\Citizen\ReviewController::class, 'destroy'])->name('citizen.reviews.destroy');
    Route::get('/citizen/facilities/{facilityId}/reviews', [\App\Http\Controllers\Citizen\ReviewController::class, 'facilityReviews'])->name('citizen.facilities.reviews');
    
    // Bulletin Board
    Route::get('/citizen/bulletin', [\App\Http\Controllers\Citizen\BulletinController::class, 'index'])->name('citizen.bulletin');
    Route::get('/citizen/bulletin/{id}', [\App\Http\Controllers\Citizen\BulletinController::class, 'show'])->name('citizen.bulletin.show');
    Route::get('/citizen/bulletin/{id}/download', [\App\Http\Controllers\Citizen\BulletinController::class, 'downloadAttachment'])->name('citizen.bulletin.download');
    
    // Profile Management
    Route::get('/citizen/profile', [\App\Http\Controllers\Citizen\ProfileController::class, 'index'])->name('citizen.profile');
    Route::post('/citizen/profile/update', [\App\Http\Controllers\Citizen\ProfileController::class, 'update'])->name('citizen.profile.update');
    Route::post('/citizen/profile/password', [\App\Http\Controllers\Citizen\ProfileController::class, 'updatePassword'])->name('citizen.profile.password');
    Route::post('/citizen/profile/avatar', [\App\Http\Controllers\Citizen\ProfileController::class, 'uploadAvatar'])->name('citizen.profile.avatar');
});

// Facility Routes (shared across roles)
Route::middleware(['auth'])->group(function () {
    Route::get('/facilities', function () {
        return 'Facilities List page - Coming soon';
    })->name('facility.list');
});

// Default dashboard redirect based on session role
Route::middleware('auth')->get('/dashboard', function () {
    $role = session('user_role');
    switch ($role) {
        case 'super admin':
            return redirect()->route('superadmin.dashboard');
        case 'Admin':
            return redirect()->route('admin.dashboard');
        case 'Reservations Staff':
            return redirect()->route('staff.dashboard');
        default:
            return redirect()->route('citizen.dashboard');
    }
})->name('dashboard');
Route::middleware(['auth', 'role:super admin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');
});

// Protected Routes - Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
});

// Protected Routes - Staff
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Staff\DashboardController::class, 'index'])->name('dashboard');
});

// Default Dashboard Route (redirects based on role)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $role = session('user_role', 'citizen');
        
        // Redirect to appropriate dashboard based on role
        if (str_contains(strtolower($role), 'super admin')) {
            return redirect()->route('superadmin.dashboard');
        } elseif (str_contains(strtolower($role), 'admin')) {
            return redirect()->route('admin.dashboard');
        } elseif (str_contains(strtolower($role), 'staff')) {
            return redirect()->route('staff.dashboard');
        } else {
            return redirect()->route('citizen.dashboard');
        }
    })->name('dashboard');
});

