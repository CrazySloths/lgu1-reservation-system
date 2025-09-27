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
    public function index(Request $request)
    {
        // --- STATIC USER DATA (Database drivers not available on server) ---
        // This section provides hardcoded user data to ensure the dashboard displays correctly
        // when the database is not accessible or drivers are missing.
        
        $user = (object)[
            'id' => 4, // Local database ID (if it existed)
            'external_id' => 60, // External SSO ID
            'name' => 'Cristian mark Angelo Pastoril Llaneta',
            'email' => '1hawkeye101010101@gmail.com', // CORRECT EMAIL
            'role' => 'citizen',
            'status' => 'active',
            'first_name' => 'Cristian',
            'middle_name' => 'mark Angelo Pastoril',
            'last_name' => 'Llaneta',
            'phone_number' => null,
            'address' => null,
            'date_of_birth' => null,
            'email_verified_at' => now(),
            'created_at' => now()->subDays(30),
            'updated_at' => now()
        ];

        // Add properties for Blade template compatibility
        $user->full_name = $user->name; // For sidebar display
        $user->avatar_initials = 'CL'; // Generate initials from "Cristian Llaneta"
        
        // Log this fallback for debugging
        \Log::warning('CitizenDashboardController using STATIC USER DATA due to database issues.', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'url_params' => $request->all()
        ]);
        
        // --- STATIC DASHBOARD DATA ---
        // Since we are bypassing the database, we'll use dummy data for stats
        $recentReservations = collect(); // Empty collection
        $totalReservations = 0;
        $paymentSlips = collect(); // Empty collection  
        $unpaidPaymentSlips = 0;
        $availableFacilities = 5; // Static number
        
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