<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthSecurityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class CitizenAuthController extends Controller
{
    protected $authSecurityService;

    public function __construct(AuthSecurityService $authSecurityService)
    {
        $this->authSecurityService = $authSecurityService;
    }

    /**
     * Show citizen registration form
     */
    public function showRegistrationForm()
    {
        return view('citizen.auth.register');
    }

    /**
     * Handle citizen registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'name' => 'nullable|string|max:500', // Auto-generated from name components
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|regex:/^09[0-9]{9}$/',
            'region' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'barangay' => 'required|string|max:100',
            'street_address' => 'required|string|max:255',
            'address' => 'required|string|max:500', // This will be auto-generated from the components
            'date_of_birth' => 'required|date|before:today',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Store registration data in session temporarily (NOT in database yet)
        $registrationData = [
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'citizen',
            'phone_number' => $request->phone_number,
            'region' => $request->region,
            'city' => $request->city,
            'barangay' => $request->barangay,
            'street_address' => $request->street_address,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            // Security verification flags - all start as false
            'is_verified' => false,
            'verified_at' => null,
            'email_verified' => false,
            'phone_verified' => false,
            'two_factor_enabled' => false,
        ];

        // Generate email verification token
        $emailVerificationToken = bin2hex(random_bytes(32));
        $registrationData['email_verification_token'] = $emailVerificationToken;
        $registrationData['email_verification_sent_at'] = now();

        // Store registration data in session with expiration (30 minutes)
        $sessionKey = 'pending_registration_' . bin2hex(random_bytes(16));
        Session::put($sessionKey, $registrationData);
        Session::put($sessionKey . '_expires_at', now()->addMinutes(30));
        Session::put('current_registration_key', $sessionKey);
        Session::put('verification_step', 'email_pending');

        // Send email verification using session data
        $emailSent = $this->authSecurityService->sendEmailVerificationForRegistration(
            $registrationData['email'],
            $registrationData['first_name'] . ' ' . $registrationData['last_name'],
            $emailVerificationToken
        );

        if (!$emailSent) {
            return back()->withErrors([
                'verification' => 'Failed to send email verification. Please try again.'
            ])->withInput();
        }

        return redirect()->route('citizen.auth.verify')
            ->with('success', 'Registration data received! Please check your email to verify your account. Your data will be saved after both email and SMS verification are completed.');
    }

    /**
     * Show login form (admin or citizen context)
     */
    public function showLoginForm(Request $request)
    {
        // Check if this is admin login based on route or URL
        $isAdminLogin = str_contains($request->url(), '/admin') || $request->route()->getName() === 'admin.login';
        
        if ($isAdminLogin) {
            return view('citizen.auth.login', ['isAdminLogin' => true]);
        }
        
        return view('citizen.auth.login', ['isAdminLogin' => false]);
    }

    /**
     * Handle authentication for both citizen and admin
     */
    public function login(Request $request)
    {
        // Accept either a real email or a username-style identifier in the 'email' field.
        // If a non-email username is provided, normalize it to the local placeholder domain
        // used by our seeder (for example: Admin-Facilities123 -> admin-facilities123@sso.local).
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        $loginInput = $data['email'];
        if (!filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            // normalize username to placeholder email used in DB seeder
            $loginEmail = strtolower($loginInput) . '@sso.local';
        } else {
            $loginEmail = $loginInput;
        }

        // Attempt authentication with normalized email and provided password
        if (Auth::attempt(['email' => $loginEmail, 'password' => $data['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Check current route to determine where to redirect
            $currentRoute = $request->route()->getName();
            
            if ($currentRoute === 'admin.login.submit' || str_contains($request->url(), '/admin')) {
                // Admin login attempt
                if ($user->isAdmin()) {
                    return redirect()->intended('/admin/facilities');
                } else {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Access denied. Admin credentials required for administrative access.',
                    ]);
                }
            } else {
                // Citizen login attempt
                if ($user->isCitizen()) {
                    return redirect()->intended('citizen/dashboard');
                } elseif ($user->isAdmin()) {
                    // Admin trying to access citizen portal - redirect to admin
                    return redirect()->route('admin.login')->with('info', 'Redirected to admin portal.');
                }
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle logout for both admin and citizen users
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Redirect to the central LGU1 login page after logout.
        return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
    }

    // ========================================
    // VERIFICATION METHODS
    // ========================================

    /**
     * Show verification form for registration
     */
    public function showVerificationForm()
    {
        $sessionKey = Session::get('current_registration_key');
        if (!$sessionKey) {
            return redirect()->route('citizen.register')
                ->withErrors(['verification' => 'Registration session expired. Please register again.']);
        }

        // Get registration data from session
        $registrationData = Session::get($sessionKey);
        $expiresAt = Session::get($sessionKey . '_expires_at');

        if (!$registrationData || !$expiresAt || now()->gt($expiresAt)) {
            Session::forget([$sessionKey, $sessionKey . '_expires_at', 'current_registration_key']);
            return redirect()->route('citizen.register')
                ->withErrors(['verification' => 'Registration session expired. Please register again.']);
        }

        // Create a user-like object for the view (but don't save to database)
        $userData = (object) [
            'first_name' => $registrationData['first_name'],
            'last_name' => $registrationData['last_name'],
            'email' => $registrationData['email'],
            'phone_number' => $registrationData['phone_number'],
            'email_verified' => $registrationData['email_verified'] ?? false,
            'phone_verified' => $registrationData['phone_verified'] ?? false,
            'phone_verification_code' => $registrationData['phone_verification_code'] ?? null,
            'phone_verification_sent_at' => isset($registrationData['phone_verification_sent_at']) 
                ? \Carbon\Carbon::parse($registrationData['phone_verification_sent_at']) 
                : null,
        ];

        return view('citizen.auth.verify', ['user' => $userData]);
    }

    /**
     * Handle email verification for registration
     */
    public function verifyEmail(Request $request)
    {
        $token = $request->get('token');
        $sessionKey = Session::get('current_registration_key');

        if (!$sessionKey || !$token) {
            return redirect()->route('citizen.register')
                ->withErrors(['verification' => 'Invalid verification link.']);
        }

        // Get registration data from session
        $registrationData = Session::get($sessionKey);
        $expiresAt = Session::get($sessionKey . '_expires_at');

        if (!$registrationData || !$expiresAt || now()->gt($expiresAt)) {
            Session::forget([$sessionKey, $sessionKey . '_expires_at', 'current_registration_key']);
            return redirect()->route('citizen.register')
                ->withErrors(['verification' => 'Registration session expired. Please register again.']);
        }

        // Verify the token matches what was sent in email
        if (!hash_equals($registrationData['email_verification_token'], $token)) {
            return redirect()->route('citizen.auth.verify')
                ->withErrors(['email_verification' => 'Invalid verification token.']);
        }

        // Mark email as verified in session data
        $registrationData['email_verified'] = true;
        $registrationData['email_verification_token'] = null; // Clear token after use
        Session::put($sessionKey, $registrationData);

        // Update verification step
        Session::put('verification_step', 'sms_pending');

        // Generate SMS code and store in session
        $smsCode = $this->authSecurityService->generateSmsCode();
        $registrationData['phone_verification_code'] = $smsCode;
        $registrationData['phone_verification_sent_at'] = now();
        Session::put($sessionKey, $registrationData);

        // Send SMS verification using registration data
        $smsSent = $this->authSecurityService->sendSmsVerificationForRegistration(
            $registrationData['phone_number'],
            $registrationData['first_name'] . ' ' . $registrationData['last_name'],
            $smsCode
        );

        if ($smsSent) {
            return redirect()->route('citizen.auth.verify')
                ->with('success', 'Email verified successfully! We\'ve sent a verification code to your phone number.');
        }

        return redirect()->route('citizen.auth.verify')
            ->with('success', 'Email verified successfully!')
            ->withErrors(['sms' => 'Failed to send SMS verification. Please use the "Resend SMS" button.']);
    }

    /**
     * Handle phone verification for registration
     */
    public function verifyPhone(Request $request)
    {
        $request->validate([
            'phone_code' => 'required|string|size:6'
        ]);

        $sessionKey = Session::get('current_registration_key');
        if (!$sessionKey) {
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired. Please register again.',
                'redirect' => route('citizen.register')
            ]);
        }

        // Get registration data from session
        $registrationData = Session::get($sessionKey);
        $expiresAt = Session::get($sessionKey . '_expires_at');

        if (!$registrationData || !$expiresAt || now()->gt($expiresAt)) {
            Session::forget([$sessionKey, $sessionKey . '_expires_at', 'current_registration_key']);
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired. Please register again.',
                'redirect' => route('citizen.register')
            ]);
        }

        // Check if email was verified first
        if (!$registrationData['email_verified']) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email first.'
            ]);
        }

        // Verify SMS code
        $storedCode = $registrationData['phone_verification_code'] ?? null;
        $sentAt = $registrationData['phone_verification_sent_at'] ?? null;
        
        if (!$storedCode || !$sentAt) {
            return response()->json([
                'success' => false,
                'message' => 'No SMS code found. Please resend the SMS code.'
            ]);
        }

        // Check if code has expired (10 minutes)
        if (now()->diffInMinutes($sentAt) > 10) {
            return response()->json([
                'success' => false,
                'message' => 'SMS verification code has expired. Please resend the code.'
            ]);
        }

        // Verify the code
        if (!hash_equals($storedCode, $request->phone_code)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code. Please try again.'
            ]);
        }

        try {
            // SMS verified successfully! Now create the user account
            $user = User::create([
                'first_name' => $registrationData['first_name'],
                'middle_name' => $registrationData['middle_name'],
                'last_name' => $registrationData['last_name'],
                'name' => $registrationData['name'],
                'email' => $registrationData['email'],
                'password' => $registrationData['password'], // Already hashed
                'role' => $registrationData['role'],
                'phone_number' => $registrationData['phone_number'],
                'region' => $registrationData['region'],
                'city' => $registrationData['city'],
                'barangay' => $registrationData['barangay'],
                'street_address' => $registrationData['street_address'],
                'address' => $registrationData['address'],
                'date_of_birth' => $registrationData['date_of_birth'],
                // Mark as verified since both email and SMS have been confirmed
                'is_verified' => true,
                'verified_at' => now(),
                'email_verified' => true,
                'phone_verified' => true,
                'two_factor_enabled' => false,
            ]);

            // Clean up session data
            Session::forget([
                $sessionKey, 
                $sessionKey . '_expires_at', 
                'current_registration_key',
                'verification_step'
            ]);

            // Log the user in automatically
            Auth::login($user);

            Log::info('User registration completed successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone_number
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Registration completed successfully! Welcome to LGU1 Portal.',
                'redirect' => route('citizen.dashboard')
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to create user account after verification', [
                'email' => $registrationData['email'],
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete registration. Please try again or contact support.'
            ]);
        }
    }

    /**
     * Resend email verification for registration
     */
    public function resendEmailVerification()
    {
        $sessionKey = Session::get('current_registration_key');
        if (!$sessionKey) {
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired.'
            ]);
        }

        // Get registration data from session
        $registrationData = Session::get($sessionKey);
        $expiresAt = Session::get($sessionKey . '_expires_at');

        if (!$registrationData || !$expiresAt || now()->gt($expiresAt)) {
            Session::forget([$sessionKey, $sessionKey . '_expires_at', 'current_registration_key']);
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired.'
            ]);
        }

        if ($registrationData['email_verified'] ?? false) {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified.'
            ]);
        }

        // Generate new email verification token
        $emailVerificationToken = bin2hex(random_bytes(32));
        $registrationData['email_verification_token'] = $emailVerificationToken;
        $registrationData['email_verification_sent_at'] = now();
        Session::put($sessionKey, $registrationData);

        // Send email verification using session data
        $sent = $this->authSecurityService->sendEmailVerificationForRegistration(
            $registrationData['email'],
            $registrationData['first_name'] . ' ' . $registrationData['last_name'],
            $emailVerificationToken
        );
        
        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'Email verification sent!' : 'Failed to send email. Please try again.'
        ]);
    }

    /**
     * Resend SMS verification for registration
     */
    public function resendSmsVerification()
    {
        $sessionKey = Session::get('current_registration_key');
        if (!$sessionKey) {
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired.'
            ]);
        }

        // Get registration data from session
        $registrationData = Session::get($sessionKey);
        $expiresAt = Session::get($sessionKey . '_expires_at');

        if (!$registrationData || !$expiresAt || now()->gt($expiresAt)) {
            Session::forget([$sessionKey, $sessionKey . '_expires_at', 'current_registration_key']);
            return response()->json([
                'success' => false,
                'message' => 'Registration session expired.'
            ]);
        }

        // Check if email was verified first
        if (!($registrationData['email_verified'] ?? false)) {
            return response()->json([
                'success' => false,
                'message' => 'Please verify your email first.'
            ]);
        }

        if ($registrationData['phone_verified'] ?? false) {
            return response()->json([
                'success' => false,
                'message' => 'Phone already verified.'
            ]);
        }

        // Generate new SMS code and store in session
        $smsCode = $this->authSecurityService->generateSmsCode();
        $registrationData['phone_verification_code'] = $smsCode;
        $registrationData['phone_verification_sent_at'] = now();
        Session::put($sessionKey, $registrationData);

        // Send SMS verification using session data
        $sent = $this->authSecurityService->sendSmsVerificationForRegistration(
            $registrationData['phone_number'],
            $registrationData['first_name'] . ' ' . $registrationData['last_name'],
            $smsCode
        );
        
        return response()->json([
            'success' => $sent,
            'message' => $sent ? 'SMS verification sent!' : 'Failed to send SMS. Please try again.'
        ]);
    }

    /**
     * Check if verification is complete and redirect accordingly
     */
    protected function checkVerificationCompletion(User $user)
    {
        if ($user->hasCompletedRequiredVerifications()) {
            // Mark account as verified
            $user->update([
                'is_verified' => true,
                'verified_at' => now(),
                'last_security_check' => now(),
            ]);

            // Clear verification session
            Session::forget(['verification_user_id', 'verification_step']);

            // Log the user in
            Auth::login($user);
        }
    }

    /**
     * Show 2FA setup page (optional step)
     */
    public function showTwoFactorSetup()
    {
        if (!Auth::check()) {
            // Redirect to the single sign-on (SSO) login route if not authenticated.
            return redirect()->route('sso.login');
        }

        $user = Auth::user();
        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('citizen.dashboard');
        }

        $secret = $this->authSecurityService->generateTotpSecret($user);
        $qrCodeUrl = $this->authSecurityService->generateQrCodeUrl($user);

        return view('citizen.auth.setup-2fa', compact('secret', 'qrCodeUrl'));
    }

    /**
     * Enable 2FA after verification
     */
    public function enableTwoFactor(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6'
        ]);

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.'
            ]);
        }

        $user = Auth::user();
        
        if ($this->authSecurityService->enableTwoFactor($user, $request->verification_code)) {
            return response()->json([
                'success' => true,
                'message' => '2FA enabled successfully!',
                'recovery_codes' => $user->two_factor_recovery_codes
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid verification code.'
        ]);
    }
}
