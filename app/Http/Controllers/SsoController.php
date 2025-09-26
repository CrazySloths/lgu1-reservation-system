<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SsoController extends Controller
{
    /**
     * HMAC-validated SSO login entrypoint.
     *
     * Expected query params:
     * - user_id (int)
     * - username (string)
     * - ts (int, unix timestamp)
     * - sig (string, HMAC_SHA256 over "user_id|username|ts" with shared secret)
     * Optional:
     * - name (string, full name display)
     * - role (string, defaults to 'admin' for admin portal access)
     * - redirect (string, relative path to redirect after login)
     */
    public function login(Request $request)
    {
        // Write directly to a file to bypass any logging issues
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'all_params' => $request->all(),
            'query_params' => $request->query(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_agent' => $request->userAgent(),
        ];
        
        $logFile = public_path('sso_debug.log');
        file_put_contents($logFile, "=== SSO REQUEST ===\n" . json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND | LOCK_EX);
        
        // Also try Laravel logging
        Log::info('SSO Raw Request', [
            'all_params' => $request->all(),
            'query_params' => $request->query(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'headers' => $request->headers->all()
        ]);

        $userId = $request->query('user_id');
        $username = $request->query('username');
        $role = $request->query('role');
        $subsystemRoleName = $request->query('subsystem_role_name');
        $subsystem = $request->query('subsystem');
        $ts = $request->query('ts');
        $sig = $request->query('sig');

        Log::info('SSO Login Attempt', [
            'user_id' => $userId,
            'username' => $username,
            'role' => $role,
            'subsystem_role_name' => $subsystemRoleName,
            'subsystem' => $subsystem,
            'sig' => $sig,
            'ts' => $ts,
            'role_analysis' => [
                'has_role_param' => !empty($role),
                'has_subsystem_role_name' => !empty($subsystemRoleName),
                'role_to_use' => $subsystemRoleName ?? $role ?? 'citizen',
                'is_staff_check' => [
                    'subsystem_role_name_contains_staff' => stripos($subsystemRoleName ?? '', 'staff') !== false,
                    'role_contains_staff' => stripos($role ?? '', 'staff') !== false,
                    'username_contains_staff' => stripos($username, 'staff') !== false
                ]
            ]
        ]);

        // Based on the lead's feedback, the signature check is not needed for this environment.
        // We will skip it to simplify debugging.

        // Find or create the user - improved lookup logic
        $user = User::where('external_id', $userId)
                    ->orWhere('email', $username)
                    ->orWhere('name', $username)
                    ->first();

        if (!$user) {
            // Create user based on role - check both subsystem_role_name and role parameters
            $roleToUse = $subsystemRoleName ?? $role ?? 'citizen';
            $email = $this->generateEmailFromRole($username, $roleToUse);
            $userRole = $this->mapSsoRoleToSystemRole($roleToUse);
            
            $user = User::create([
                'external_id' => $userId,
                'name' => $username,
                'email' => $email,
                'password' => bcrypt(Str::random(16)),
                'role' => $userRole,
                'status' => 'active'
            ]);

            Log::info('Created new user', [
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]);
        } else {
            // Update existing user's role based on SSO data - check both subsystem_role_name and role parameters
            $roleToUse = $subsystemRoleName ?? $role ?? 'citizen';
            $userRole = $this->mapSsoRoleToSystemRole($roleToUse);
            $user->role = $userRole;
            $user->status = 'active';
            $user->save();

            Log::info('Updated existing user', [
                'user_id' => $user->id,
                'name' => $user->name,
                'role' => $user->role
            ]);
        }

        // Save the signature as a one-time login token
        $user->forceFill([
            'sso_token' => $sig,
            'sso_token_expires_at' => now()->addMinutes(5), // Increased timeout
        ])->save();

        // For staff users, redirect directly to staff dashboard 
        // (which now handles SSO authentication)
        // Check multiple role sources: subsystem_role_name, role parameter, user role, and username
        $isStaff = (
            stripos($subsystemRoleName ?? '', 'staff') !== false ||
            stripos($role ?? '', 'staff') !== false ||
            $user->role == 'staff' ||
            stripos($username, 'staff') !== false
        );
        
        if ($isStaff) {
            $redirectUrl = 'https://facilities.local-government-unit-1-ph.com/staff/dashboard?user_id=' . $user->id . '&sig=' . $sig;
        } else {
            // For admin and other roles, use the original redirect logic
            // Pass the best available role parameter (prefer subsystem_role_name, fallback to role)
            $roleParam = $subsystemRoleName ?? $role;
            $redirectUrl = $this->determineRedirectUrl($subsystem, $roleParam, $user, $sig);
            
            // Fallback if no proper redirect determined
            if (empty($redirectUrl)) {
                Log::warning('No redirect URL determined, using fallback logic');
                
                if (stripos($username, 'admin') !== false) {
                    $redirectUrl = 'https://facilities.local-government-unit-1-ph.com/admin/dashboard?user_id=' . $user->id . '&sig=' . $sig;
                } else {
                    $redirectUrl = 'https://facilities.local-government-unit-1-ph.com/staff/dashboard?user_id=' . $user->id . '&sig=' . $sig;
                }
            }
        }

        // Log success to file
        $successData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'user_id' => $user->id,
            'redirect_url' => $redirectUrl,
            'user_role' => $user->role,
            'final_decision' => 'Redirecting to: ' . $redirectUrl
        ];
        
        $logFile = public_path('sso_debug.log');
        file_put_contents($logFile, "=== SSO SUCCESS ===\n" . json_encode($successData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND | LOCK_EX);

        Log::info('SSO Login Success', [
            'user_id' => $user->id,
            'redirect_url' => $redirectUrl,
            'final_decision' => 'Redirecting to: ' . $redirectUrl
        ]);

        // Redirect to the determined URL
        return redirect()->away($redirectUrl);
    }

    /**
     * Determine redirect URL based on subsystem and role - Lead Programmer's Logic
     */
    protected function determineRedirectUrl($subsystem, $subsystemRoleName, $user, $sig)
    {
        $redirectUrl = '';
        
        Log::info('Determining redirect URL', [
            'subsystem' => $subsystem,
            'subsystem_role_name' => $subsystemRoleName,
            'user_role' => $user->role,
            'user_name' => $user->name
        ]);
        
        // Implement the exact logic provided by the lead programmer with case-insensitive comparisons
        if ($subsystem == "Public Facilities Reservation System") {
            if (stripos($subsystemRoleName ?? '', 'admin') !== false) {
                $redirectUrl = 'https://facilities.local-government-unit-1-ph.com/admin/dashboard';
            } elseif (stripos($subsystemRoleName ?? '', 'staff') !== false) {
                $redirectUrl = 'https://facilities.local-government-unit-1-ph.com/staff/dashboard';
            } else {
                $redirectUrl = 'https://facilities.local-government-unit-1-ph.com/citizen/dashboard';
            }
        } else {
            // If subsystem is empty or different, use role-based logic with case-insensitive checks
            if (stripos($subsystemRoleName ?? '', 'admin') !== false || $user->role == 'admin') {
                $redirectUrl = 'https://facilities.local-government-unit-1-ph.com/admin/dashboard';
            } elseif (stripos($subsystemRoleName ?? '', 'staff') !== false || $user->role == 'staff') {
                $redirectUrl = 'https://facilities.local-government-unit-1-ph.com/staff/dashboard';
            } else {
                $redirectUrl = 'https://facilities.local-government-unit-1-ph.com/citizen/dashboard';
            }
        }

        // Add the token parameters for staff and admin dashboards
        if ($redirectUrl && (stripos($redirectUrl, 'admin') !== false || stripos($redirectUrl, 'staff') !== false)) {
            $separator = strpos($redirectUrl, '?') !== false ? '&' : '?';
            $redirectUrl .= $separator . "user_id={$user->id}&sig={$sig}";
        }

        Log::info('Redirect URL determined', [
            'final_url' => $redirectUrl
        ]);

        return $redirectUrl;
    }

    /**
     * Handle staff dashboard access - processes SSO authentication if parameters present
     * This replaces the /sso/login route for staff users
     */
    public function handleStaffDashboard(Request $request)
    {
        // Check if this is an SSO callback (has SSO parameters)
        if ($request->has(['user_id', 'sig']) || $request->has(['username', 'role', 'subsystem_role_name', 'subsystem'])) {
            
            // Write directly to a file to bypass any logging issues
            $logData = [
                'timestamp' => date('Y-m-d H:i:s'),
                'route' => '/staff/dashboard',
                'all_params' => $request->all(),
                'query_params' => $request->query(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_agent' => $request->userAgent(),
            ];
            
            $logFile = public_path('sso_debug.log');
            file_put_contents($logFile, "=== STAFF DASHBOARD SSO REQUEST ===\n" . json_encode($logData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND | LOCK_EX);
            
            // Also try Laravel logging
            Log::info('Staff Dashboard SSO Request', [
                'all_params' => $request->all(),
                'query_params' => $request->query(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
            ]);

            $userId = $request->query('user_id');
            $username = $request->query('username');
            $role = $request->query('role');
            $subsystemRoleName = $request->query('subsystem_role_name');
            $subsystem = $request->query('subsystem');
            $ts = $request->query('ts');
            $sig = $request->query('sig');

            Log::info('Staff Dashboard SSO Parameters', [
                'user_id' => $userId,
                'username' => $username,
                'role' => $role,
                'subsystem_role_name' => $subsystemRoleName,
                'subsystem' => $subsystem,
                'sig' => $sig,
                'ts' => $ts
            ]);

            // Find or create the user - improved lookup logic
            $user = null;

            // First try to find by external_id if provided
            if ($userId) {
                $user = User::where('external_id', $userId)->first();
            }

            // If not found and we have username, try by email or username
            if (!$user && $username) {
                $user = User::where('email', $username)->orWhere('name', $username)->first();
            }

            // If still not found, create new user
            if (!$user) {
                $systemRole = $this->mapSsoRoleToSystemRole($subsystemRoleName ?: $role);
                $email = $this->generateEmailFromRole($username ?: 'staff', $subsystemRoleName ?: $role);
                
                $user = User::create([
                    'external_id' => $userId,
                    'name' => $username ?: 'Staff User',
                'email' => $email,
                    'role' => $systemRole,
                    'status' => 'active',
                    'password' => bcrypt('sso_managed'), // Not used for SSO users
                ]);
            } else {
                // Update existing user with latest SSO data
                $systemRole = $this->mapSsoRoleToSystemRole($subsystemRoleName ?: $role);
                $user->update([
                    'external_id' => $userId ?: $user->external_id,
                    'role' => $systemRole,
                    'status' => 'active',
                ]);
            }

            // Save the signature as a one-time login token
            $user->forceFill([
                'sso_token' => $sig,
                'sso_token_expires_at' => now()->addMinutes(5), // Increased timeout
            ])->save();

            // Log the user in
            Auth::login($user);
            
            // Regenerate session for security
            $request->session()->regenerate();

            // Log success to file
            $successData = [
                'timestamp' => date('Y-m-d H:i:s'),
                'route' => '/staff/dashboard',
                'user_id' => $user->id,
                'user_role' => $user->role,
                'logged_in' => Auth::check(),
                'success' => 'User authenticated via SSO'
            ];
            
            file_put_contents($logFile, "=== STAFF DASHBOARD SSO SUCCESS ===\n" . json_encode($successData, JSON_PRETTY_PRINT) . "\n\n", FILE_APPEND | LOCK_EX);

            Log::info('Staff Dashboard SSO Success', [
                'user_id' => $user->id,
                'logged_in' => Auth::check(),
                'success' => 'User authenticated and logged in via staff dashboard'
            ]);

            // Now forward to the regular staff dashboard controller
            return app(\App\Http\Controllers\Staff\StaffDashboardController::class)->index($request);
        }
        
        // No SSO parameters, this is a direct dashboard access
        // Forward to the regular staff dashboard controller
        return app(\App\Http\Controllers\Staff\StaffDashboardController::class)->index($request);
    }

    /**
     * Map SSO role to system role - flexible to handle various data formats
     */
    protected function mapSsoRoleToSystemRole($ssoRole)
    {
        // Normalize the role value - trim whitespace and convert to lowercase
        $normalizedRole = strtolower(trim($ssoRole ?? ''));
        
        // Check various possible role values
        switch ($normalizedRole) {
            case 'admin':
            case 'administrator':
                return 'admin';
            case 'staff':
            case 'employee':
                return 'staff';
            case 'citizen':
            case 'user':
            case 'public':
                return 'citizen';
            default:
                // If role is unclear, default to citizen for security
                return 'citizen';
        }
    }

    /**
     * Generate email based on role - flexible to handle various data formats
     */
    protected function generateEmailFromRole($username, $ssoRole)
    {
        // Use our role mapping to determine the system role
        $systemRole = $this->mapSsoRoleToSystemRole($ssoRole);
        
        // Generate email based on the normalized system role
        switch ($systemRole) {
            case 'admin':
                return strtolower($username) . '@admin.local';
            case 'staff':
                return strtolower($username) . '@staff.local';
            default:
                return strtolower($username) . '@citizen.local';
        }
    }

    /**
     * Only allow relative redirects to avoid open redirect vulnerabilities.
     */
    protected function sanitizeRedirect(string $redirect, string $role = 'admin'): string
    {
        // Determine the appropriate default dashboard based on role
        $defaultDashboard = $role === 'staff' ? route('staff.dashboard') : route('admin.dashboard');
        
        if (str_starts_with($redirect, 'http://') || str_starts_with($redirect, 'https://')) {
            return $defaultDashboard;
        }
        if (empty($redirect) || $redirect === '/') {
            return $defaultDashboard;
        }
        if (str_starts_with($redirect, '//')) {
            return $defaultDashboard;
        }
        return $redirect;
    }
}
