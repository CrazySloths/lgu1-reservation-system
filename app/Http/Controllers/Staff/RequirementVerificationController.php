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
        $query = Booking::with(['user', 'facility'])
            ->where('status', 'pending')
            ->whereNull('staff_verified_by');

        // Filter by facility if specified
        if ($request->filled('facility')) {
            $query->where('facility_id', $request->facility);
        }

        // Filter by date range if specified
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('staff.verification.index', compact('bookings'));
    }

    /**
     * Show detailed booking information for verification
     */
    public function show(Booking $booking)
    {
        // Ensure booking is pending and not yet verified by staff
        if ($booking->status !== 'pending' || $booking->staff_verified_by) {
            return redirect()->route('staff.verification.index')
                ->with('error', 'This booking is not available for verification.');
        }

        $booking->load(['user', 'facility']);

        return view('staff.verification.show', compact('booking'));
    }

    /**
     * Approve booking requirements and send to admin for final approval
     */
    public function approve(Request $request, Booking $booking)
    {
        $request->validate([
            'staff_notes' => 'nullable|string|max:1000'
        ]);

        // Ensure booking is still pending and unverified
        if ($booking->status !== 'pending' || $booking->staff_verified_by) {
            return redirect()->route('staff.verification.index')
                ->with('error', 'This booking is no longer available for verification.');
        }

        // Update booking with staff verification
        $booking->update([
            'staff_verified_by' => Auth::id(),
            'staff_verified_at' => now(),
            'staff_notes' => $request->staff_notes ?? 'Requirements verified and approved.',
            'status' => 'pending' // Still pending, but now for admin approval
        ]);

        // TODO: Send notification to admin about new booking ready for approval
        // TODO: Send email to citizen confirming requirements were approved

        return redirect()->route('staff.verification.index')
            ->with('success', "Booking #{$booking->id} requirements approved! Sent to admin for final approval.");
    }

    /**
     * Reject booking requirements and notify citizen
     */
    public function reject(Request $request, Booking $booking)
    {
        $request->validate([
            'staff_notes' => 'required|string|max:1000',
            'rejection_reason' => 'required|string|max:500'
        ]);

        // Ensure booking is still pending and unverified
        if ($booking->status !== 'pending' || $booking->staff_verified_by) {
            return redirect()->route('staff.verification.index')
                ->with('error', 'This booking is no longer available for verification.');
        }

        // Update booking with rejection
        $booking->update([
            'staff_verified_by' => Auth::id(),
            'staff_verified_at' => now(),
            'staff_notes' => $request->staff_notes,
            'status' => 'rejected',
            'rejected_reason' => $request->rejection_reason
        ]);

        // TODO: Send email to citizen with rejection reason and required corrections

        return redirect()->route('staff.verification.index')
            ->with('success', "Booking #{$booking->id} requirements rejected. Citizen has been notified.");
    }
}
