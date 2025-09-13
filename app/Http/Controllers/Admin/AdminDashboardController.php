<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\PaymentSlip;
use App\Models\Facility;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with overview statistics and key actions.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get current month data
        $currentMonth = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        
        // Get pending approvals count
        $pendingApprovalsCount = Booking::pending()->count();
        
        // Get pending approvals for display (latest 5)
        $pendingApprovals = Booking::with(['facility', 'user'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get schedule conflicts (same facility, same date, overlapping times, both approved)
        $conflicts = $this->getScheduleConflicts();
        
        // Get overdue payments
        $overduePayments = PaymentSlip::with(['booking.facility', 'user'])
            ->where('status', 'unpaid')
            ->where('due_date', '<', Carbon::now())
            ->limit(5)
            ->get();
        
        // Current month statistics
        $monthlyStats = [
            'bookings_count' => Booking::whereBetween('created_at', [$currentMonth, $currentMonthEnd])->count(),
            'approved_bookings' => Booking::approved()
                ->whereBetween('event_date', [$currentMonth, $currentMonthEnd])
                ->count(),
            'revenue' => PaymentSlip::where('status', 'paid')
                ->whereBetween('paid_at', [$currentMonth, $currentMonthEnd])
                ->sum('amount'),
            'pending_revenue' => PaymentSlip::where('status', 'unpaid')
                ->sum('amount')
        ];
        
        // Facility utilization for current month
        $facilityStats = Facility::withCount(['bookings as monthly_bookings' => function ($query) use ($currentMonth, $currentMonthEnd) {
            $query->where('status', 'approved')
                  ->whereBetween('event_date', [$currentMonth, $currentMonthEnd]);
        }])->get();
        
        // Upcoming approved reservations (next 7 days)
        $upcomingReservations = Booking::with(['facility', 'user'])
            ->approved()
            ->whereBetween('event_date', [Carbon::now(), Carbon::now()->addDays(7)])
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();
        
        // Recent activity (last 7 days)
        $recentActivity = $this->getRecentActivity();
        
        return view('admin.dashboard', compact(
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
     * Get schedule conflicts for alert system
     */
    private function getScheduleConflicts()
    {
        $conflicts = [];
        
        // Get approved bookings for the next 30 days
        $upcomingBookings = Booking::approved()
            ->whereBetween('event_date', [Carbon::now(), Carbon::now()->addDays(30)])
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(['facility_id', 'event_date']);
        
        foreach ($upcomingBookings as $facilityId => $dateGroups) {
            foreach ($dateGroups as $date => $bookings) {
                if ($bookings->count() > 1) {
                    // Check for time overlaps
                    for ($i = 0; $i < $bookings->count() - 1; $i++) {
                        for ($j = $i + 1; $j < $bookings->count(); $j++) {
                            if ($this->timesOverlap(
                                $bookings[$i]->start_time,
                                $bookings[$i]->end_time,
                                $bookings[$j]->start_time,
                                $bookings[$j]->end_time
                            )) {
                                $conflicts[] = [
                                    'type' => 'time_overlap',
                                    'facility' => $bookings[$i]->facility,
                                    'date' => $date,
                                    'booking1' => $bookings[$i],
                                    'booking2' => $bookings[$j]
                                ];
                            }
                        }
                    }
                }
            }
        }
        
        return collect($conflicts)->take(5);
    }
    
    /**
     * Check if two time ranges overlap
     */
    private function timesOverlap($start1, $end1, $start2, $end2)
    {
        return $start1 < $end2 && $start2 < $end1;
    }
    
    /**
     * Get recent activity for the dashboard
     */
    private function getRecentActivity()
    {
        $activities = [];
        
        // Recent approvals (last 7 days)
        $recentApprovals = Booking::with(['facility', 'user'])
            ->where('status', 'approved')
            ->whereBetween('approved_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->orderBy('approved_at', 'desc')
            ->limit(3)
            ->get();
        
        foreach ($recentApprovals as $booking) {
            $activities[] = [
                'type' => 'approval',
                'message' => "Reservation approved for {$booking->facility->name}",
                'details' => "Event: {$booking->event_name} on " . Carbon::parse($booking->event_date)->format('M j, Y'),
                'time' => $booking->approved_at,
                'icon' => 'check-circle',
                'color' => 'text-green-600'
            ];
        }
        
        // Recent payments (last 7 days)
        $recentPayments = PaymentSlip::with(['booking.facility'])
            ->where('status', 'paid')
            ->whereBetween('paid_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->orderBy('paid_at', 'desc')
            ->limit(3)
            ->get();
        
        foreach ($recentPayments as $payment) {
            $activities[] = [
                'type' => 'payment',
                'message' => "Payment received for {$payment->booking->facility->name}",
                'details' => "₱" . number_format($payment->amount, 2) . " - " . $payment->slip_number,
                'time' => $payment->paid_at,
                'icon' => 'currency-dollar',
                'color' => 'text-blue-600'
            ];
        }
        
        // Recent new bookings (last 7 days)
        $recentBookings = Booking::with(['facility'])
            ->where('status', 'pending')
            ->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()])
            ->orderBy('created_at', 'desc')
            ->limit(2)
            ->get();
        
        foreach ($recentBookings as $booking) {
            $activities[] = [
                'type' => 'new_booking',
                'message' => "New reservation request for {$booking->facility->name}",
                'details' => "Event: {$booking->event_name} on " . Carbon::parse($booking->event_date)->format('M j, Y'),
                'time' => $booking->created_at,
                'icon' => 'calendar',
                'color' => 'text-yellow-600'
            ];
        }
        
        // Sort by time and take latest 5
        return collect($activities)
            ->sortByDesc('time')
            ->take(5)
            ->values();
    }
    
    /**
     * Get quick stats for API calls (for real-time updates)
     */
    public function getQuickStats()
    {
        return response()->json([
            'pending_approvals' => Booking::pending()->count(),
            'conflicts' => $this->getScheduleConflicts()->count(),
            'overdue_payments' => PaymentSlip::where('status', 'unpaid')
                ->where('due_date', '<', Carbon::now())
                ->count(),
            'todays_events' => Booking::approved()
                ->whereDate('event_date', Carbon::today())
                ->count()
        ]);
    }
}
