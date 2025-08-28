<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking; // Import the Booking model
use Illuminate\Database\Eloquent\SoftDeletes;

class FacilityController extends Controller
{
    /**
     * Display a listing of the facilities.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $facilities = Facility::all();
        return view('FacilityList', compact('facilities'));
    }

    /**
     * Display the booking calendar page.
     *
     * @return \Illuminate\View\View
     */
    public function calendar()
    {
        $facilities = Facility::all();
        return view('calendar', compact('facilities'));
    }

    /**
     * Display the new reservation form page.
     *
     * @return \Illuminate\View\View
     */
    public function newReservation()
    {
        $facilities = Facility::all();
        return view('new-reservation', compact('facilities'));
    }

    /**
     * Store a newly created facility in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'rate_per_hour' => 'nullable|numeric|min:0',
        ]);

        Facility::create($validatedData);

        return redirect()->route('facility.list')->with('success', 'Facility added successfully!');
    }

    /**
     * Update the specified facility in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $facility_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $facility_id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'rate_per_hour' => 'nullable|numeric|min:0',
        ]);

        $facility = Facility::findOrFail($facility_id);
        $facility->update($validatedData);

        return redirect()->route('facility.list')->with('success', 'Facility updated successfully!');
    }

    /**
     * Remove the specified facility from storage.
     *
     * @param  int  $facility_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($facility_id)
    {
        $facility = Facility::findOrFail($facility_id);
        $facility->delete();
        return redirect()->route('facility.list')->with('success', 'Facility archived successfully!');
    }

    /**
     * Get events for a specific facility.
     *
     * @param  int  $facility_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEvents($facility_id)
    {
        $bookings = Booking::where('facility_id', $facility_id)
                            ->where('status', 'approved')
                            ->get();

        $events = [];

        foreach ($bookings as $booking) {
            $events[] = [
                'title' => $booking->user_name,
                'start' => $booking->start_time,
                'end' => $booking->end_time,
                'backgroundColor' => '#3B82F6'
            ];
        }

        return response()->json($events);
    }
    
    /**
     * Store a new booking request in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeBooking(Request $request)
    {
        $validatedData = $request->validate([
            'facility_id' => 'required|exists:facilities,facility_id',
            'user_name' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        Booking::create($validatedData);

        return redirect()->route('new-reservation')->with('success', 'Booking request submitted successfully! Your request is pending for approval.');
    }

    /**
     * Display the booking approval dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function approvalDashboard()
    {
        $pendingBookings = Booking::where('status', 'pending')
                                  ->with('facility')
                                  ->get();
    
        return view('booking-approval', compact('pendingBookings'));
    }

    /**
     * Approve a booking request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'approved';
        $booking->save();

        return redirect()->route('bookings.approval')->with('success', 'Booking approved successfully!');
    }
    
    /**
     * Reject a booking request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rejectBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'rejected';
        $booking->save();
        
        return redirect()->route('bookings.approval')->with('success', 'Booking rejected successfully!');
    }

    /**
     * Display the reservation status page for all user bookings.
     *
     * @return \Illuminate\View\View
     */
    public function reservationStatus()
    {
        $bookings = Booking::with('facility')
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        return view('reservation-status', compact('bookings'));
    }
}