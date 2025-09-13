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
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Check if user has the required role
        if ($user->role !== $role) {
            // If citizen tries to access admin or staff routes, redirect to citizen portal
            if (($role === 'admin' || $role === 'staff') && $user->isCitizen()) {
                return redirect()->route('citizen.dashboard')->with('error', 'Access denied. You do not have administrative privileges.');
            }

            // If admin tries to access citizen or staff routes
            if ($role === 'citizen' && $user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('error', 'Access denied. Please use the admin portal.');
            }
            
            if ($role === 'staff' && $user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('error', 'Access denied. You are an administrator, not staff.');
            }

            // If staff tries to access admin or citizen routes
            if ($role === 'admin' && $user->isStaff()) {
                return redirect()->route('staff.dashboard')->with('error', 'Access denied. You do not have administrative privileges.');
            }
            
            if ($role === 'citizen' && $user->isStaff()) {
                return redirect()->route('staff.dashboard')->with('error', 'Access denied. Please use the staff portal.');
            }

            // Default: Access denied
            abort(403, 'Access denied. Insufficient privileges.');
        }

        return $next($request);
    }
}