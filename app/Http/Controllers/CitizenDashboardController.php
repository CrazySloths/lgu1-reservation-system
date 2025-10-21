<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\PaymentSlip;
use Illuminate\Support\Facades\Log;
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
           
        $user = Auth::user();
        if(!$user)
        {
            return redirect('/login')->with('error', 'Please log in to access the dashboard.');
        }
        
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
            $recentReservations = $paymentSlips;
        }
        catch(Exception $e)
        {
            \Log::error('Database Error loading dashboard stats:', ['error' => $e->getMessage()]);
            $availableFacilities = 0;
            $totalReservations = 0;
            $pendingReservations = 0;
            $paymentSlips = collect();
            $unpaidPaymentSlips = 0;
            $recentReservations = collect();

        }
        

        // Add properties for Blade template compatibility
        $user->full_name = $user->name ?? $user->first_name . ' ' . $user->last_name;
        $user->avatar_initials = strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? 'U', 0, 1));
        
        return view('citizen.dashboard', compact('user', 'availableFacilities', 'totalReservations', 'recentReservations', 'paymentSlips', 'unpaidPaymentSlips', 'pendingReservations'));
    }

    /**
     * Show facility reservation page
     */
    public function reservations()
    {
        $user = Auth::user();
        
        if(!$user)
        {
            return redirect('/login')->with('error', 'Please log in to view reservations.');

            try
            {
                $facilities = Facility::where('status', 'active')->get();
                \Log::info('Citizen: Loaded facilities from database.');
            }
            catch(Exception $e)
            {
                \Log::error('Database Error loading facilities:', ['error' => $e->getMessage()]);
                $facilities = collect([]);
            }
        }

        // Add properties for Blade template compatibility
        $user->full_name = $user->name;
        $user->avatar_initials = 'CL';
        
        // --- LOAD FACILITIES FROM SESSION (Same as Admin) ---
        // Load facilities from persistent file storage (SURVIVES SLEEP/RESTART!)
        $facilitiesFile = storage_path('app/facilities_data.json');
        
        if (file_exists($facilitiesFile)) {
            $data = json_decode(file_get_contents($facilitiesFile), true);
            if ($data && is_array($data)) {
                \Log::info('🎯 CITIZEN: Loaded facilities from persistent file:', ['count' => count($data)]);
                $facilities = collect($data)->map(function($facility) {
                    return (object) $facility;
                });
            } else {
                $facilities = null;
            }
        } else {
            $facilities = null;
        }
        
        if (!$facilities) {
            // Fallback to default facilities
            $facilities = collect([
                (object)[
                    'id' => 1,
                    'facility_id' => 1,
                    'name' => 'Community Hall',
                    'description' => 'Large hall suitable for community events, meetings, and celebrations',
                    'capacity' => 200,
                    'hourly_rate' => 500.00,
                    'daily_rate' => 1500.00,
                    'facility_type' => 'hall',
                    'location' => 'Main Building, Ground Floor',
                    'image_path' => null,
                    'status' => 'active',
                    'amenities' => 'Sound system, air conditioning, tables and chairs',
                    'created_at' => now()->subDays(100),
                    'updated_at' => now()->subDays(10)
                ],
                (object)[
                    'id' => 2,
                    'facility_id' => 2,
                    'name' => 'Basketball Court',
                    'description' => 'Standard basketball court for sports and recreational activities',
                    'capacity' => 50,
                    'hourly_rate' => 200.00,
                    'daily_rate' => 600.00,
                    'facility_type' => 'sports',
                    'location' => 'Recreation Area, Outdoor',
                    'image_path' => null,
                    'status' => 'active',
                    'amenities' => 'Basketball hoops, benches, lighting',
                    'created_at' => now()->subDays(90),
                    'updated_at' => now()->subDays(5)
                ],
                (object)[
                    'id' => 3,
                    'facility_id' => 3,
                    'name' => 'Conference Room',
                    'description' => 'Professional meeting room for business conferences and workshops',
                    'capacity' => 30,
                    'hourly_rate' => 300.00,
                    'daily_rate' => 900.00,
                    'facility_type' => 'meeting',
                    'location' => 'Admin Building, 2nd Floor',
                    'image_path' => null,
                    'status' => 'active',
                    'amenities' => 'Projector, whiteboard, air conditioning, WiFi',
                    'created_at' => now()->subDays(80),
                    'updated_at' => now()->subDays(2)
                ]
            ]);
        }
        
        
        return view('citizen.reservations', compact('user', 'facilities'));
    }

    /**
     * Show user's reservation history
     */
    public function reservationHistory()
    {
        $user = Auth::user();
        if(!$user)
        {
            return redirect('/login')->with('error', 'Please log in to view reservation history.');
        }
        try
        {
            $reservations = \App\Models\Booking::where('user_id', $user->id)
            ->with(['facility', 'paymentSlip'])
            ->orderBy('event_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();
            \Log::info('🎯 RESERVATION HISTORY: Loaded from database', ['user_id' => $user->id, 'count' => $reservations->count()]);
        }
        catch(Exception $e)
        {
            \Log::error('Database Error loading reservation history:', ['error' => $e->getMessage()]);
            $reservations = collect();
            return view('citizen.reservation-history', compact('user', 'reservations'))->with('warning', 'Could not load reservation history due to a database error.');
        }
        $user->full_name = $user->name ?? $user->first_name . ' ' . $user->last_name;
        $user->avatar_initials = strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? 'U', 0, 1));
        
        // --- LOAD BOOKINGS FROM PERSISTENT FILE STORAGE ---
        $bookingsFile = storage_path('app/bookings_data.json');
        $reservations = collect();
        
        if (file_exists($bookingsFile)) {
            $data = json_decode(file_get_contents($bookingsFile), true);
            if ($data && is_array($data)) {
                // Filter bookings for current user
                $userBookings = array_filter($data, function($booking) use ($user) {
                    return $booking['user_id'] == $user->id;
                });
                
                // Convert to collection with proper structure for Blade template
                $reservations = collect(array_values($userBookings))->map(function($booking) {
                    // --- RECALCULATE FEE FROM TIME DURATION (FIX OLD BOOKINGS) ---
                    $calculatedFee = 0;
                    if (!empty($booking['start_time']) && !empty($booking['end_time'])) {
                        try {
                            // Parse times (handles both 12-hour and 24-hour formats)
                            $start = \Carbon\Carbon::parse($booking['start_time']);
                            $end = \Carbon\Carbon::parse($booking['end_time']);
                            // FIX: Use absolute value to ensure positive duration
                            $durationHours = abs($start->diffInHours($end));
                            
                            // Get facility pricing (default: Pacquiao Court rates)
                            $dailyRate = 5000;  // ₱5,000 base (3 hours)
                            $hourlyRate = 2000; // ₱2,000 per hour extension
                            
                            // Calculate fee
                            $calculatedFee = $dailyRate; // Base 3 hours
                            if ($durationHours > 3) {
                                $calculatedFee += ($durationHours - 3) * $hourlyRate;
                            }
                        } catch (\Exception $e) {
                            // If calculation fails, use stored fee
                            $calculatedFee = $booking['total_fee'] ?? 0;
                        }
                    } else {
                        $calculatedFee = $booking['total_fee'] ?? 0;
                    }
                    
                    return (object)[
                        'id' => $booking['id'],
                        'user_id' => $booking['user_id'],
                        'facility_id' => $booking['facility_id'],
                        'event_name' => $booking['event_name'],
                        'event_description' => $booking['event_description'] ?? '',
                        'applicant_name' => $booking['applicant_name'],
                        'event_date' => $booking['event_date'],
                        'start_time' => $booking['start_time'],
                        'end_time' => $booking['end_time'],
                        'expected_attendees' => $booking['expected_attendees'],
                        'total_fee' => $calculatedFee,  // ✅ RECALCULATED FEE (fixes old bookings)
                        'status' => $booking['status'],
                        'created_at' => $booking['created_at'],
                        'facility' => (object)[
                            'name' => $booking['facility_name'] ?? 'Unknown Facility',
                            'hourly_rate' => 500.00
                        ],
                        'paymentSlip' => (object)[
                            'id' => $booking['id'],
                            'amount' => $calculatedFee,  // ✅ Use recalculated fee
                            'status' => $booking['status'] === 'approved' ? 'pending' : 'unpaid',
                            'due_date' => now()->addDays(5)
                        ]
                    ];
                });
                
                \Log::info('🎯 RESERVATION HISTORY: Loaded from persistent file', ['user_id' => $user->id, 'count' => $reservations->count()]);
            }
        } else {
            \Log::warning('🎯 RESERVATION HISTORY: No bookings file found - showing empty list');
        }
        
        return view('citizen.reservation-history', compact('user', 'reservations'));
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        if(!$user)
        {
            return redirect('/login')->with('error', 'Please log in to view your profile.');
        }
        $user->full_name = $user->name ?? $user->first_name . ' ' . $user->last_name;
        $user->avatar_initials = strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? 'U', 0, 1));
      
        
        return view('citizen.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        // validation corresponding to fields in the profile form
        $request->validate([
        'name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'address' => 'required|string|max:500',
        'date_of_birth' => 'required|date|before:today',
    ]);
    $user = Auth::user();
        if(!$user)
        {
            return back()->with('error', 'Authentication required. Please log in again.');
        }
        try
        {
            $user->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
            ]);
            \Log::info('Profile updated successfully (REAL UPDATE):', [
                'user_id' => $user->id,
                'updated_by_user' => $user->email,
                'changes' => $request->only(['name', 'phone_number', 'address', 'date_of_birth'])
            ]);
            return back()->with('success', 'Profile updated successfully!');
        }
        catch(Exception $e)
        {
            \Log::error('Database Error during profile update:', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to update profile due to a database error. Please try again.');
        }
    }

    /**
     * Show facility availability calendar
     */
    public function viewAvailability()
    {
        
        $user = Auth::user();

        if(!$user)
        {
            return redirect('/login')->with('error', 'Please log in to view facility availability.');
        }
        $user->full_name = $user->name ?? $user->first_name . ' ' . $user->last_name;
        $user->avatar_initials = strtoupper(substr($user->first_name ?? 'U', 0, 1) . substr($user->last_name ?? 'U', 0, 1));
        try
        {
            $facilities = \App\Models\Facility::where('status', 'active')->get();
            \Log::info('🎯 CITIZEN AVAILABILITY: Loaded facilities from database.', ['count' => $facilities->count()]);
        }
        catch(Exception $e)
        {
            \Log::error('Database Error loading facilities for availability:', ['error' => $e->getMessage()]);
            $facilities = collect([]);
            return view('citizen.availability', compact('user', 'facilities'))->with('warning', 'Could not load facilities from the database.');
        }
        return view('citizen.availability', compact('user', 'facilities'));
        
        // --- LOAD FACILITIES FROM PERSISTENT FILE STORAGE ---
        $facilitiesFile = storage_path('app/facilities_data.json');
        
        if (file_exists($facilitiesFile)) {
            $data = json_decode(file_get_contents($facilitiesFile), true);
            if ($data && is_array($data)) {
                \Log::info('🎯 CITIZEN AVAILABILITY: Loaded facilities from persistent file:', ['count' => count($data)]);
                $facilities = collect($data)->map(function($facility) {
                    return (object) $facility;
                });
            } else {
                $facilities = $this->getDefaultFacilities();
            }
        } else {
            $facilities = $this->getDefaultFacilities();
        }
        
        \Log::warning('CitizenDashboardController::viewAvailability using PERSISTENT FILE STORAGE due to database issues.');
        
        return view('citizen.availability', compact('user', 'facilities'));
    }
    
    private function getDefaultFacilities()
    {
        return collect([
            (object)[
                'id' => 1,
                'facility_id' => 1,
                'name' => 'Community Hall',
                'description' => 'Large hall suitable for community events, meetings, and celebrations',
                'capacity' => 200,
                'hourly_rate' => 500.00,
                'daily_rate' => 1500.00,
                'facility_type' => 'hall',
                'location' => 'Main Building, Ground Floor',
                'image_path' => null,
                'status' => 'active',
                'amenities' => 'Sound system, air conditioning, tables and chairs',
                'created_at' => now()->subDays(100),
                'updated_at' => now()->subDays(10)
            ],
            (object)[
                'id' => 2,
                'facility_id' => 2,
                'name' => 'Basketball Court',
                'description' => 'Standard basketball court for sports and recreational activities',
                'capacity' => 50,
                'hourly_rate' => 200.00,
                'daily_rate' => 600.00,
                'facility_type' => 'sports',
                'location' => 'Recreation Area, Outdoor',
                'image_path' => null,
                'status' => 'active',
                'amenities' => 'Basketball hoops, benches, lighting',
                'created_at' => now()->subDays(90),
                'updated_at' => now()->subDays(5)
            ],
            (object)[
                'id' => 3,
                'facility_id' => 3,
                'name' => 'Conference Room',
                'description' => 'Professional meeting room for business conferences and workshops',
                'capacity' => 30,
                'hourly_rate' => 300.00,
                'daily_rate' => 900.00,
                'facility_type' => 'meeting',
                'location' => 'Admin Building, 2nd Floor',
                'image_path' => null,
                'status' => 'active',
                'amenities' => 'Projector, whiteboard, air conditioning, WiFi',
                'created_at' => now()->subDays(80),
                'updated_at' => now()->subDays(2)
            ]
        ]);
    }

    /**
     * API endpoint to get bookings for a specific facility
     */
    public function getFacilityBookings($facilityId)
    {
        try {
            // --- LOAD APPROVED BOOKINGS FROM DATABASE ---
            $bookings = collect();
            
            try {
                // Try to get ONLY approved bookings for this facility from database first
                $bookings = Booking::with('facility')
                                ->where('facility_id', $facilityId)
                                ->where('status', 'approved') // Citizens only see approved bookings
                                ->get();
                                
                \Log::info('🎯 CITIZEN API: Loaded bookings from database:', [
                    'facility_id' => $facilityId,
                    'total_bookings' => $bookings->count()
                ]);
            } catch (\Exception $e) {
                \Log::warning('🎯 CITIZEN API: Database query failed, trying file storage', ['error' => $e->getMessage()]);
                
                // Fallback to file storage for bookings
                $bookingsFile = storage_path('app/bookings_data.json');
                if (file_exists($bookingsFile)) {
                    $allBookings = json_decode(file_get_contents($bookingsFile), true);
                    if ($allBookings && is_array($allBookings)) {
                        // Filter bookings for this facility - ONLY approved bookings
                        $fileBookings = [];
                        foreach ($allBookings as $booking) {
                            if (isset($booking['facility_id']) && $booking['facility_id'] == $facilityId 
                                && isset($booking['status']) && $booking['status'] === 'approved') {
                                $fileBookings[] = (object) $booking;
                            }
                        }
                        $bookings = collect($fileBookings);
                        
                        \Log::info('🎯 CITIZEN API: Loaded bookings from persistent file:', [
                            'facility_id' => $facilityId,
                            'total_bookings' => count($allBookings),
                            'facility_bookings' => count($fileBookings)
                        ]);
                    }
                } else {
                    \Log::warning('🎯 CITIZEN API: bookings_data.json not found, using empty array');
                }
            }
            
            $bookings = collect($bookings);

            $events = [];

            foreach ($bookings as $booking) {
                // Set different colors for different statuses
                $backgroundColor = $booking->status === 'approved' ? '#ef4444' : '#f59e0b'; // Red for approved, Yellow for pending
                $borderColor = $booking->status === 'approved' ? '#dc2626' : '#d97706';
                
                // Format events for FullCalendar
                $startTime = $booking->start_time;
                $endTime = $booking->end_time;
                
                // Ensure time has seconds (HH:MM:SS format)
                if (substr_count($startTime, ':') === 1) {
                    $startTime .= ':00';
                }
                if (substr_count($endTime, ':') === 1) {
                    $endTime .= ':00';
                }
                
                $events[] = [
                    'id' => $booking->id,
                    'title' => $booking->event_name . ' - ' . $booking->applicant_name,
                    'start' => $booking->event_date . 'T' . $startTime,
                    'end' => $booking->event_date . 'T' . $endTime,
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

            \Log::info('🎯 CITIZEN API: Facility Bookings Response:', [
                'facility_id' => $facilityId,
                'events_count' => count($events),
                'events' => $events
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
    
    /**
     * API endpoint to get bookings for ALL facilities
     */
    public function getAllFacilityBookings()
    {
        try {
            // --- LOAD ALL APPROVED BOOKINGS FROM DATABASE ---
            $bookings = collect();
            
            try {
                // Try to get ONLY approved bookings from database first
                $bookings = Booking::with('facility')
                                ->where('status', 'approved') // Citizens only see approved bookings
                                ->get();
                                
                \Log::info('🎯 CITIZEN API: Loaded ALL bookings from database:', [
                    'total_bookings' => $bookings->count()
                ]);
            } catch (\Exception $e) {
                \Log::warning('🎯 CITIZEN API: Database query failed, trying file storage', ['error' => $e->getMessage()]);
                
                // Fallback to file storage for bookings
                $bookingsFile = storage_path('app/bookings_data.json');
                if (file_exists($bookingsFile)) {
                    $allBookings = json_decode(file_get_contents($bookingsFile), true);
                    if ($allBookings && is_array($allBookings)) {
                        // Convert to objects and filter ONLY approved bookings
                        $fileBookings = [];
                        foreach ($allBookings as $booking) {
                            // Only show approved bookings to citizens
                            if (isset($booking['status']) && $booking['status'] === 'approved') {
                                $fileBookings[] = (object) $booking;
                            }
                        }
                        $bookings = collect($fileBookings);
                        
                        \Log::info('🎯 CITIZEN API: Loaded ALL bookings from persistent file:', [
                            'total_bookings' => count($fileBookings)
                        ]);
                    }
                } else {
                    \Log::warning('🎯 CITIZEN API: bookings_data.json not found');
                }
            }
            
            $events = [];

            foreach ($bookings as $booking) {
                // Handle both Eloquent models and arrays (for fallback file data)
                $bookingArray = is_array($booking) ? $booking : $booking->toArray();
                $status = $bookingArray['status'] ?? 'pending';
                
                // Set different colors for different statuses
                $backgroundColor = $status === 'approved' ? '#ef4444' : '#f59e0b'; // Red for approved, Yellow for pending
                $borderColor = $status === 'approved' ? '#dc2626' : '#d97706';
                
                // Format events for FullCalendar
                $startTime = $bookingArray['start_time'] ?? '';
                $endTime = $bookingArray['end_time'] ?? '';
                
                // Ensure time has seconds (HH:MM:SS format)
                if (substr_count($startTime, ':') === 1) {
                    $startTime .= ':00';
                }
                if (substr_count($endTime, ':') === 1) {
                    $endTime .= ':00';
                }
                
                $events[] = [
                    'id' => $bookingArray['id'] ?? 0,
                    'title' => ($bookingArray['event_name'] ?? '') . ' - ' . ($bookingArray['applicant_name'] ?? ''),
                    'start' => ($bookingArray['event_date'] ?? '') . 'T' . $startTime,
                    'end' => ($bookingArray['event_date'] ?? '') . 'T' . $endTime,
                    'backgroundColor' => $backgroundColor,
                    'borderColor' => $borderColor,
                    'textColor' => '#ffffff',
                    'extendedProps' => [
                        'facility_id' => $bookingArray['facility_id'] ?? null,
                        'applicant' => $bookingArray['applicant_name'] ?? '',
                        'attendees' => $bookingArray['expected_attendees'] ?? 0,
                        'status' => $status,
                        'description' => $bookingArray['event_description'] ?? ''
                    ]
                ];
            }

            \Log::info('🎯 CITIZEN API: ALL Facility Bookings Response:', [
                'events_count' => count($events)
            ]);

            return response()->json($events);
        } catch (\Exception $e) {
            \Log::error('Error fetching all facility bookings:', [
                'error' => $e->getMessage()
            ]);

            return response()->json([], 500);
        }
    }
}