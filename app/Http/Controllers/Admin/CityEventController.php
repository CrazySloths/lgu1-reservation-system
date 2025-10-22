<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CityEventController extends Controller
{
    /**
     * Display a listing of city events.
     * Note: City events are also visible in the main facility calendar.
     */
    public function index()
    {
        $cityEvents = Booking::where(function($query) {
                $query->where('user_name', 'City Government')
                      ->orWhere('event_name', 'LIKE', '%City Event%')
                      ->orWhere('event_name', 'LIKE', '%CITY EVENT%')
                      ->orWhere('applicant_name', 'City Mayor Office');
            })
            ->orderBy('event_date', 'desc')
            ->paginate(15);

        return view('admin.city-events.index', compact('cityEvents'));
    }

    /**
     * Show the form for creating a new city event.
     */
    public function create()
    {
        $facilities = Facility::where('status', 'active')->get();
        return view('admin.city-events.create', compact('facilities'));
    }

    /**
     * Store a newly created city event in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,facility_id',
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'expected_attendees' => 'required|integer|min:1',
            'mayor_authorization' => 'required|string|max:255',
        ]);

        // Calculate duration and fees
        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);
        $durationHours = $start->diffInHours($end);
        
        // Base fee calculation (can be waived for city events if needed)
        $baseFee = 5000;
        $extraHours = max(0, $durationHours - 3);
        $totalFee = 0; // City events are typically free

        // Check for conflicts with existing bookings
        $conflicts = Booking::where('facility_id', $request->facility_id)
            ->where('event_date', $request->event_date)
            ->where('status', '!=', 'rejected')
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function ($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->get();

        // If there are conflicts, notify but still allow booking (city events have priority)
        if ($conflicts->count() > 0) {
            // Optionally reject conflicting citizen bookings
            foreach ($conflicts as $conflict) {
                if ($conflict->user_name !== 'City Government') {
                    $conflict->update([
                        'status' => 'rejected',
                        'rejected_reason' => 'Overridden by City Event: ' . $request->event_name . ' (Mayor Authorization: ' . $request->mayor_authorization . ')'
                    ]);
                }
            }
        }

        // Create the city event booking
        $booking = Booking::create([
            'facility_id' => $request->facility_id,
            'user_id' => Auth::id() ?? 1,
            'user_name' => 'City Government',
            'applicant_name' => 'City Mayor Office',
            'applicant_email' => 'mayor@lgu1.gov.ph',
            'applicant_phone' => $request->contact_number ?? 'N/A',
            'applicant_address' => 'City Hall, LGU1',
            'event_name' => 'CITY EVENT: ' . $request->event_name,
            'event_description' => $request->event_description . '\n\nMayor Authorization: ' . $request->mayor_authorization,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'expected_attendees' => $request->expected_attendees,
            'total_fee' => $totalFee,
            'status' => 'approved', // Auto-approved
            'approved_by' => Auth::id() ?? 1,
            'approved_at' => now(),
            'admin_notes' => 'City Event - Mayor Authorization: ' . $request->mayor_authorization,
        ]);

        return redirect()->route('admin.city-events.index')
            ->with('success', 'City event created successfully! ' . 
                   ($conflicts->count() > 0 ? $conflicts->count() . ' conflicting citizen booking(s) were automatically rejected.' : ''));
    }

    /**
     * Display the specified city event.
     */
    public function show($id)
    {
        $cityEvent = Booking::findOrFail($id);
        return view('admin.city-events.show', compact('cityEvent'));
    }

    /**
     * Show the form for editing the specified city event.
     */
    public function edit($id)
    {
        $cityEvent = Booking::findOrFail($id);
        $facilities = Facility::where('status', 'active')->get();
        return view('admin.city-events.edit', compact('cityEvent', 'facilities'));
    }

    /**
     * Update the specified city event in storage.
     */
    public function update(Request $request, $id)
    {
        $cityEvent = Booking::findOrFail($id);

        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,facility_id',
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'expected_attendees' => 'required|integer|min:1',
        ]);

        $cityEvent->update([
            'facility_id' => $request->facility_id,
            'event_name' => 'CITY EVENT: ' . $request->event_name,
            'event_description' => $request->event_description,
            'event_date' => $request->event_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'expected_attendees' => $request->expected_attendees,
        ]);

        return redirect()->route('admin.city-events.index')
            ->with('success', 'City event updated successfully!');
    }

    /**
     * Remove the specified city event from storage.
     */
    public function destroy($id)
    {
        $cityEvent = Booking::findOrFail($id);
        $cityEvent->delete();

        return redirect()->route('admin.city-events.index')
            ->with('success', 'City event deleted successfully!');
    }
}

