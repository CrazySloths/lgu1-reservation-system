<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    /**
     * HMAC-validated SSO login entrypoint.
     *
     * Expected query params:
     * - user_id (int)
     * - username (string)
     * - ts (int, unix timestamp)
     * - sig (string, HMAC_SHA256 over "user_id|username|ts" with shared secret)
     * Optional:
     * - name (string, full name display)
     * - role (string, defaults to 'admin' for admin portal access)
     * - redirect (string, relative path to redirect after login)
     */
    public function login(Request $request)
    {
        $userId = (int) $request->query('user_id');
        $username = (string) $request->query('username', '');
        $ts = (int) $request->query('ts');
        $sig = (string) $request->query('sig', '');
        $name = (string) $request->query('name', '');
        $role = (string) $request->query('role', 'admin');
        
        
        // Determine default redirect based on role
        $defaultRedirect = $role === 'staff' ? route('staff.dashboard') : route('admin.dashboard');
        $redirect = (string) $request->query('redirect', $defaultRedirect);

        if (!$userId || !$username || !$ts || !$sig) {
            // Provide a more user-friendly error message and redirect to proper login
            return redirect()->away('https://local-government-unit-1-ph.com/public/login.php')
                ->with('error', 'Please login through the main system portal.');
        }

        // 5-minute validity window to prevent replay
        if (abs(time() - $ts) > 300) {
            abort(401, 'SSO link expired.');
        }

        $secret = config('services.sso.shared_secret');
        if (!$secret) {
            // Fallback to the secret from your .env file
            $secret = 'your-strong-shared-secret';
        }

        $payload = $userId . '|' . $username . '|' . $ts;
        $expected = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($expected, $sig)) {
            Log::warning('Invalid SSO signature', ['user_id' => $userId, 'username' => $username]);
            abort(401, 'Invalid SSO signature.');
        }

        // Build local user attributes
        $email = filter_var($username, FILTER_VALIDATE_EMAIL)
            ? $username
            : ($username . '+' . $userId . '@sso.local');
        $displayName = $name ?: $username;

        // Find user by external_id first, then by username/email (same logic for admin and staff)
        $user = User::where('external_id', (string) $userId)->first();
        
        if (!$user) {
            // Fallback: find by username/email (works for both admin and staff)
            $user = User::where('email', $username)->first();
        }
        
        if (!$user) {
            // Create new user if not found (same logic for admin and staff)
            $user = User::create([
                'external_id' => (string) $userId,
                'name' => $displayName,
                'email' => $email,
                'password' => Str::random(40),
                'role' => $role ?: 'admin',
                // Mark as verified for SSO
                'email_verified' => true,
                'is_verified' => true,
                'verified_at' => now(),
            ]);
        } else {
            // Update existing user with latest SSO info (preserve existing role)
            $user->update([
                'name' => $displayName,
                'email' => $email,
                'email_verified' => true,
                'is_verified' => true,
                'verified_at' => now(),
                // Don't update role - preserve existing role (admin/staff/citizen)
            ]);
        }

        Auth::login($user, true);

        // Redirect to a safe destination without SSO params
        $safeRedirect = $this->sanitizeRedirect($redirect, $user->role);
        return redirect()->intended($safeRedirect);
    }

    /**
     * Only allow relative redirects to avoid open redirect vulnerabilities.
     */
    protected function sanitizeRedirect(string $redirect, string $role = 'admin'): string
    {
        // Determine the appropriate default dashboard based on role
        $defaultDashboard = $role === 'staff' ? route('staff.dashboard') : route('admin.dashboard');
        
        if (str_starts_with($redirect, 'http://') || str_starts_with($redirect, 'https://')) {
            return $defaultDashboard;
        }
        if (empty($redirect) || $redirect === '/') {
            return $defaultDashboard;
        }
        if (str_starts_with($redirect, '//')) {
            return $defaultDashboard;
        }
        return $redirect;
    }
}
