<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StaffDashboardController extends Controller
{
    /**
     * Display the staff dashboard with static verification metrics
     */
    public function index(Request $request)
    {
        // Get static staff user from session or create from URL parameters
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $user = null;
        
        if (isset($_SESSION['static_staff_user'])) {
            $staffData = $_SESSION['static_staff_user'];
            $user = (object) $staffData;
            $user->full_name = $user->name;
            $user->avatar_initials = $this->generateInitials($user->name);
        }
        
        // Fallback staff data
        if (!$user) {
            $userId = $request->get('user_id', 50);
            $username = $request->get('username', 'Staff Member');
            
            // Extract clean username 
            $cleanUsername = str_replace(['Staff-Facilities123', '-Facilities123'], '', $username);
            $cleanUsername = ucfirst(trim($cleanUsername, '-'));
            if (empty($cleanUsername) || $cleanUsername === 'Staff') {
                $cleanUsername = 'Staff Member';
            }
            
            $user = (object) [
                'id' => $userId,
                'name' => $cleanUsername,
                'email' => 'staff@lgu1.com',
                'role' => 'staff',
                'status' => 'active'
            ];
            $user->full_name = $user->name;
            $user->avatar_initials = $this->generateInitials($user->name);
        }
        
        // Static dashboard metrics (no database queries)
        $pendingVerifications = 8;
        $myVerificationsToday = 3;
        $myTotalVerifications = 45;
        $totalPendingAdmin = 5;

        // Static recent bookings pending staff verification
        $recentPendingBookings = collect([
            (object)[
                'id' => 1,
                'event_name' => 'Birthday Party',
                'event_date' => '2024-10-05',
                'start_time' => '14:00:00',
                'user' => (object)['name' => 'Maria Santos'],
                'facility' => (object)['name' => 'Community Hall'],
                'created_at' => now()->subHours(2)
            ],
            (object)[
                'id' => 2,
                'event_name' => 'Training Workshop',
                'event_date' => '2024-10-07',
                'start_time' => '09:00:00',
                'user' => (object)['name' => 'Carlos Rivera'],
                'facility' => (object)['name' => 'Conference Room'],
                'created_at' => now()->subHours(5)
            ],
            (object)[
                'id' => 3,
                'event_name' => 'Sports Event',
                'event_date' => '2024-10-10',
                'start_time' => '16:00:00',
                'user' => (object)['name' => 'Juan Cruz'],
                'facility' => (object)['name' => 'Sports Complex'],
                'created_at' => now()->subHours(8)
            ]
        ]);

        // Static my recent verifications
        $myRecentVerifications = collect([
            (object)[
                'id' => 10,
                'event_name' => 'Community Meeting',
                'event_date' => '2024-09-28',
                'user' => (object)['name' => 'Ana Lopez'],
                'facility' => (object)['name' => 'Meeting Room'],
                'staff_verified_at' => now()->subHours(1),
                'staff_notes' => 'Documents verified, approved for processing'
            ],
            (object)[
                'id' => 11,
                'event_name' => 'Wedding Reception',
                'event_date' => '2024-09-30',
                'user' => (object)['name' => 'Pedro Garcia'],
                'facility' => (object)['name' => 'Function Hall'],
                'staff_verified_at' => now()->subHours(3),
                'staff_notes' => 'All requirements complete'
            ]
        ]);

        error_log("Staff Dashboard loaded with static data for: " . $user->name);

        return view('staff.dashboard', compact(
            'user',
            'pendingVerifications',
            'myVerificationsToday', 
            'myTotalVerifications',
            'totalPendingAdmin',
            'recentPendingBookings',
            'myRecentVerifications'
        ));
    }

    /**
     * Generate initials from name
     */
    private function generateInitials($name)
    {
        $nameParts = explode(' ', trim($name));
        $firstName = $nameParts[0] ?? 'S';
        $lastName = end($nameParts);
        
        return strtoupper(
            substr($firstName, 0, 1) . 
            (($lastName !== $firstName) ? substr($lastName, 0, 1) : 'M')
        );
    }

    /**
     * Get personal statistics for the staff member (static data)
     */
    public function myStats()
    {
        $stats = [
            'total_verifications' => 45,
            'verifications_this_week' => 12,
            'verifications_this_month' => 38,
            'average_per_day' => 1.8
        ];

        return view('staff.stats', compact('stats'));
    }

    /**
     * Display list of bookings pending staff verification (static data)
     */
    public function verificationIndex()
    {
        $bookings = collect([
            (object)[
                'id' => 1,
                'event_name' => 'Birthday Party',
                'event_date' => '2024-10-05',
                'user' => (object)['name' => 'Maria Santos'],
                'facility' => (object)['name' => 'Community Hall'],
                'created_at' => now()->subHours(2)
            ],
            (object)[
                'id' => 2,
                'event_name' => 'Training Workshop',
                'event_date' => '2024-10-07',
                'user' => (object)['name' => 'Carlos Rivera'],
                'facility' => (object)['name' => 'Conference Room'],
                'created_at' => now()->subHours(5)
            ]
        ]);

        return view('staff.verification.index', compact('bookings'));
    }

    /**
     * Display booking details for verification (static data)
     */
    public function verificationShow($bookingId)
    {
        $booking = (object)[
            'id' => $bookingId,
            'event_name' => 'Sample Event',
            'event_date' => '2024-10-05',
            'start_time' => '14:00:00',
            'end_time' => '18:00:00',
            'user' => (object)[
                'name' => 'Sample User',
                'email' => 'sample@example.com'
            ],
            'facility' => (object)[
                'name' => 'Sample Facility',
                'capacity' => 100
            ],
            'created_at' => now()->subHours(2)
        ];
        
        return view('staff.verification.show', compact('booking'));
    }

    /**
     * Process staff verification (static response)
     */
    public function processVerification(Request $request, $bookingId)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'staff_notes' => 'required|string|max:500'
        ]);

        // Log the verification action
        error_log("Staff verification processed: " . $request->action . " for booking " . $bookingId);

        return redirect()->route('staff.verification.index')
            ->with('success', 'Booking ' . $request->action . 'd successfully!');
    }
}