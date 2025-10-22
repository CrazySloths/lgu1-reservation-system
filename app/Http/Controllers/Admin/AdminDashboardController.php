<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\PaymentSlip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with REAL database data
     */
    public function index(Request $request)
    {
        // Get authenticated admin
        $admin = Auth::user();
        
        // Fallback admin data if not authenticated
        if (!$admin) {
            $admin = (object) [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@lgu1.com',
                'role' => 'admin',
                'status' => 'active'
            ];
        }
        
        $admin->full_name = $admin->name;
        $admin->avatar_initials = $this->generateInitials($admin->name);
        
        // REAL DATABASE QUERIES
        
        // Pending Approvals (status = pending)
        $pendingApprovals = Booking::with(['facility', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        $pendingApprovalsCount = $pendingApprovals->count();
        
        // Schedule Conflicts (detect overlapping bookings)
        $conflicts = $this->detectScheduleConflicts();
        
        // Overdue Payments (payment slips past due date and not paid)
        $overduePayments = PaymentSlip::with(['booking.facility', 'booking.user'])
            ->where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->orderBy('due_date', 'asc')
            ->get();
        
        // Monthly Statistics (ALL TIME for better overview)
        $monthlyStats = [
            'bookings_count' => Booking::count(),
            'approved_bookings' => Booking::where('status', 'approved')->count(),
            'revenue' => PaymentSlip::where('status', 'paid')->sum('amount'),
            'pending_revenue' => PaymentSlip::where('status', 'unpaid')->sum('amount')
        ];
        
        // Facility Statistics (total bookings per facility)
        $facilityStats = Facility::withCount('bookings')
        ->get()
        ->map(function ($facility) {
            return (object) [
                'id' => $facility->id,
                'name' => $facility->name,
                'monthly_bookings' => $facility->bookings_count
            ];
        });
        
        // Upcoming Reservations (approved bookings in the future)
        $upcomingReservations = Booking::with(['facility', 'user'])
            ->where('status', 'approved')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->limit(5)
            ->get();
        
        // Recent Activity (last 5 booking updates)
        $recentActivity = $this->getRecentActivity();
        
        \Log::info("Admin Dashboard loaded with REAL data for: " . $admin->name, [
            'pending_approvals' => $pendingApprovalsCount,
            'conflicts' => $conflicts->count(),
            'overdue_payments' => $overduePayments->count(),
            'monthly_bookings' => $monthlyStats['bookings_count']
        ]);
        
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
     * Detect schedule conflicts (overlapping bookings for same facility)
     */
    private function detectScheduleConflicts()
    {
        $conflicts = collect([]);
        
        // Get all approved bookings for the next 30 days
        $bookings = Booking::with('facility')
            ->where('status', 'approved')
            ->where('event_date', '>=', now()->toDateString())
            ->where('event_date', '<=', now()->addDays(30)->toDateString())
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();
        
        // Check for overlaps
        foreach ($bookings as $booking) {
            $overlapping = Booking::where('facility_id', $booking->facility_id)
                ->where('id', '!=', $booking->id)
                ->where('status', 'approved')
                ->where('event_date', $booking->event_date)
                ->where(function($query) use ($booking) {
                    $query->whereBetween('start_time', [$booking->start_time, $booking->end_time])
                          ->orWhereBetween('end_time', [$booking->start_time, $booking->end_time])
                          ->orWhere(function($q) use ($booking) {
                              $q->where('start_time', '<=', $booking->start_time)
                                ->where('end_time', '>=', $booking->end_time);
                          });
                })
                ->first();
            
            if ($overlapping) {
                $conflicts->push((object)[
                    'booking1' => $booking,
                    'booking2' => $overlapping
                ]);
            }
        }
        
        return $conflicts;
    }
    
    /**
     * Get recent activity feed
     */
    private function getRecentActivity()
    {
        $activities = collect([]);
        
        // Recent approvals
        $recentApprovals = Booking::with('facility')
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->limit(3)
            ->get();
        
        foreach ($recentApprovals as $booking) {
            $activities->push([
                'type' => 'approval',
                'message' => 'Reservation approved for ' . $booking->facility->name,
                'details' => 'Event: ' . $booking->event_name . ' on ' . Carbon::parse($booking->event_date)->format('M d, Y'),
                'time' => $booking->updated_at,
                'icon' => 'check-circle',
                'color' => 'text-green-600'
            ]);
        }
        
        // Recent payments
        $recentPayments = PaymentSlip::with('booking')
            ->where('status', 'paid')
            ->orderBy('updated_at', 'desc')
            ->limit(2)
            ->get();
        
        foreach ($recentPayments as $payment) {
            $activities->push([
                'type' => 'payment',
                'message' => 'Payment received',
                'details' => '₱' . number_format($payment->amount, 2) . ' - ' . $payment->reference_number,
                'time' => $payment->updated_at,
                'icon' => 'currency-dollar',
                'color' => 'text-blue-600'
            ]);
        }
        
        // Sort by time
        return $activities->sortByDesc('time')->take(5);
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
     * Get quick stats for admin dashboard (REAL DATA)
     */
    public function getQuickStats(Request $request)
    {
        $stats = [
            'pending_approvals' => Booking::where('status', 'pending')->count(),
            'conflicts' => $this->detectScheduleConflicts()->count(),
            'overdue_payments' => PaymentSlip::where('status', 'unpaid')
                                           ->where('due_date', '<', now())
                                           ->count(),
            'todays_events' => Booking::where('status', 'approved')
                                     ->whereDate('event_date', now()->toDateString())
                                     ->count()
        ];
        
        return response()->json($stats);
    }
}