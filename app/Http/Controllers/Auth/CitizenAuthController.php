<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CitizenAuthController extends Controller
{
    /**
     * Show citizen registration form
     */
    public function showRegistrationForm()
    {
        return view('citizen.auth.register');
    }

    /**
     * Handle citizen registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone_number' => 'required|string|max:20',
            'region' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'barangay' => 'required|string|max:100',
            'street_address' => 'required|string|max:255',
            'address' => 'required|string|max:500', // This will be auto-generated from the components
            'date_of_birth' => 'required|date|before:today',
            'id_type' => 'required|in:Government-Issued ID,School ID,Driver\'s License,Passport,Senior Citizen ID,PWD ID,Voter\'s ID',
            'id_number' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'citizen',
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'id_type' => $request->id_type,
            'id_number' => $request->id_number,
            'is_verified' => false,
        ]);

        Auth::login($user);

        return redirect()->route('citizen.dashboard')->with('success', 'Registration successful! Welcome to the Citizen Portal.');
    }

    /**
     * Show citizen login form
     */
    public function showLoginForm()
    {
        return view('citizen.auth.login');
    }

    /**
     * Handle citizen login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if user is a citizen
            if (Auth::user()->isCitizen()) {
                return redirect()->intended('citizen/dashboard');
            }

            // If not a citizen, redirect to admin portal
            Auth::logout();
            return back()->withErrors([
                'email' => 'Access denied. Use the admin portal for administrative access.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle citizen logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('citizen.login');
    }
}