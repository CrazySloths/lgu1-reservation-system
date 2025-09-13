<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use App\Models\Booking;
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
        $user = Auth::user() ?? User::where('role', 'citizen')->first();

        // If no user is found, redirect to a safe route or show an error.
        if (!$user) {
            // Optionally, you can create a default user if none exists for testing
            // For now, we'll just abort.
            abort(404, 'No citizen user found to display the dashboard.');
        }
        
        // Get user's recent reservations
        $recentReservations = $user->reservations()->latest()->take(5)->get();
        $totalReservations = $user->reservations()->count();
        
        // Get user's payment slips
        $paymentSlips = $user->paymentSlips()->with('booking')->latest()->take(3)->get();
        $unpaidPaymentSlips = $user->paymentSlips()->where('status', 'unpaid')->count();
        
        // Get available facilities
        $availableFacilities = Facility::where('status', 'active')->count();
        
        return view('citizen.dashboard', compact('user', 'availableFacilities', 'totalReservations', 'recentReservations', 'paymentSlips', 'unpaidPaymentSlips'));
    }

    /**
     * Show facility reservation page
     */
    public function reservations()
    {
        $user = Auth::user() ?? User::where('role', 'citizen')->first();
        
        // Get all active facilities
        $facilities = Facility::where('status', 'active')->get();
        
        return view('citizen.reservations', compact('user', 'facilities'));
    }

    /**
     * Show user's reservation history
     */
    public function reservationHistory()
    {
        $user = Auth::user() ?? User::where('role', 'citizen')->first();
        
        // Debug: Log current user information
        \Log::info('Reservation History - Current User:', [
            'user_id' => $user ? $user->id : 'Not authenticated',
            'email' => $user ? $user->email : 'N/A'
        ]);
        
        if (!$user) {
            abort(404, 'No citizen user found.');
        }
        
        // Get user's reservations with facility and payment slip information
        $reservations = $user->reservations()
                            ->with(['facility', 'paymentSlip'])
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        // Debug: Log reservations count
        \Log::info('Reservation History - Found reservations:', [
            'count' => $reservations->count(),
            'user_id' => $user->id
        ]);
        
        return view('citizen.reservation-history', compact('user', 'reservations'));
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user() ?? User::where('role', 'citizen')->first();
        
        return view('citizen.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user() ?? User::where('role', 'citizen')->first();
        
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

    /**
     * Show facility availability calendar
     */
    public function viewAvailability()
    {
        $user = Auth::user() ?? User::where('role', 'citizen')->first();
        
        // Get all active facilities
        $facilities = Facility::where('status', 'active')->get();
        
        return view('citizen.availability', compact('user', 'facilities'));
    }

    /**
     * API endpoint to get bookings for a specific facility
     */
    public function getFacilityBookings($facilityId)
    {
        try {
            // Get all bookings for the facility (not just approved ones for better visibility)
            $bookings = Booking::where('facility_id', $facilityId)
                             ->whereIn('status', ['approved', 'pending']) // Show both approved and pending
                             ->with('facility')
                             ->get();

            // Debug: Log booking query
            \Log::info('Facility Bookings Query:', [
                'facility_id' => $facilityId,
                'found_bookings' => $bookings->count(),
                'total_bookings_in_db' => Booking::count()
            ]);

            $events = [];

            foreach ($bookings as $booking) {
                // Set different colors for different statuses
                $backgroundColor = $booking->status === 'approved' ? '#ef4444' : '#f59e0b'; // Red for approved, Yellow for pending
                $borderColor = $booking->status === 'approved' ? '#dc2626' : '#d97706';
                
                // Format events for FullCalendar
                $events[] = [
                    'id' => $booking->id,
                    'title' => $booking->event_name . ' - ' . $booking->applicant_name,
                    'start' => $booking->event_date . 'T' . $booking->start_time,
                    'end' => $booking->event_date . 'T' . $booking->end_time,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'applicant' => $booking->applicant_name,
                        'attendees' => $booking->expected_attendees,
                        'status' => $booking->status,
                        'description' => $booking->event_description
                    ]
                ];
            }

            \Log::info('Facility Bookings Response:', [
                'facility_id' => $facilityId,
                'events_count' => count($events)
            ]);

            return response()->json($events);
        } catch (\Exception $e) {
            \Log::error('Error fetching facility bookings:', [
                'facility_id' => $facilityId,
                'error' => $e->getMessage()
            ]);

            return response()->json([], 500);
        }
    }
}