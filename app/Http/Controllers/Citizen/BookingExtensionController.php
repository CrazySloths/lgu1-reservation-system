<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BookingExtensionController extends Controller
{
    /**
     * Check if booking extension would cause conflicts
     */
    public function checkConflict(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        
        // --- SECURITY FIX: ENFORCE AUTHENTICATION AND ACCESS CONTROL (OLAC) ---
        $user = Auth::user();
        
        // 1. Enforce Authentication: Remove static ID (1) and session fallback.
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.'
            ], 401); // 401 Unauthorized
        }
        
        $userId = $user->id; 
        
        // 2. Object-Level Access Control (OLAC): Verify booking ownership.
        // This prevents User A from extending User B's booking.
        if ($booking->user_id != $userId) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access: This booking is not yours.'
            ], 403); // 403 Forbidden
        }
        // --- END SECURITY FIX ---
        
        // Validate the booking can be extended
        if (!in_array($booking->status, ['approved', 'pending'])) {
            return response()->json([
                'success' => false,
                'message' => 'Only approved or pending bookings can be extended'
            ], 400);
        }
        
        $newEndTime = $request->input('new_end_time');
        
        // Check for conflicts
        $conflictResult = $booking->checkExtensionConflict($newEndTime);
        
        if ($conflictResult['hasConflict']) {
            // Format conflict details
            $conflictDetails = $conflictResult['conflicts']->map(function($conflict) {
                return [
                    'id' => $conflict->id,
                    'user_name' => $conflict->user_name,
                    'event_name' => $conflict->event_name,
                    'start_time' => Carbon::parse($conflict->start_time)->format('h:i A'),
                    'end_time' => Carbon::parse($conflict->end_time)->format('h:i A'),
                ];
            });
            
            return response()->json([
                'success' => false,
                'hasConflict' => true,
                'message' => $conflictResult['message'],
                'conflicts' => $conflictDetails
            ]);
        }
        
        return response()->json([
            'success' => true,
            'hasConflict' => false,
            'message' => 'No conflicts detected. Extension is possible.'
        ]);
    }
    
    /**
     * Process the booking extension request
     */
    public function extend(Request $request, $bookingId)
    {
        $request->validate([
            'new_end_time' => 'required|date_format:H:i',
            'extension_reason' => 'nullable|string|max:500'
        ]);
        
        $booking = Booking::findOrFail($bookingId);
        
        // --- SECURITY FIX: ENFORCE AUTHENTICATION AND ACCESS CONTROL (OLAC) ---
        $user = Auth::user();

        // 1. Enforce Authentication: Remove static ID (1) and session fallback.
        if (!$user) {
            return redirect()->back()->with('error', 'Authentication required.');
        }

        $userId = $user->id; 
        
        // 2. Object-Level Access Control (OLAC): Verify booking ownership.
        // This prevents unauthorized extension requests.
        if ($booking->user_id != $userId) {
            return redirect()->back()->with('error', 'Unauthorized access: This booking is not yours.');
        }
        // --- END SECURITY FIX ---
        
        // Validate the booking can be extended
        if (!in_array($booking->status, ['approved', 'pending'])) {
            return redirect()->back()->with('error', 'Only approved or pending bookings can be extended');
        }
        
        $newEndTime = $request->input('new_end_time');
        $extensionReason = $request->input('extension_reason');
        
        // Check for conflicts
        $conflictResult = $booking->checkExtensionConflict($newEndTime);
        
        if ($conflictResult['hasConflict']) {
            $conflictNames = $conflictResult['conflicts']->pluck('user_name')->join(', ');
            return redirect()->back()->with('error', 
                'Cannot extend booking: Schedule conflict detected with existing bookings by ' . $conflictNames);
        }
        
        // Store old end time for logging
        $oldEndTime = $booking->end_time;
        
        // Update the booking
        $booking->end_time = $newEndTime;
        
        // Add extension note to admin notes
        $extensionNote = "\n[" . now()->format('Y-m-d H:i:s') . "] Booking extended from " . 
                        Carbon::parse($oldEndTime)->format('h:i A') . " to " . 
                        Carbon::parse($newEndTime)->format('h:i A');
        
        if ($extensionReason) {
            $extensionNote .= " - Reason: " . $extensionReason;
        }
        
        $booking->admin_notes = ($booking->admin_notes ?? '') . $extensionNote;
        $booking->save();
        
        // Log the extension
        Log::info('Booking extended', [
            'booking_id' => $booking->id,
            'user_id' => $userId,
            'old_end_time' => $oldEndTime,
            'new_end_time' => $newEndTime,
            'reason' => $extensionReason
        ]);
        
        return redirect()->route('citizen.reservation.history')
            ->with('success', 'Booking extended successfully from ' . 
                   Carbon::parse($oldEndTime)->format('h:i A') . ' to ' . 
                   Carbon::parse($newEndTime)->format('h:i A'));
    }
}