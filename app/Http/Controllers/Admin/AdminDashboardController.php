<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with static data support
     */
    public function index(Request $request)
    {
        // Get static admin from session
        session_start();
        $admin = null;
        
        if (isset($_SESSION['static_admin_user'])) {
            $adminData = $_SESSION['static_admin_user'];
            $admin = (object) $adminData;
            $admin->full_name = $admin->name;
            $admin->avatar_initials = $this->generateInitials($admin->name);
        }
        
        // Fallback admin data
        if (!$admin) {
            $admin = (object) [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@lgu1.com',
                'role' => 'admin',
                'status' => 'active'
            ];
            $admin->full_name = $admin->name;
            $admin->avatar_initials = $this->generateInitials($admin->name);
        }
        
        // Static dashboard statistics (no database queries)
        $pendingApprovalsCount = 12;
        $pendingApprovals = collect([
            (object)[
                'id' => 1,
                'event_name' => 'Community Meeting',
                'event_date' => '2024-10-15',
                'start_time' => '09:00:00',
                'end_time' => '12:00:00',
                'facility' => (object)['name' => 'Community Hall'],
                'user' => (object)['name' => 'John Doe']
            ],
            (object)[
                'id' => 2,
                'event_name' => 'Sports Tournament',
                'event_date' => '2024-10-20',
                'start_time' => '14:00:00',
                'end_time' => '18:00:00',
                'facility' => (object)['name' => 'Sports Complex'],
                'user' => (object)['name' => 'Jane Smith']
            ]
        ]);
        
        $conflicts = collect([]);
        
        $overduePayments = collect([
            (object)[
                'id' => 1,
                'amount' => 5000,
                'due_date' => '2024-09-20',
                'booking' => (object)[
                    'facility' => (object)['name' => 'Conference Room']
                ],
                'user' => (object)['name' => 'Mike Johnson']
            ]
        ]);
        
        // Static monthly statistics
        $monthlyStats = [
            'bookings_count' => 89,
            'approved_bookings' => 67,
            'revenue' => 125000.00,
            'pending_revenue' => 45000.00
        ];
        
        // Static facility stats
        $facilityStats = collect([
            (object)[
                'id' => 1,
                'name' => 'Community Hall',
                'monthly_bookings' => 15
            ],
            (object)[
                'id' => 2,
                'name' => 'Sports Complex',
                'monthly_bookings' => 22
            ],
            (object)[
                'id' => 3,
                'name' => 'Conference Room',
                'monthly_bookings' => 30
            ]
        ]);
        
        // Static upcoming reservations
        $upcomingReservations = collect([
            (object)[
                'id' => 1,
                'event_name' => 'Youth Workshop',
                'event_date' => '2024-09-30',
                'start_time' => '09:00:00',
                'facility' => (object)['name' => 'Training Center'],
                'user' => (object)['name' => 'Sarah Wilson']
            ],
            (object)[
                'id' => 2,
                'event_name' => 'Health Seminar',
                'event_date' => '2024-10-02',
                'start_time' => '14:00:00',
                'facility' => (object)['name' => 'Community Hall'],
                'user' => (object)['name' => 'Dr. Martinez']
            ]
        ]);
        
        // Static recent activity
        $recentActivity = collect([
            [
                'type' => 'approval',
                'message' => 'Reservation approved for Community Hall',
                'details' => 'Event: Town Meeting on Oct 1, 2024',
                'time' => now()->subHours(2),
                'icon' => 'check-circle',
                'color' => 'text-green-600'
            ],
            [
                'type' => 'payment',
                'message' => 'Payment received for Sports Complex',
                'details' => '₱8,000.00 - PAY-2024-001',
                'time' => now()->subHours(4),
                'icon' => 'currency-dollar',
                'color' => 'text-blue-600'
            ],
            [
                'type' => 'new_booking',
                'message' => 'New reservation request for Conference Room',
                'details' => 'Event: Business Meeting on Oct 5, 2024',
                'time' => now()->subHours(6),
                'icon' => 'calendar',
                'color' => 'text-yellow-600'
            ]
        ]);
        
        error_log("Admin Dashboard loaded with static data for: " . $admin->name);
        
        return view('admin.dashboard', compact(
            'admin',
            'pendingApprovalsCount',
            'pendingApprovals',
            'conflicts',
            'overduePayments',
            'monthlyStats',
            'facilityStats',
            'upcomingReservations',
            'recentActivity'
        ));
    }
    
    /**
     * Generate initials from name
     */
    private function generateInitials($name)
    {
        $nameParts = explode(' ', trim($name));
        $firstName = $nameParts[0] ?? 'A';
        $lastName = end($nameParts);
        
        return strtoupper(
            substr($firstName, 0, 1) . 
            (($lastName !== $firstName) ? substr($lastName, 0, 1) : 'D')
        );
    }
    
    /**
     * Get quick stats for admin dashboard
     */
    public function getQuickStats(Request $request)
    {
        // Return static stats since database is unreliable
        $stats = [
            'pending_approvals' => 12,
            'conflicts' => 0,
            'overdue_payments' => 1,
            'todays_events' => 3
        ];
        
        return response()->json($stats);
    }
}