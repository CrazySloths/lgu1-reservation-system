<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class SsoAuthMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. If user is already authenticated in Laravel, just continue.
        if (Auth::check()) {
            return $next($request);
        }

        // 2. Check for the essential SSO parameters in the request query.
        // We require user_id, email (for database lookup), and token (to fulfill the basic protocol).
        if (!$request->has('user_id') || !$request->has('email') || !$request->has('token')) {
            // If parameters are missing, redirect to the central login.
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
        }

        // Capture all necessary data directly from the URL.
        $ssoUserId = $request->input('user_id');
        $ssoEmail = $request->input('email');
        $ssoUsername = $request->input('username');
        // Use the role name from the external system for mapping.
        $ssoRoleName = $request->input('subsystem_role_name'); 

        Log::info('SSO DIRECT LOGIN: Attempting to login using URL parameters (API call bypassed).', [
            'sso_id' => $ssoUserId,
            'email' => $ssoEmail,
            'role_name' => $ssoRoleName,
        ]);

        // *******************************************************************
        // 3. MAPPING: Determine Local Role based on External Role Name
        // *******************************************************************
        
        $role = 'citizen'; // Default local role
        
        // Map SSO role names to local roles (case-insensitive check)
        if (stripos($ssoRoleName, 'admin') !== false) {
            $role = 'admin';
        } elseif (stripos($ssoRoleName, 'staff') !== false) {
            $role = 'staff';
        }
        
        // 4. Update/Create local user record using EMAIL as the unique identifier.
        // This synchronizes the external user with your local 'users' table.
        try {
            $localUser = User::updateOrCreate(
                ['email' => $ssoEmail], 
                [
                    'name' => $ssoUsername ?? 'User',
                    'password' => '', // Not needed for SSO
                    'role' => $role,
                    'sso_user_id' => $ssoUserId, // Store the external ID for future reference
                ]
            );

            // 5. Log the user into the Laravel application. (This successfully sets the local session!)
            Auth::login($localUser);

            // 6. Regenerate session and redirect to the appropriate dashboard.
            $request->session()->regenerate();
            
            if ($localUser->role === 'admin' || $localUser->role === 'staff') {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('citizen.dashboard'));
            }

        } catch (\Exception $e) {
            Log::critical('SSO Authentication failed during Update/Create or Auth::login.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Critical fail: Redirect back to login with a system error flag
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=system_exception');
        }
    }
}