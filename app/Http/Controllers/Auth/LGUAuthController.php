<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class LGUAuthController extends Controller
{
    /**
     * Handle LGU1 token-based authentication
     */
    public function handleTokenLogin(Request $request)
    {
        // Check if user is already authenticated
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        $userId = $request->get('user_id');
        $token = $request->get('token');

        if (empty($userId) || empty($token)) {
            Log::error('LGU Auth: Missing user_id or token parameters');
            return redirect()->route('admin.login')->withErrors([
                'login' => 'Invalid authentication parameters.'
            ]);
        }

        // Validate input
        $userId = $this->validateInput($userId, 'int');
        $token = $this->validateInput($token);

        if ($userId === false || empty($token)) {
            Log::error('LGU Auth: Invalid user_id or token provided', [
                'user_id' => $userId,
                'token_length' => strlen($token ?? '')
            ]);
            return redirect()->route('admin.login')->withErrors([
                'login' => 'Invalid authentication credentials.'
            ]);
        }

        // Validate API URL (SSRF protection)
        $allowedHosts = ['local-government-unit-1-ph.com'];
        $apiUrl = 'https://local-government-unit-1-ph.com/api/route.php?path=facilities-users';
        $parsedUrl = parse_url($apiUrl);

        if (!in_array($parsedUrl['host'], $allowedHosts)) {
            Log::error('LGU Auth: Unauthorized API host attempted', [
                'host' => $parsedUrl['host']
            ]);
            return redirect()->route('admin.login')->withErrors([
                'login' => 'Authentication service unavailable.'
            ]);
        }

        // Fetch user data from LGU API
        $response = $this->fetchWithCurl($apiUrl);

        if (!$response) {
            Log::error('LGU Auth: Failed to connect to authentication service');
            return redirect()->route('admin.login')->withErrors([
                'login' => 'Authentication service unavailable. Please try again.'
            ]);
        }

        $data = json_decode($response, true);
        if (!$data || !$data['success']) {
            Log::error('LGU Auth: API returned unsuccessful response', [
                'response' => $response
            ]);
            return redirect()->route('admin.login')->withErrors([
                'login' => 'Authentication failed. Please verify your credentials.'
            ]);
        }

        // Find and validate user
        $authenticatedUser = null;
        foreach ($data['data'] as $apiUser) {
            if ($apiUser['id'] == $userId && hash_equals($apiUser['username'], base64_decode($token))) {
                $authenticatedUser = $apiUser;
                break;
            }
        }

        if (!$authenticatedUser) {
            Log::error('LGU Auth: User authentication failed', [
                'user_id' => $userId
            ]);
            return redirect()->route('admin.login')->withErrors([
                'login' => 'Authentication failed. Invalid credentials.'
            ]);
        }

        // Create or update user in Laravel system
        $laravelUser = $this->createOrUpdateUser($authenticatedUser);

        if (!$laravelUser) {
            Log::error('LGU Auth: Failed to create/update Laravel user', [
                'api_user' => $authenticatedUser
            ]);
            return redirect()->route('admin.login')->withErrors([
                'login' => 'Account setup failed. Please contact administrator.'
            ]);
        }

        // Store LGU session data
        Session::put([
            'lgu_user_id' => (int)$authenticatedUser['id'],
            'lgu_username' => $this->validateInput($authenticatedUser['username']),
            'lgu_user_name' => $this->validateInput($authenticatedUser['full_name']),
            'lgu_user_role' => $this->validateInput($authenticatedUser['subsystem_role_name']),
            'lgu_subsystem_name' => 'Housing and Resettlement Management',
            'lgu_subsystem_role_name' => $this->validateInput($authenticatedUser['subsystem_role_name']),
            'lgu_login_time' => time(),
            'lgu_last_activity' => time(),
        ]);

        // Log the user into Laravel's Auth system
        Auth::login($laravelUser);

        Log::info('LGU Auth: User successfully authenticated', [
            'user_id' => $authenticatedUser['id'],
            'username' => $authenticatedUser['username'],
            'role' => $authenticatedUser['subsystem_role_name']
        ]);

        return $this->redirectBasedOnRole($laravelUser);
    }

    /**
     * Show admin login form with LGU authentication instructions
     */
    public function showLoginForm()
    {
        return view('auth.lgu-login');
    }

    /**
     * Handle logout and session cleanup
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Clear Laravel auth
        Auth::logout();
        
        // Clear LGU session data
        Session::forget([
            'lgu_user_id',
            'lgu_username', 
            'lgu_user_name',
            'lgu_user_role',
            'lgu_subsystem_name',
            'lgu_subsystem_role_name',
            'lgu_login_time',
            'lgu_last_activity'
        ]);
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('LGU Auth: User logged out successfully', [
            'user_id' => $user ? $user->id : 'unknown'
        ]);

        return redirect()->route('admin.login')->with('success', 'You have been logged out successfully.');
    }

    /**
     * Create or update user in Laravel system based on LGU data
     */
    private function createOrUpdateUser($apiUser)
    {
        try {
            // Map LGU role to Laravel role
            $role = $this->mapLGURole($apiUser['subsystem_role_name']);
            
            $userData = [
                'name' => $this->validateInput($apiUser['full_name']),
                'email' => $this->validateInput($apiUser['username']) . '@lgu1.gov.ph', // Create email if needed
                'role' => $role,
                'lgu_user_id' => (int)$apiUser['id'],
                'lgu_username' => $this->validateInput($apiUser['username']),
                'is_verified' => true,
                'email_verified' => true,
            ];

            // Look for existing user by LGU user ID first, then by email
            $user = User::where('lgu_user_id', $apiUser['id'])->first();
            
            if (!$user) {
                $user = User::where('email', $userData['email'])->first();
            }

            if ($user) {
                // Update existing user
                $user->update($userData);
            } else {
                // Create new user
                $userData['password'] = Hash::make(uniqid()); // Random password since we use LGU auth
                $user = User::create($userData);
            }

            return $user;
        } catch (\Exception $e) {
            Log::error('LGU Auth: Error creating/updating user', [
                'error' => $e->getMessage(),
                'api_user' => $apiUser
            ]);
            return null;
        }
    }

    /**
     * Map LGU role to Laravel role
     */
    private function mapLGURole($lguRole)
    {
        $roleMapping = [
            'Administrative & Records Staff' => 'staff',
            'Administrator' => 'admin',
            'Admin' => 'admin',
            'Staff' => 'staff',
            // Add more mappings as needed
        ];

        return $roleMapping[$lguRole] ?? 'staff';
    }

    /**
     * Redirect user based on their role
     */
    private function redirectBasedOnRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isStaff()) {
            return redirect()->route('staff.dashboard');
        } else {
            // Fallback for unexpected roles
            return redirect()->route('admin.dashboard');
        }
    }

    /**
     * Validate and sanitize input
     */
    private function validateInput($input, $type = 'string')
    {
        if ($input === null || $input === '') {
            return $type === 'int' ? false : '';
        }

        switch ($type) {
            case 'int':
                $filtered = filter_var($input, FILTER_VALIDATE_INT);
                return $filtered !== false ? $filtered : false;
            
            case 'string':
            default:
                // Remove any potentially dangerous characters
                $filtered = filter_var($input, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
                return trim($filtered);
        }
    }

    /**
     * Fetch data using cURL with security measures
     */
    private function fetchWithCurl($url)
    {
        try {
            $ch = curl_init();
            
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => false, // Prevent redirect attacks
                CURLOPT_MAXREDIRS => 0,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_USERAGENT => 'LGU1-ReservationSystem/1.0',
                CURLOPT_HTTPHEADER => [
                    'Accept: application/json',
                    'Content-Type: application/json'
                ]
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            
            curl_close($ch);

            if ($response === false || !empty($error)) {
                Log::error('LGU Auth: cURL error', [
                    'error' => $error,
                    'url' => $url
                ]);
                return false;
            }

            if ($httpCode !== 200) {
                Log::error('LGU Auth: HTTP error', [
                    'http_code' => $httpCode,
                    'url' => $url
                ]);
                return false;
            }

            return $response;
        } catch (\Exception $e) {
            Log::error('LGU Auth: Exception in fetchWithCurl', [
                'error' => $e->getMessage(),
                'url' => $url
            ]);
            return false;
        }
    }
}
