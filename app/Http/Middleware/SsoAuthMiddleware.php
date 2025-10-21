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

        // validate essential SSO parameters
        if (!$request->has('user_id') || !$request->has('email') || !$request->has('token')) {
            Log::error('SSO Validation failed: Missing essential parameters (user_id/email/token).');
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=sso_data_missing');
        }

        $ssoUserId = $request->input('user_id');
        $ssoEmail = $request->input('email');
        $ssoUsername = $request->input('username');
        $ssoRoleName = $request->input('subsystem_role_name'); 

        $cleanedEmail = strtolower(trim($ssoEmail));

        // final Validation: Prevent 'Column 'email' cannot be null' error (SQLSTATE[23000]: 1048)
        if (empty($cleanedEmail)) {
            Log::critical('SSO Validation failed: Cleaned Email parameter is empty or null.', [
                'sso_id' => $ssoUserId, 
                'raw_email' => $ssoEmail
            ]);
            // redirect back to login, as we cannot proceed without an email
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=empty_email_from_sso');
        }

        // map SSO role to local role
        $role = 'citizen'; 
        if (stripos($ssoRoleName, 'admin') !== false) {
            $role = 'admin';
        } elseif (stripos($ssoRoleName, 'staff') !== false) {
            $role = 'staff';
        }
        
        // authenticate or create the local user record
        try {
            // find or create the local user record using the cleaned email as the unique identifier.
            $localUser = User::updateOrCreate(
                ['email' => $cleanedEmail], 
                [
                    'name' => $ssoUsername ?? 'User',
                    'password' => '', 
                    'role' => $role,
                    'external_id' => $ssoUserId, 
                ]
            );

            // log the user into the Laravel application. (sets the local session!)
            Auth::login($localUser);

            // regenerate session ID for security
            $request->session()->regenerate();
            
            // redirect to intended page or dashboard based on role
            
            if ($localUser->role === 'admin' || $localUser->role === 'staff') {
                return redirect()->intended(route('admin.dashboard'));
            } else {
                return redirect()->intended(route('citizen.dashboard'));
            }

        } catch (\Exception $e) {
            // log the database failure that prevented Auth::login()
            Log::critical('SSO Authentication failed during Update/Create or Auth::login (DB Error).', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // redirect back to login with a generic error message
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=system_db_exception');
        }
    }
}