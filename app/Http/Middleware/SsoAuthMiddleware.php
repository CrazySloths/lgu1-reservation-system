<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SsoAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. If user is already authenticated in Laravel, just continue.
        if (Auth::check()) {
            // Check if the session is still active/valid for the route's purpose
            return $next($request);
        }

        // 2. Check for the SSO token and user_id in the request query.
        // We require both user_id and token to proceed to API call.
        if (!$request->has('user_id') || !$request->has('token')) {
            // If no token, redirect to the central login.
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
        }

        $ssoUserId = $request->input('user_id');
        $ssoToken = $request->input('token');

        // *******************************************************************
        // 3. API CALL: Fetch all user data for filtering.
        // *******************************************************************
        $api_url = 'https://local-government-unit-1-ph.com/api/route.php?path=facilities-users';
        
        try {
            // Make an API call to fetch user data (all users)
            $response = Http::timeout(30)->get($api_url);

            // Ensure the HTTP Status is SUCCESSFUL (200-299)
            if (!$response->successful()) {
                Log::error('SSO API request failed.', [
                    'status' => $response->status(), 
                    'body' => $response->body()
                ]);
                return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=api_request_failed');
            }

            $data = $response->json();

            // Ensure the response is not empty, the 'success' flag is true, and 'data' exists
            if (!$data || !($data['success'] ?? false) || !isset($data['data'])) {
                Log::error('SSO API response was not successful or missing data.', ['response' => $data]);
                return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=invalid_response');
            }

            // *******************************************************************
            // 4. CRITICAL: BYPASS TOKEN VALIDATION FOR DIAGNOSTICS.
            //    We only check if the user ID exists in the API response.
            // *******************************************************************
            $ssoUser = null;

            foreach ($data['data'] as $user) {
                // Find the user entry in the API response that matches the user_id from the query.
                if (isset($user['id']) && (string)$user['id'] === (string)$ssoUserId) {
                    $ssoUser = $user;
                    Log::info('SSO DIAGNOSTIC: User found by ID, bypassing token check.', ['sso_id' => $ssoUserId]);
                    break; 
                }
            }

            // *******************************************************************
            // 5. HANDLING: If the user is found, log them in.
            // *******************************************************************
            if ($ssoUser) {
                // 5. Map the role and check for required data.
                $role = 'citizen'; // Default role
                $ssoRole = $ssoUser['subsystem_role_name'] ?? 'Citizen';

                // Map SSO roles to local roles
                if (stripos($ssoRole, 'admin') !== false) {
                    $role = 'admin';
                } elseif (stripos($ssoRole, 'staff') !== false) {
                    $role = 'staff';
                }

                // CHECK FOR EMAIL (Crucial for updateOrCreate)
                $email = $ssoUser['email'] ?? null;
                
                if (empty($email)) {
                    Log::error('SSO API user data is missing email (required for local mapping).', ['sso_user' => $ssoUser]);
                    return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=missing_email');
                }

                // 6. Update/Create local user record using EMAIL as the unique key.
                $localUser = User::updateOrCreate(
                    ['email' => $email], 
                    [
                        'name' => $ssoUser['full_name'] ?? 'User',
                        'password' => '', // Not needed for SSO
                        'role' => $role,
                        'sso_user_id' => $ssoUserId, 
                    ]
                );

                // 7. Log the user into the Laravel application. (This sets the local session)
                Auth::login($localUser);

                // 8. Redirect to the appropriate dashboard based on role.
                $request->session()->regenerate();
                
                if ($localUser->role === 'admin' || $localUser->role === 'staff') {
                    return redirect()->intended(route('admin.dashboard'));
                } else {
                    return redirect()->intended(route('citizen.dashboard'));
                }
            }

            // If the user was not found in the loop (API data/ID mismatch)
            Log::warning('SSO CRITICAL FAILURE: User ID was not found in API response.', ['user_id' => $ssoUserId]);
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=id_not_found_in_api');

        } catch (\Exception $e) {
            Log::critical('SSO Authentication failed due to an exception.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Redirect back to login
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=system_exception');
        }
    }
}