<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RequirementVerificationController extends Controller
{
    /**
     * Display list of bookings pending staff verification
     */
    public function index(Request $request)
    {
        // --- LOAD BOOKINGS FROM PERSISTENT FILE STORAGE (STATIC DATA) ---
        $bookingsFile = storage_path('app/bookings_data.json');
        $allBookings = [];
        
        if (file_exists($bookingsFile)) {
            $data = json_decode(file_get_contents($bookingsFile), true);
            if ($data && is_array($data)) {
                $allBookings = $data;
            }
        }
        
        // Filter for pending bookings without staff verification
        $filteredBookings = array_filter($allBookings, function($booking) use ($request) {
            // Must be pending status
            if ($booking['status'] !== 'pending') {
                return false;
            }
            
            // Must not have staff verification yet
            if (!empty($booking['staff_verified_by'])) {
                return false;
            }
            
            // Filter by facility if specified
            if ($request->filled('facility') && $booking['facility_id'] != $request->facility) {
                return false;
            }
            
            // Filter by date range if specified
            if ($request->filled('date_from')) {
                $createdDate = date('Y-m-d', strtotime($booking['created_at']));
                if ($createdDate < $request->date_from) {
                    return false;
                }
            }
            if ($request->filled('date_to')) {
                $createdDate = date('Y-m-d', strtotime($booking['created_at']));
                if ($createdDate > $request->date_to) {
                    return false;
                }
            }
            
            return true;
        });
        
        // Convert to collection with proper structure for Blade templates
        $bookings = collect(array_values($filteredBookings))->map(function($booking) {
            return (object)[
                'id' => $booking['id'],
                'user_id' => $booking['user_id'],
                'facility_id' => $booking['facility_id'],
                'event_name' => $booking['event_name'],
                'event_description' => $booking['event_description'] ?? '',
                'event_date' => $booking['event_date'],
                'start_time' => $booking['start_time'],
                'end_time' => $booking['end_time'],
                'expected_attendees' => $booking['expected_attendees'],
                'total_fee' => $booking['total_fee'],
                'status' => $booking['status'],
                'created_at' => \Carbon\Carbon::parse($booking['created_at']), // Convert to Carbon instance
                'priority' => 'normal', // Add priority field
                'user' => (object)[
                    'name' => $booking['applicant_name'],
                    'email' => $booking['applicant_email'] ?? 'N/A',
                ],
                'facility' => (object)[
                    'name' => $booking['facility_name'] ?? 'Unknown Facility',
                    'location' => 'LGU1 Sports Complex'
                ]
            ];
        })->sortByDesc('created_at')->values();

        return view('staff.verification.index', compact('bookings'));
    }

    /**
     * Show detailed booking information for verification
     */
    public function show($id)
    {
        // --- LOAD BOOKING FROM PERSISTENT FILE STORAGE ---
        $bookingsFile = storage_path('app/bookings_data.json');
        $booking = null;
        
        if (file_exists($bookingsFile)) {
            $data = json_decode(file_get_contents($bookingsFile), true);
            if ($data && is_array($data)) {
                foreach ($data as $item) {
                    if ($item['id'] == $id) {
                        $booking = $item;
                        break;
                    }
                }
            }
        }
        
        if (!$booking) {
            return redirect()->route('staff.verification.index')
                ->with('error', 'Booking not found.');
        }
        
        // Ensure booking is pending and not yet verified by staff
        if ($booking['status'] !== 'pending' || !empty($booking['staff_verified_by'])) {
            return redirect()->route('staff.verification.index')
                ->with('error', 'This booking is not available for verification.');
        }
        
        // Convert to object with proper structure
        $booking = (object)[
            'id' => $booking['id'],
            'user_id' => $booking['user_id'],
            'facility_id' => $booking['facility_id'],
            'event_name' => $booking['event_name'],
            'event_description' => $booking['event_description'] ?? '',
            'applicant_name' => $booking['applicant_name'],
            'applicant_email' => $booking['applicant_email'] ?? 'N/A',
            'applicant_phone' => $booking['applicant_phone'] ?? 'N/A',
            'applicant_address' => $booking['applicant_address'] ?? 'N/A',
            'event_date' => \Carbon\Carbon::parse($booking['event_date']), // Convert to Carbon
            'start_time' => $booking['start_time'],
            'end_time' => $booking['end_time'],
            'expected_attendees' => $booking['expected_attendees'],
            'attendees' => $booking['expected_attendees'], // Alias for template
            'total_fee' => $booking['total_fee'],
            'status' => $booking['status'],
            'event_type' => 'general', // Add event type
            'priority' => 'normal', // Add priority
            'created_at' => \Carbon\Carbon::parse($booking['created_at']), // Convert to Carbon
            // Document fields
            'id_type' => $booking['id_type'] ?? null,
            'valid_id_path' => $booking['valid_id_path'] ?? null,
            'id_back_path' => $booking['id_back_path'] ?? null,
            'id_selfie_path' => $booking['id_selfie_path'] ?? null,
            'authorization_letter_path' => $booking['authorization_letter_path'] ?? null,
            'event_proposal_path' => $booking['event_proposal_path'] ?? null,
            'digital_signature' => $booking['digital_signature'] ?? null,
            'user' => (object)[
                'name' => $booking['applicant_name'],
                'email' => $booking['applicant_email'] ?? 'N/A',
                'phone' => $booking['applicant_phone'] ?? 'N/A',
            ],
            'facility' => (object)[
                'name' => $booking['facility_name'] ?? 'Unknown Facility',
                'location' => 'LGU1 Sports Complex',
                'type' => 'Sports Facility'
            ]
        ];

        return view('staff.verification.show', compact('booking'));
    }

    /**
     * Approve booking requirements and send to admin for final approval
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'staff_notes' => 'nullable|string|max:1000'
        ]);

        // --- LOAD BOOKINGS FROM PERSISTENT FILE STORAGE ---
        $bookingsFile = storage_path('app/bookings_data.json');
        $allBookings = [];
        $bookingIndex = -1;
        
        if (file_exists($bookingsFile)) {
            $allBookings = json_decode(file_get_contents($bookingsFile), true);
            if ($allBookings && is_array($allBookings)) {
                foreach ($allBookings as $index => $item) {
                    if ($item['id'] == $id) {
                        $bookingIndex = $index;
                        break;
                    }
                }
            }
        }
        
        if ($bookingIndex === -1) {
            return redirect()->route('staff.verification.index')
                ->with('error', 'Booking not found.');
        }
        
        $booking = $allBookings[$bookingIndex];
        
        // Ensure booking is still pending and unverified
        if ($booking['status'] !== 'pending' || !empty($booking['staff_verified_by'])) {
            return redirect()->route('staff.verification.index')
                ->with('error', 'This booking is no longer available for verification.');
        }

        // Get staff user from session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $staffId = $_SESSION['staff_user']['id'] ?? 1;
        $staffName = $_SESSION['staff_user']['name'] ?? 'Staff Member';
        
        // Update booking with staff verification
        $allBookings[$bookingIndex]['staff_verified_by'] = $staffId;
        $allBookings[$bookingIndex]['staff_verified_at'] = now()->toDateTimeString();
        $allBookings[$bookingIndex]['staff_notes'] = $request->staff_notes ?? 'Requirements verified and approved.';
        $allBookings[$bookingIndex]['status'] = 'staff_verified'; // Changed to staff_verified status
        
        // Save updated bookings back to file
        file_put_contents($bookingsFile, json_encode($allBookings, JSON_PRETTY_PRINT));

        // TODO: Send notification to admin about new booking ready for approval
        // TODO: Send email to citizen confirming requirements were approved

        return redirect()->route('staff.verification.index')
            ->with('success', "Booking #{$id} requirements approved! Sent to admin for final approval.");
    }

    /**
     * Reject booking requirements and notify citizen
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'staff_notes' => 'required|string|max:1000',
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        // --- LOAD BOOKINGS FROM PERSISTENT FILE STORAGE ---
        $bookingsFile = storage_path('app/bookings_data.json');
        $allBookings = [];
        $bookingIndex = -1;
        
        if (file_exists($bookingsFile)) {
            $allBookings = json_decode(file_get_contents($bookingsFile), true);
            if ($allBookings && is_array($allBookings)) {
                foreach ($allBookings as $index => $item) {
                    if ($item['id'] == $id) {
                        $bookingIndex = $index;
                        break;
                    }
                }
            }
        }
        
        if ($bookingIndex === -1) {
            return redirect()->route('staff.verification.index')
                ->with('error', 'Booking not found.');
        }
        
        $booking = $allBookings[$bookingIndex];
        
        // Ensure booking is still pending and unverified
        if ($booking['status'] !== 'pending' || !empty($booking['staff_verified_by'])) {
            return redirect()->route('staff.verification.index')
                ->with('error', 'This booking is no longer available for verification.');
        }

        // Get staff user from session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $staffId = $_SESSION['staff_user']['id'] ?? 1;
        
        // Update booking with rejection
        $allBookings[$bookingIndex]['staff_verified_by'] = $staffId;
        $allBookings[$bookingIndex]['staff_verified_at'] = now()->toDateTimeString();
        $allBookings[$bookingIndex]['staff_notes'] = $request->staff_notes;
        $allBookings[$bookingIndex]['status'] = 'rejected';
        $allBookings[$bookingIndex]['rejected_reason'] = $request->rejection_reason ?? $request->staff_notes;
        
        // Save updated bookings back to file
        file_put_contents($bookingsFile, json_encode($allBookings, JSON_PRETTY_PRINT));

        // TODO: Send email to citizen with rejection reason and required corrections

        return redirect()->route('staff.verification.index')
            ->with('success', "Booking #{$id} requirements rejected. Citizen has been notified.");
    }
}
