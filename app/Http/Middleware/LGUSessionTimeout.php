<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LGUSessionTimeout
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to authenticated admin/staff users
        if (!Auth::check() || !$this->isAdminOrStaff()) {
            return $next($request);
        }

        // Check if this is an LGU authenticated session
        if (!Session::has('lgu_last_activity')) {
            return $next($request);
        }

        // Session timeout check (3600 seconds = 1 hour)
        $lastActivity = Session::get('lgu_last_activity');
        $sessionTimeout = 3600; // 1 hour in seconds

        if ($lastActivity && (time() - $lastActivity > $sessionTimeout)) {
            // Session has expired
            Log::info('LGU Auth: Session timeout for user', [
                'user_id' => Auth::id(),
                'last_activity' => $lastActivity,
                'current_time' => time(),
                'timeout_duration' => $sessionTimeout
            ]);

            // Clear all session data
            Auth::logout();
            Session::flush();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')->withErrors([
                'session' => 'Your session has expired due to inactivity. Please log in again.'
            ]);
        }

        // Update last activity timestamp
        Session::put('lgu_last_activity', time());

        return $next($request);
    }

    /**
     * Check if current user is admin or staff
     */
    private function isAdminOrStaff()
    {
        $user = Auth::user();
        return $user && ($user->isAdmin() || $user->isStaff());
    }
}
