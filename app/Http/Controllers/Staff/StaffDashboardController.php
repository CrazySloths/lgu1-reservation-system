<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    /**
     * Display the staff dashboard with verification metrics
     */
    public function index()
    {
        $staff = Auth::user();
        
        // Get verification metrics
        $pendingVerifications = Booking::where('status', 'pending')
            ->whereNull('staff_verified_by')
            ->count();
            
        $myVerificationsToday = Booking::where('staff_verified_by', $staff->id)
            ->whereDate('staff_verified_at', today())
            ->count();
            
        $myTotalVerifications = Booking::where('staff_verified_by', $staff->id)
            ->count();
            
        $totalPendingAdmin = Booking::where('status', 'pending')
            ->whereNotNull('staff_verified_by')
            ->count();

        // Get recent bookings pending staff verification
        $recentPendingBookings = Booking::with(['user', 'facility'])
            ->where('status', 'pending')
            ->whereNull('staff_verified_by')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get my recent verifications
        $myRecentVerifications = Booking::with(['user', 'facility'])
            ->where('staff_verified_by', $staff->id)
            ->orderBy('staff_verified_at', 'desc')
            ->take(5)
            ->get();

        return view('staff.dashboard', compact(
            'pendingVerifications',
            'myVerificationsToday', 
            'myTotalVerifications',
            'totalPendingAdmin',
            'recentPendingBookings',
            'myRecentVerifications'
        ));
    }

    /**
     * Get personal statistics for the staff member
     */
    public function myStats()
    {
        $staff = Auth::user();
        
        $stats = [
            'total_verifications' => Booking::where('staff_verified_by', $staff->id)->count(),
            'verifications_this_week' => Booking::where('staff_verified_by', $staff->id)
                ->whereBetween('staff_verified_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'verifications_this_month' => Booking::where('staff_verified_by', $staff->id)
                ->whereMonth('staff_verified_at', now()->month)
                ->count(),
            'average_per_day' => round(
                Booking::where('staff_verified_by', $staff->id)
                    ->whereMonth('staff_verified_at', now()->month)
                    ->count() / now()->day, 1
            )
        ];

        return view('staff.stats', compact('stats'));
    }

    /**
     * Display list of bookings pending staff verification
     */
    public function verificationIndex()
    {
        $bookings = Booking::with(['user', 'facility'])
            ->where('status', 'pending')
            ->whereNull('staff_verified_by')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('staff.verification.index', compact('bookings'));
    }

    /**
     * Display booking details for verification
     */
    public function verificationShow(Booking $booking)
    {
        $booking->load(['user', 'facility']);
        
        return view('staff.verification.show', compact('booking'));
    }

    /**
     * Process staff verification
     */
    public function processVerification(Request $request, Booking $booking)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'staff_notes' => 'required|string|max:500'
        ]);

        $booking->update([
            'staff_verified_by' => Auth::id(),
            'staff_verified_at' => now(),
            'staff_notes' => $request->staff_notes,
            'status' => $request->action === 'approve' ? 'staff_approved' : 'rejected'
        ]);

        return redirect()->route('staff.verification.index')
            ->with('success', 'Booking ' . $request->action . 'd successfully!');
    }
}
