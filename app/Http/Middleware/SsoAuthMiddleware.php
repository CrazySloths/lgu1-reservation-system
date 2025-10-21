<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;



class SsoAuthMiddleware
{
    /**
     * Handle an incoming request.
     * 
     */
    public function handle(Request $request, Closure $next)
    {
        // If user is already authenticated in Laravel, just continue.
        if (Auth::check()) {
            return $next($request);
        }

        Log::info('SSO Middleware: User not authenticated, redirecting to SSO login page.');
        
        return redirect()->away('https://local-government-unit-1-ph.com/public/login.php');
        
    }
}