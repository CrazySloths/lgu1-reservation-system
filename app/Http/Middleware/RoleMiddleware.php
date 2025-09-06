<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('citizen.login');
        }

        $user = Auth::user();

        // Check if user has the required role
        if ($user->role !== $role) {
            // Redirect based on user's actual role and what they're trying to access
            if ($role === 'admin') {
                if ($user->isCitizen()) {
                    return redirect()->route('citizen.dashboard')->with('error', 'Access denied. You do not have administrative privileges.');
                } elseif ($user->isStaff()) {
                    return redirect()->route('staff.dashboard')->with('error', 'Access denied. Admin privileges required.');
                }
            }

            if ($role === 'staff') {
                if ($user->isCitizen()) {
                    return redirect()->route('citizen.dashboard')->with('error', 'Access denied. Staff privileges required.');
                } elseif ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard')->with('error', 'Access denied. This is a staff-only area.');
                }
            }

            if ($role === 'citizen') {
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard')->with('error', 'Access denied. Please use the admin portal.');
                } elseif ($user->isStaff()) {
                    return redirect()->route('staff.dashboard')->with('error', 'Access denied. Please use the staff portal.');
                }
            }

            // Default: Access denied
            abort(403, 'Access denied. Insufficient privileges.');
        }

        return $next($request);
    }
}