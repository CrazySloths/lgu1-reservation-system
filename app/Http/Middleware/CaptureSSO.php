<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureSSO
{
    /**
     * Handle an incoming request.
     * Captures SSO parameters and stores them in session
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if SSO parameters are present in the request
        if ($request->has('user_id')) {
            // Store SSO data in session
            $request->session()->put('sso_user', [
                'id' => $request->input('user_id'),
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'role' => $request->input('role', 'citizen')
            ]);
        }
        
        // Make SSO user data available to all views
        if ($request->session()->has('sso_user')) {
            $ssoUser = $request->session()->get('sso_user');
            view()->share('ssoUser', (object) $ssoUser);
        }

        return $next($request);
    }
}

