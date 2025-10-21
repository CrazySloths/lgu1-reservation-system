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
        // --- STATIC USER DATA (Database drivers not available on server) ---
        $user = (object)[
            'id' => 4,
            'external_id' => 60,
            'name' => 'Cristian mark Angelo Pastoril Llaneta',
            'email' => '1hawkeye101010101@gmail.com',
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
        
        \Log::warning('CitizenDashboardController::reservations using STATIC DATA due to database issues.');
        
        return view('citizen.reservations', compact('user', 'facilities'));
    }

    /**
     * Show user's reservation history
     */
    public function reservationHistory()
    {
        // --- STATIC USER DATA ---
        $user = (object)[
            'id' => 4,
            'external_id' => 60,
            'name' => 'Cristian mark Angelo Pastoril Llaneta',
            'email' => '1hawkeye101010101@gmail.com',
            'role' => 'citizen',
            'status' => 'active',
            'full_name' => 'Cristian mark Angelo Pastoril Llaneta',
            'avatar_initials' => 'CL'
        ];
        
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
        // --- STATIC USER DATA ---
        $user = (object)[
            'id' => 4,
            'external_id' => 60,
            'name' => 'Cristian mark Angelo Pastoril Llaneta',
            'email' => '1hawkeye101010101@gmail.com',
            'role' => 'citizen',
            'status' => 'active',
            'first_name' => 'Cristian',
            'middle_name' => 'mark Angelo Pastoril',
            'last_name' => 'Llaneta',
            'phone_number' => '+63 912 345 6789',
            'address' => '123 Main Street, Barangay Sample, City',
            'date_of_birth' => '1995-06-15',
            'email_verified_at' => now(),
            'created_at' => now()->subDays(30),
            'updated_at' => now(),
            'full_name' => 'Cristian mark Angelo Pastoril Llaneta',
            'avatar_initials' => 'CL'
        ];
        
        \Log::warning('CitizenDashboardController::profile using STATIC DATA due to database issues.');
        
        return view('citizen.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        // --- STATIC USER DATA (No database updates possible) ---
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'date_of_birth' => 'required|date|before:today',
        ]);

        // Log the attempted update for debugging
        \Log::info('Profile update attempted (STATIC MODE - no database available):', [
            'user_id' => 4,
            'attempted_changes' => [
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            ]
        ]);

        // Return success message (simulating successful update)
        return back()->with('success', 'Profile updated successfully! (Note: Changes are simulated due to database limitations)');
    }

    /**
     * Show facility availability calendar
     */
    public function viewAvailability()
    {
        // --- STATIC USER DATA ---
        $user = (object)[
            'id' => 4,
            'external_id' => 60,
            'name' => 'Cristian mark Angelo Pastoril Llaneta',
            'email' => '1hawkeye101010101@gmail.com',
            'role' => 'citizen',
            'status' => 'active',
            'full_name' => 'Cristian mark Angelo Pastoril Llaneta',
            'avatar_initials' => 'CL'
        ];
        
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
            // --- LOAD BOOKINGS FROM PERSISTENT FILE STORAGE ---
            $bookingsFile = storage_path('app/bookings_data.json');
            $bookings = [];
            
            if (file_exists($bookingsFile)) {
                $allBookings = json_decode(file_get_contents($bookingsFile), true);
                if ($allBookings && is_array($allBookings)) {
                    // Filter bookings for this facility  
                    foreach ($allBookings as $booking) {
                        if (isset($booking['facility_id']) && $booking['facility_id'] == $facilityId) {
                            $bookings[] = (object) $booking;
                        }
                    }
                    
                    \Log::info('🎯 CITIZEN API: Loaded bookings from persistent file:', [
                        'facility_id' => $facilityId,
                        'total_bookings' => count($allBookings),
                        'facility_bookings' => count($bookings)
                    ]);
                }
            } else {
                \Log::warning('🎯 CITIZEN API: bookings_data.json not found, using empty array');
            }
            
            $bookings = collect($bookings);

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

            \Log::info('Facility Bookings Response (STATIC DATA):', [
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