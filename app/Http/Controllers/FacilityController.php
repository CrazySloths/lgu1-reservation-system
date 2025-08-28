<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

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
     * Display the reservation status page for a specific user.
     *
     * @return \Illuminate\View\View
     */
    public function showUserBookings()
    {
        // Change this to the exact user name from your database to see their bookings.
        $current_user_name = 'test 1'; 
        
        $bookings = Booking::where('user_name', $current_user_name)
                             ->with('facility')
                             ->orderBy('created_at', 'desc')
                             ->get();
        
        return view('reservation-status', compact('bookings'));
    }

    // --- AI Integration Methods ---
    
    /**
     * Exports booking data to a JSON file for AI model training.
     *
     * @return void
     */
    private function exportBookingDataForAI()
    {
        try {
            // Get all approved bookings with their facility information
            $bookings = Booking::with('facility')
                                 ->where('status', 'approved')
                                 ->get();

            // If no approved bookings are found, write an empty array to the JSON file
            if ($bookings->isEmpty()) {
                $jsonData = '[]';
            } else {
                // Manually map and format the data to ensure clean JSON output
                $formattedBookings = $bookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'facility_id' => $booking->facility_id,
                        'start_time' => $booking->start_time->format('Y-m-d H:i:s'),
                        'end_time' => $booking->end_time->format('Y-m-d H:i:s'),
                        'user_name' => $booking->user_name,
                        'status' => $booking->status,
                        'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $booking->updated_at->format('Y-m-d H:i:s'),
                        'deleted_at' => $booking->deleted_at ? $booking->deleted_at->format('Y-m-d H:i:s') : null,
                        'facility' => $booking->facility->toArray(),
                    ];
                });
                $jsonData = $formattedBookings->toJson();
            }

            // Define the path for the data file inside the python_ai/data folder
            $filePath = base_path('python_ai/data/historical_bookings.json');

            // Check if the directory exists, if not, create it
            if (!File::isDirectory(dirname($filePath))) {
                File::makeDirectory(dirname($filePath), 0777, true, true);
            }

            // Write the JSON data to the file
            File::put($filePath, $jsonData);

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('AI data export failed: ' . $e->getMessage());
            // Write an empty array to prevent the Python script from crashing
            File::put(base_path('python_ai/data/historical_bookings.json'), '[]');
        }
    }
    
    /**
     * Displays the facility usage forecast.
     *
     * @return \Illuminate\View\View
     */
    public function forecast()
    {
        // Add this line to clear the config and ensure the latest data is used
        Artisan::call('cache:clear');
        
        // First, export the latest booking data to a JSON file
        $this->exportBookingDataForAI();

        // 1. Define the start and end dates for the forecast
        $start_date = now()->addDay()->format('Y-m-d');
        $end_date = now()->addMonths(6)->format('Y-m-d'); // Forecasting 6 months out

        // 2. Build the command to call the Python script
        $python_script_path = base_path('python_ai/forecast.py');
        $command = "python " . escapeshellarg($python_script_path) . " " . escapeshellarg($start_date) . " " . escapeshellarg($end_date);

        // 3. Execute the command and capture the output
        $output = shell_exec($command);

        // 4. Decode the JSON output from the Python script
        $forecast_data = json_decode($output, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $forecast_data = ['error' => 'Failed to decode forecast data. Raw output: ' . $output];
        }

        // 5. Pass the forecast data to a view
        return view('forecast', compact('forecast_data'));
    }
}