<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitizenDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is handled in routes/web.php instead
    }

    /**
     * Show citizen dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's reservations (if booking system exists)
        // $reservations = $user->reservations()->latest()->take(5)->get();
        
        // Get available facilities
        $availableFacilities = Facility::where('status', 'active')->count();
        
        return view('citizen.dashboard', compact('user', 'availableFacilities'));
    }

    /**
     * Show facility reservation page
     */
    public function reservations()
    {
        $user = Auth::user();
        
        // Get all active facilities
        $facilities = Facility::where('status', 'active')->get();
        
        return view('citizen.reservations', compact('user', 'facilities'));
    }

    /**
     * Show user's reservation history
     */
    public function reservationHistory()
    {
        $user = Auth::user();
        
        // Get user's reservations (when booking system is implemented)
        // $reservations = $user->reservations()->orderBy('created_at', 'desc')->paginate(10);
        $reservations = collect(); // Empty collection for now
        
        return view('citizen.reservation-history', compact('user', 'reservations'));
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('citizen.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date|before:today',
        ]);

        $user->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }
}