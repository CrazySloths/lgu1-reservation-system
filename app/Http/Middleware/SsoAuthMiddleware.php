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
     * Authenticate user from SSO URL parameters or existing session.
     */
    public function handle(Request $request, Closure $next)
    {
        // 1. If user is already authenticated in Laravel, just continue.
        if (Auth::check()) {
            return $next($request);
        }

        // 2. Check for SSO URL parameters (user coming from external SSO login)
        if ($request->has('user_id') || $request->has('username') || $request->has('email')) {
            $userId = $request->input('user_id');
            $username = $request->input('username');
            $email = $request->input('email');
            
            Log::info('SSO Middleware: Received SSO parameters', [
                'user_id' => $userId,
                'username' => $username,
                'email' => $email
            ]);
            
            // Try to find user by external_id, email, or username
            $user = User::where(function($query) use ($userId, $email, $username) {
                if ($userId) $query->orWhere('id', $userId)->orWhere('external_id', $userId);
                if ($email) $query->orWhere('email', $email);
                if ($username) $query->orWhere('name', $username);
            })->first();
                       
            if ($user) {
                Log::info('SSO Middleware: User found, logging in', [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'role' => $user->role
                ]);
                
                // Log the user in
                Auth::login($user, true); // Remember me = true
                $request->session()->regenerate();
                
                // ✅ FIX: Use redirect()->route() to clear the URL and prevent the login loop (Crucial for stability)
                if (isset($user->role) && $user->role === 'citizen')
                {
                    return redirect()->route('citizen.dashboard');
                }
                if (isset($user->role) && $user->role === 'admin')
                {
                    return redirect()->route('admin.dashboard'); 
                }
                
                // Fallback redirect for other roles
                return redirect('/');

            } else {
                Log::warning('SSO Middleware: No user found from SSO parameters', [
                    'user_id' => $userId,
                    'username' => $username,
                    'email' => $email
                ]);
                
                // TEMP FIX: User not found, but we stop the external redirect. Continue to internal login.
            }
        }

        // 3. No SSO parameters and not authenticated. Instead of redirecting externally, go to internal login.
        Log::info('SSO Middleware: User not authenticated, redirecting to internal login page.');
        return redirect()->route('login'); // Redirect to the internal Laravel login route
    }
}