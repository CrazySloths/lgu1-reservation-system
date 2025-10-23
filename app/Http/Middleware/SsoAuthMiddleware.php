<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SsoAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        //  Step 1: Already authenticated? proceed.
        if (Auth::check()) {
            Log::info('SSO Middleware: User already authenticated', ['user_id' => Auth::id()]);
            return $next($request);
        }

        //  Step 2: Check if SSO parameters exist (user coming from SSO login)
        if ($request->has('user_id') || $request->has('username') || $request->has('email')) {
            $userId = $request->input('user_id');
            $username = $request->input('username');
            $email = $request->input('email');

            Log::info('SSO Middleware: SSO params detected', [
                'user_id' => $userId,
                'username' => $username,
                'email' => $email
            ]);

            $user = User::where(function ($query) use ($userId, $email, $username) {
                if ($userId) $query->orWhere('id', $userId)->orWhere('external_id', $userId);
                if ($email) $query->orWhere('email', $email);
                if ($username) $query->orWhere('name', $username);
            })->first();

            if ($user) {
                Auth::login($user, true);
                $request->session()->regenerate();

                Log::info('SSO Middleware: Authenticated via SSO', [
                    'user_id' => $user->id,
                    'role' => $user->role
                ]);

                // ✅ Redirect based on role (prevent looping back to login)
                return match ($user->role) {
                    'citizen' => redirect()->route('citizen.dashboard'),
                    'admin' => redirect()->route('admin.dashboard'),
                    default => redirect('/'),
                };
            }

            Log::warning('SSO Middleware: No user found for SSO params');
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?error=user_not_found');
        }

        //  Step 3: If not authenticated and no SSO params, redirect to SSO login ONCE
        Log::info('SSO Middleware: Not authenticated, redirecting to external SSO login');
        return redirect()->away('https://local-government-unit-1-ph.com/public/login.php?redirect=' . urlencode($request->fullUrl()));
    }
}
