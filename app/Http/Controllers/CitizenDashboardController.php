<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\PaymentSlip;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception; // Siguraduhin na ito ay nandoon para sa try/catch

class CitizenDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is handled in routes/web.php instead (SSOAuthMiddleware at auth:web)
    }

    // *********************************************************************************
    // ❌ TINANGGAL: Ang buong getAuthenticatedUser() function.
    // Ito ang nagiging sanhi ng conflict sa SsoAuthMiddleware.
    // *********************************************************************************
    
    /**
     * Show citizen dashboard
     */
    public function index(Request $request)
    {
        // ✅ User is guaranteed to be available by 'auth:web' middleware in web.php
        $user = Auth::user(); 

        // ❌ TINANGGAL: Manual check if(!$user)
        
        try
        {
            // available facilities (global static)
            $availableFacilities = Facility::where('status', 'active')->count();
            // total reservations (user-specific)
            $totalReservations = Booking::where('user_id', $user->id)->count();
            // pending reservations (user-specific)
            $pendingReservations = Booking::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'for_approval'])
            ->count();
            // recent payment slips (user-specific)
            $paymentSlips = PaymentSlip::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            // unpaid payment slips (user-specific)
            $unpaidPaymentSlips = PaymentSlip::where('user_id', $user->id)
            ->where('status', 'unpaid')
            ->count();

            // Log successful access
            Log::info('CITIZEN DASHBOARD: User accessed successfully.', ['user_id' => $user->id, 'email' => $user->email]);
            
            return view('citizen.dashboard', [
                'user' => $user,
                'availableFacilities' => $availableFacilities,
                'totalReservations' => $totalReservations,
                'pendingReservations' => $pendingReservations,
                'paymentSlips' => $paymentSlips,
                'unpaidPaymentSlips' => $unpaidPaymentSlips
            ]);
        }
        catch (\Exception $e)
        {
            Log::error('CITIZEN DASHBOARD: Error fetching data.', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Redirect to dashboard with error message if data loading fails
            return redirect()->route('citizen.dashboard')->with('error', 'There was an error loading the dashboard data.');
        }
    }

    /**
     * Show facility reservations list
     */
    public function reservations(Request $request)
    {
        $user = Auth::user(); // ✅ FIX: Ginamit ang Auth::user()

        $reservations = Booking::where('user_id', $user->id)
                               ->with('facility')
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('citizen.reservations', compact('reservations'));
    }

    /**
     * Show reservation history
     */
    public function reservationHistory(Request $request)
    {
        $user = Auth::user(); // ✅ FIX: Ginamit ang Auth::user()
        
        try
        {
            $history = Booking::where('user_id', $user->id)
                               ->whereIn('status', ['completed', 'rejected', 'cancelled'])
                               ->with('facility', 'paymentSlip') // Inayos para kasama ang paymentSlip
                               ->orderBy('event_date', 'desc')
                               ->get();
                           
            return view('citizen.reservation-history', compact('history'));
        }
        catch (\Exception $e)
        {
            Log::error('RESERVATION HISTORY: Database error.', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return redirect()->route('citizen.dashboard')->with('error', 'There was an error loading your reservation history.');
        }
    }

    /**
     * Show profile page
     */
    public function profile(Request $request)
    {
        $user = Auth::user(); // ✅ FIX: Ginamit ang Auth::user()
        return view('citizen.profile', compact('user'));
    }

    /**
     * Update profile information
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // ✅ FIX: Ginamit ang Auth::user()
        
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'street_address' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'id_type' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255|unique:users,id_number,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Update the name field for backward compatibility
        $validatedData['name'] = implode(' ', array_filter([
            $validatedData['first_name'], 
            $validatedData['middle_name'], 
            $validatedData['last_name']
        ]));

        if (isset($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
        
        $user->update($validatedData);

        return redirect()->route('citizen.profile')->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Show facility availability calendar
     */
    public function viewAvailability()
    {
        $facilities = Facility::where('status', 'active')->get();
        return view('citizen.availability', compact('facilities'));
    }

    /**
     * Get bookings for a specific facility (for calendar view)
     */
    public function getFacilityBookings(Request $request, $facility_id)
    {
        try {
            // Fetch bookings for the specific facility that are approved or pending review
            $bookings = Booking::where('facility_id', $facility_id)
                ->whereIn('status', ['approved', 'for_approval', 'pending']) // Include pending for visibility to prevent double booking
                ->get();
            
            $events = [];
            foreach ($bookings as $booking) {
                // Set color based on status
                $backgroundColor = $booking->status === 'approved' ? '#10B981' : '#F59E0B'; // Green for approved, Amber for others
                $borderColor = $backgroundColor;

                // Time format normalization
                $startTime = $booking->start_time;
                $endTime = $booking->end_time;
                
                if (substr_count($startTime, ':') === 1) {
                    $startTime .= ':00';
                }
                if (substr_count($endTime, ':') === 1) {
                    $endTime .= ':00';
                }
                
                // Fix date formatting - extract just the date part to avoid double "T"
                $eventDate = date('Y-m-d', strtotime($booking->event_date));
                
                $events[] = [
                    'id' => $booking->id,
                    'title' => $booking->event_name . ' - ' . $booking->applicant_name,
                    'start' => $eventDate . 'T' . $startTime,
                    'end' => $eventDate . 'T' . $endTime,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'facility_id' => $booking->facility_id,
                        'applicant' => $booking->applicant_name,
                        'attendees' => $booking->expected_attendees,
                        'status' => $booking->status,
                        'description' => $booking->event_description
                    ]
                ];
            }

            Log::info('CITIZEN API: Facility Bookings Response:', [
                'facility_id' => $facility_id,
                'events_count' => count($events)
            ]);

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Error fetching facility bookings:', [
                'facility_id' => $facility_id,
                'error' => $e->getMessage()
            ]);

            return response()->json([], 500);
        }
    }
    
    /**
     * Get all bookings (for administrator/staff calendar view)
     */
    public function getAllFacilityBookings(Request $request)
    {
        try {
            // Fetch all bookings that are approved or pending review (accessible by citizen if needed for global view)
            $bookings = Booking::whereIn('status', ['approved', 'for_approval', 'pending'])
                ->with('facility')
                ->get();
            
            $events = [];
            foreach ($bookings as $booking) {
                // Set color based on status
                $backgroundColor = $booking->status === 'approved' ? '#10B981' : '#F59E0B'; 
                $borderColor = $backgroundColor;

                // Time format normalization
                $startTime = $booking->start_time;
                $endTime = $booking->end_time;
                
                if (substr_count($startTime, ':') === 1) {
                    $startTime .= ':00';
                }
                if (substr_count($endTime, ':') === 1) {
                    $endTime .= ':00';
                }
                
                // Fix date formatting - extract just the date part to avoid double "T"
                $eventDate = date('Y-m-d', strtotime($booking->event_date));
                
                $events[] = [
                    'id' => $booking->id,
                    'title' => $booking->event_name . ' - ' . $booking->applicant_name,
                    'start' => $eventDate . 'T' . $startTime,
                    'end' => $eventDate . 'T' . $endTime,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'facility_id' => $booking->facility_id,
                        'applicant' => $booking->applicant_name,
                        'attendees' => $booking->expected_attendees,
                        'status' => $booking->status,
                        'description' => $booking->event_description
                    ]
                ];
            }

            Log::info('CITIZEN API: ALL Facility Bookings Response:', [
                'events_count' => count($events)
            ]);

            return response()->json($events);
        } catch (\Exception $e) {
            Log::error('Error fetching all facility bookings:', [
                'error' => $e->getMessage()
            ]);

            return response()->json([], 500);
        }
    }
}