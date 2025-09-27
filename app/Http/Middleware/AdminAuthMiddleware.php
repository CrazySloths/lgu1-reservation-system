<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request for admin routes
     * More permissive - only sets up auth if possible, doesn't block access
     */
    public function handle(Request $request, Closure $next)
    {
        // If Laravel Auth is already working, great!
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }
        
        // If we have user_id in URL, try to set up Laravel Auth
        if ($request->has('user_id') && !Auth::check()) {
            try {
                $userId = (int) $request->get('user_id');
                $user = User::where('id', $userId)->where('role', 'admin')->first();
                
                if ($user) {
                    // Log the user in via Laravel Auth for this session
                    Auth::login($user);
                    
                    // Regenerate session for security
                    $request->session()->regenerate();
                    
                    Log::info('Admin authenticated via URL parameters', [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'url' => $request->fullUrl()
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Admin URL authentication attempt failed', [
                    'user_id' => $request->get('user_id'),
                    'error' => $e->getMessage()
                ]);
                // Don't block access even if this fails
            }
        }
        
        // Always allow the request to continue
        // The sidebar will handle showing profile based on available auth
        return $next($request);
    }
}
