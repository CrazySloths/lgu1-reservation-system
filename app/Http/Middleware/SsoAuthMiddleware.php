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
            return $next($request);
        }

        // 2. Check for the SSO token and user_id in the request query.
        if (!$request->has('user_id') || !$request->has('token')) {
            // If no token, redirect to the central login (This is correct).
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
        }

        $ssoUserId = $request->input('user_id');
        $ssoToken = $request->input('token');

        // *******************************************************************
        // 3. API CALL: Use a more reliable API endpoint/method for SSO.
        //    Since we don't have a specific validation endpoint, we'll use the current 
        //    endpoint but ensure it works. IF you have a validation endpoint (e.g., /api/validate-token), REPLACE THIS!
        // *******************************************************************
        $api_url = 'https://local-government-unit-1-ph.com/api/route.php?path=facilities-users';
        
        try {
            // Make an API call to fetch user data (all users)
            $response = Http::timeout(30)->get($api_url);

            // Ensure the HTTP Status is SUCCESSFUL (200-299)
            if (!$response->successful()) {
                Log::error('SSO API request failed.', [
                    'status' => $response->status(), 
                    'body' => $response->body() // Add body for debugging
                ]);
                // Redirect back to login to retry, but with an error code
                return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=api_request_failed');
            }

            $data = $response->json();

            // Ensure the response is not empty, the 'success' flag is true, and 'data' exists
            if (!$data || !($data['success'] ?? false) || !isset($data['data'])) {
                Log::error('SSO API response was not successful or missing data.', ['response' => $data]);
                return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=invalid_response');
            }

            // *******************************************************************
            // 4. USER VALIDATION: Find the user and VALIDATE the token.
            // *******************************************************************
            $ssoUser = null;
            // Decode the token BEFORE the loop to avoid errors if it's invalid
            $decodedUsername = base64_decode($ssoToken, true);

            foreach ($data['data'] as $user) {
                // Ensure the user in the API response has ID and Username keys
                if (isset($user['id']) && $user['id'] == $ssoUserId && isset($user['username'])) {
                    
                    // CRITICAL: Check if the token (decoded username) matches the username from the API.
                    // This is your makeshift validation.
                    if ($decodedUsername !== false && hash_equals($user['username'], $decodedUsername)) {
                        $ssoUser = $user;
                        break; // Stop searching once the user is found
                    }
                }
            }

            // *******************************************************************
            // 5. HANDLING: If the user is found, log them in.
            // *******************************************************************
            if ($ssoUser) {
                // 5. We found the user. Let's map the role and create/update them locally.
                $role = 'citizen'; // Default role
                $ssoRole = $ssoUser['subsystem_role_name'] ?? 'Citizen';

                // Map SSO roles to local roles
                if (stripos($ssoRole, 'admin') !== false) {
                    $role = 'admin';
                } elseif (stripos($ssoRole, 'staff') !== false) {
                    $role = 'staff'; // Assuming you have a 'staff' role
                }

                // Ensure the 'email' key exists before using it
                $email = $ssoUser['email'] ?? null;
                
                if (empty($email)) {
                    Log::error('SSO API user data is missing email.', ['sso_user' => $ssoUser]);
                    return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=missing_email');
                }

                $localUser = User::updateOrCreate(
                    ['email' => $email], // Use email as the unique identifier
                    [
                        'name' => $ssoUser['full_name'] ?? 'User',
                        'password' => '', // Not needed for SSO
                        'role' => $role,
                        // Use the variable $ssoUserId (the ID from the query), 
                        // as this is the ID we expect to match
                        'sso_user_id' => $ssoUserId, 
                    ]
                );

                // 6. Log the user into the Laravel application.
                Auth::login($localUser);

                // 7. Redirect to the appropriate dashboard based on role.
                $request->session()->regenerate();
                
                // Use intended redirect to go back to the page they wanted (e.g., dashboard)
                if ($localUser->role === 'admin' || $localUser->role === 'staff') {
                    return redirect()->intended(route('admin.dashboard'));
                } else {
                    return redirect()->intended(route('citizen.dashboard'));
                }
            }

            // If the user was not found in the loop (malfunctioned validation)
            Log::warning('SSO token/user_id combination was not found in API response or failed validation.', ['user_id' => $ssoUserId, 'token' => $ssoToken]);
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=invalid_token_final_check');

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