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
            // Session activity is handled by Laravel automatically.
            return $next($request);
        }

        // 2. Check for the SSO token and user_id in the request query.
        if (!$request->has('user_id') || !$request->has('token')) {
            // If no token, redirect to the central login.
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
        }

        $ssoUserId = $request->input('user_id');
        $ssoToken = $request->input('token');

        // 3. Call the external API to verify the token.
        $api_url = 'https://local-government-unit-1-ph.com/api/route.php?path=facilities-users';
        
        try {
            $response = Http::timeout(30)->get($api_url);

            if (!$response->successful()) {
                Log::error('SSO API request failed.', ['status' => $response->status()]);
                return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=api_failed');
            }

            $data = $response->json();

            if (!$data || !($data['success'] ?? false)) {
                Log::error('SSO API response was not successful.', ['response' => $data]);
                return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=invalid_response');
            }

            // 4. Find the matching user from the API response.
            $ssoUser = null;
            foreach ($data['data'] as $user) {
                // The token is a base64 encoded username.
                if (isset($user['id']) && $user['id'] == $ssoUserId && isset($user['username']) && hash_equals($user['username'], base64_decode($ssoToken))) {
                    $ssoUser = $user;
                    break;
                }
            }

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

                $localUser = User::updateOrCreate(
                    ['email' => $ssoUser['email']], // Use email as the unique identifier
                    [
                        'name' => $ssoUser['full_name'] ?? 'User',
                        'password' => '', // Not needed for SSO
                        'role' => $role,
                        'sso_user_id' => $ssoUser['id'],
                    ]
                );

                // 6. Log the user into the Laravel application.
                Auth::login($localUser);

                // 7. Redirect to the appropriate dashboard based on role.
                $request->session()->regenerate();
                if ($localUser->role === 'admin' || $localUser->role === 'staff') {
                    return redirect()->intended(route('admin.dashboard'));
                } else {
                    return redirect()->intended(route('citizen.dashboard'));
                }
            }

            // If no user was found in the loop.
            Log::warning('SSO token/user_id combination was not found in API response.');
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=invalid_token');

        } catch (\Exception $e) {
            Log::critical('SSO Authentication failed due to an exception.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=system_error');
        }
    }
}
