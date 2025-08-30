<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use App\Models\Booking;
use App\Models\User;
use App\Services\AIRecommendationService;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FacilityController extends Controller
{
    /**
     * Display a listing of the facilities.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Force fresh data from database
        $facilities = Facility::orderBy('facility_id', 'desc')->get();
        
        \Log::info('Loading facilities page with ' . $facilities->count() . ' facilities');
        
        return response()
            ->view('FacilityList', compact('facilities'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
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
        $facilities = Facility::where('status', 'active')->get();
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
        // Debug: Log the raw request data
        \Log::info('Raw request data:', $request->all());
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'base_rate' => 'required|numeric|min:5000',
            'hourly_rate' => 'required|numeric|min:2000',
            'facility_type' => 'required|string',
            'facility_image' => 'required|image|mimes:jpeg,jpg,png|max:5120', // 5MB max
        ], [
            'base_rate.min' => 'Base rate must be at least ₱5,000 (minimum LGU requirement)',
            'hourly_rate.min' => 'Extension rate must be at least ₱2,000 per hour (minimum LGU requirement)',
        ]);

        // Debug: Log the validated data
        \Log::info('Validated data:', $validatedData);
        
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('facility_image')) {
            $imagePath = $request->file('facility_image')->store('facilities', 'public');
        }

        // Set facility data with new pricing structure (based on interview findings)
        $facilityData = [
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'capacity' => $validatedData['capacity'],
            'hourly_rate' => $validatedData['hourly_rate'], // Extension rate (₱2,000/hour)
            'daily_rate' => $validatedData['base_rate'], // Base rate for 3 hours (₱5,000)
            'facility_type' => $validatedData['facility_type'],
            'image_path' => $imagePath,
            'status' => 'active',
            'amenities' => json_encode([]), // Empty JSON array
            'operating_hours_start' => '08:00:00',
            'operating_hours_end' => '17:00:00',
            'latitude' => 0,
            'longitude' => 0,
        ];

        \Log::info('Final data for insertion:', $facilityData);

        Facility::create($facilityData);

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
        // Debug: Log the raw request data
        \Log::info('Update raw request data:', $request->all());
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'base_rate' => 'required|numeric|min:5000',
            'hourly_rate' => 'required|numeric|min:2000',
            'facility_type' => 'required|string',
            'facility_image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // 5MB max, optional for updates
        ], [
            'base_rate.min' => 'Base rate must be at least ₱5,000 (minimum LGU requirement)',
            'hourly_rate.min' => 'Extension rate must be at least ₱2,000 per hour (minimum LGU requirement)',
        ]);

        // Debug: Log the validated data
        \Log::info('Update validated data:', $validatedData);

        $facility = Facility::findOrFail($facility_id);
        
        // Handle image upload if provided
        $imagePath = $facility->image_path; // Keep existing image by default
        if ($request->hasFile('facility_image')) {
            // Delete old image if it exists
            if ($facility->image_path && \Storage::disk('public')->exists($facility->image_path)) {
                \Storage::disk('public')->delete($facility->image_path);
            }
            // Store new image
            $imagePath = $request->file('facility_image')->store('facilities', 'public');
        }

        // Set facility data with new pricing structure (based on interview findings)
        $facilityData = [
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'capacity' => $validatedData['capacity'],
            'hourly_rate' => $validatedData['hourly_rate'], // Extension rate (₱2,000/hour)
            'daily_rate' => $validatedData['base_rate'], // Base rate for 3 hours (₱5,000)
            'facility_type' => $validatedData['facility_type'],
            'image_path' => $imagePath,
            'status' => 'active',
            'amenities' => json_encode([]), // Empty JSON array
            'operating_hours_start' => '08:00:00',
            'operating_hours_end' => '17:00:00',
            'latitude' => 0,
            'longitude' => 0,
        ];

        \Log::info('Update final data for update:', $facilityData);

        $facility->update($facilityData);

        \Log::info('Facility updated successfully. ID: ' . $facility_id . ', New data: ' . json_encode($facilityData));

        // Add cache-busting parameter and session flash to ensure fresh data
        return redirect()->route('facility.list', ['refresh' => time()])->with('success', 'Facility updated successfully!');
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

    /**
     * Store a new reservation request (based on capstone requirements).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeReservation(Request $request)
    {
        // Debug: Log the raw request data
        \Log::info('New Reservation request data:', $request->all());
        
        $validatedData = $request->validate([
            'facility_id' => 'required|string',
            'applicant_name' => 'required|string|max:255',
            'organization' => 'nullable|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'event_name' => 'required|string|max:255',
            'event_description' => 'required|string',
            'event_date' => 'required|date|after:today',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'expected_participants' => 'required|integer|min:1',
            'event_type' => 'required|string',
            'equipment' => 'nullable|array',
            'chairs_quantity' => 'nullable|integer|min:0',
            'tables_quantity' => 'nullable|integer|min:0',
            'sound_system_type' => 'nullable|string',
            'valid_id' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'authorization_letter' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'event_proposal' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'terms_accepted' => 'required|accepted',
            'digital_signature' => 'required|string|max:255',
        ]);

        // Debug: Log the validated data
        \Log::info('New Reservation validated data:', $validatedData);

        try {
            // Handle file uploads
            $validIdPath = null;
            $authLetterPath = null;
            $eventProposalPath = null;

            if ($request->hasFile('valid_id')) {
                $validIdPath = $request->file('valid_id')->store('documents/valid_ids', 'public');
            }

            if ($request->hasFile('authorization_letter')) {
                $authLetterPath = $request->file('authorization_letter')->store('documents/authorization_letters', 'public');
            }

            if ($request->hasFile('event_proposal')) {
                $eventProposalPath = $request->file('event_proposal')->store('documents/event_proposals', 'public');
            }

            // Calculate total fee (this would integrate with Payment Service microservice)
            $baseFee = 2000; // Base facility rental fee
            $equipmentFee = 0;
            
            if ($validatedData['chairs_quantity'] ?? 0 > 0) {
                $equipmentFee += ($validatedData['chairs_quantity'] ?? 0) * 5;
            }
            if ($validatedData['tables_quantity'] ?? 0 > 0) {
                $equipmentFee += ($validatedData['tables_quantity'] ?? 0) * 25;
            }
            if (!empty($validatedData['sound_system_type'])) {
                $equipmentFee += $validatedData['sound_system_type'] === 'basic' ? 500 : 1000;
            }

            $totalFee = $baseFee + $equipmentFee;

            // Create the reservation record
            $reservationData = [
                'facility_id' => $validatedData['facility_id'],
                'applicant_name' => $validatedData['applicant_name'],
                'organization' => $validatedData['organization'],
                'contact_number' => $validatedData['contact_number'],
                'email' => $validatedData['email'],
                'event_name' => $validatedData['event_name'],
                'event_description' => $validatedData['event_description'],
                'event_date' => $validatedData['event_date'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'expected_participants' => $validatedData['expected_participants'],
                'event_type' => $validatedData['event_type'],
                'equipment_requested' => json_encode($validatedData['equipment'] ?? []),
                'chairs_quantity' => $validatedData['chairs_quantity'] ?? 0,
                'tables_quantity' => $validatedData['tables_quantity'] ?? 0,
                'sound_system_type' => $validatedData['sound_system_type'],
                'valid_id_path' => $validIdPath,
                'authorization_letter_path' => $authLetterPath,
                'event_proposal_path' => $eventProposalPath,
                'digital_signature' => $validatedData['digital_signature'],
                'total_fee' => $totalFee,
                'status' => 'pending', // Start with pending status for multi-level approval
                'submission_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            \Log::info('Final reservation data for insertion:', $reservationData);

            // For now, we'll use the existing Booking model
            // In a full microservices implementation, this would call the Booking Service
            Booking::create([
                'facility_id' => $this->mapFacilityNameToId($validatedData['facility_id']),
                'user_name' => $validatedData['applicant_name'],
                'event_name' => $validatedData['event_name'],
                'booking_date' => $validatedData['event_date'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'status' => 'pending',
                'notes' => $validatedData['event_description'],
            ]);

            return redirect()->route('reservations.status')
                           ->with('success', 'Reservation submitted successfully! Your request is now pending approval.');

        } catch (\Exception $e) {
            \Log::error('Reservation submission error:', ['error' => $e->getMessage()]);
            
            return redirect()->back()
                           ->withInput()
                           ->withErrors(['error' => 'Failed to submit reservation. Please try again.']);
        }
    }

    /**
     * Map facility slug to actual facility ID
     *
     * @param string $facilitySlug
     * @return int
     */
    private function mapFacilityNameToId($facilitySlug)
    {
        $facilityMapping = [
            'buena_park' => 1,
            'bulwagan' => 2,
            'caloocan_sports_complex' => 3,
            'pacquiao_court' => 4,
        ];

        return $facilityMapping[$facilitySlug] ?? 1;
    }

    /**
     * AI-Enhanced Reservation with Conflict Detection and Recommendations
     * Used by the Citizen Portal
     */
    public function storeReservationWithAI(Request $request)
    {
        // Log the request for debugging
        \Log::info('AI-Enhanced Reservation request:', $request->all());

        // Validate the request
        $validatedData = $request->validate([
            'facility_name' => 'required|string',
            'applicant_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            'applicant_phone' => 'required|string|max:20',
            'applicant_address' => 'required|string|max:500',
            'event_name' => 'required|string|max:255',
            'event_description' => 'nullable|string',
            'event_date' => 'required|date|after:today',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'expected_attendees' => 'required|integer|min:1',
            
            // ID verification files
            'id_type' => 'required|string',
            'id_front' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'id_back' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'id_selfie' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            
            // Optional documents
            'authorization_letter' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'event_proposal' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            
            // Signature
            'signature_method' => 'required|in:draw,upload',
            'signature_data' => 'nullable|string', // For drawn signature
            'signature_upload' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // For uploaded signature
        ]);

        try {
            // Get facility by name
            $facility = Facility::where('name', $validatedData['facility_name'])->first();
            if (!$facility) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Selected facility not found.'
                ], 404);
            }

            // Initialize AI Recommendation Service
            $aiService = new AIRecommendationService();

            // Check for booking conflicts and get AI recommendations if needed
            $aiResponse = $aiService->getRecommendations(
                $facility->facility_id,
                $validatedData['event_date'],
                $validatedData['start_time'],
                $validatedData['end_time'],
                $validatedData['expected_attendees'],
                'general'
            );

            // If there are conflicts, return recommendations instead of booking
            if ($aiResponse['status'] === 'conflict') {
                return response()->json([
                    'status' => 'conflict',
                    'message' => $aiResponse['message'],
                    'recommendations' => $aiResponse['ai_response']['recommendations'] ?? [],
                    'requested_facility' => [
                        'id' => $facility->facility_id,
                        'name' => $facility->name,
                        'requested_date' => $validatedData['event_date'],
                        'requested_time' => $validatedData['start_time'] . ' - ' . $validatedData['end_time']
                    ]
                ]);
            }

            // No conflicts detected, proceed with booking
            return $this->processReservationBooking($validatedData, $facility, $aiService);

        } catch (\Exception $e) {
            \Log::error('AI-Enhanced Reservation Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while processing your reservation. Please try again later.'
            ], 500);
        }
    }

    /**
     * Process the actual booking when no conflicts exist
     */
    private function processReservationBooking($validatedData, $facility, $aiService)
    {
        try {
            // Handle file uploads
            $uploadedFiles = $this->handleReservationFileUploads($validatedData);

            // Calculate fees based on facility pricing
            $duration = $this->calculateEventDuration($validatedData['start_time'], $validatedData['end_time']);
            $totalFee = $this->calculateTotalFee($facility, $duration);

            // Create the booking record
            $booking = Booking::create([
                'facility_id' => $facility->facility_id,
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? $validatedData['applicant_name'],
                'applicant_name' => $validatedData['applicant_name'],
                'applicant_email' => $validatedData['applicant_email'],
                'applicant_phone' => $validatedData['applicant_phone'],
                'applicant_address' => $validatedData['applicant_address'],
                'event_name' => $validatedData['event_name'],
                'event_description' => $validatedData['event_description'],
                'event_date' => $validatedData['event_date'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'expected_attendees' => $validatedData['expected_attendees'],
                'total_fee' => $totalFee,
                'status' => 'pending',
                
                // Store file paths
                'valid_id_path' => $uploadedFiles['id_front'] ?? null,
                'authorization_letter_path' => $uploadedFiles['authorization_letter'] ?? null,
                'event_proposal_path' => $uploadedFiles['event_proposal'] ?? null,
                'digital_signature' => $uploadedFiles['signature'] ?? null,
            ]);

            // Update AI training data
            $aiService->updateHistoricalData();

            \Log::info('Booking created successfully:', ['booking_id' => $booking->id]);

            return response()->json([
                'status' => 'success',
                'message' => 'Your reservation has been submitted successfully and is pending approval.',
                'booking_id' => $booking->id,
                'total_fee' => $totalFee,
                'facility' => [
                    'name' => $facility->name,
                    'location' => $facility->location
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Booking creation failed:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Handle file uploads for citizen reservations
     */
    private function handleReservationFileUploads($validatedData)
    {
        $uploadedFiles = [];

        // Handle ID verification files
        if (request()->hasFile('id_front')) {
            $uploadedFiles['id_front'] = request()->file('id_front')->store('documents/citizen_ids', 'public');
        }
        
        if (request()->hasFile('id_back')) {
            $uploadedFiles['id_back'] = request()->file('id_back')->store('documents/citizen_ids', 'public');
        }
        
        if (request()->hasFile('id_selfie')) {
            $uploadedFiles['id_selfie'] = request()->file('id_selfie')->store('documents/citizen_ids', 'public');
        }

        // Handle optional documents
        if (request()->hasFile('authorization_letter')) {
            $uploadedFiles['authorization_letter'] = request()->file('authorization_letter')->store('documents/authorization_letters', 'public');
        }
        
        if (request()->hasFile('event_proposal')) {
            $uploadedFiles['event_proposal'] = request()->file('event_proposal')->store('documents/event_proposals', 'public');
        }

        // Handle signature (drawn or uploaded)
        if ($validatedData['signature_method'] === 'upload' && request()->hasFile('signature_upload')) {
            $uploadedFiles['signature'] = request()->file('signature_upload')->store('documents/signatures', 'public');
        } elseif ($validatedData['signature_method'] === 'draw' && !empty($validatedData['signature_data'])) {
            // Store drawn signature as base64 data
            $uploadedFiles['signature'] = $validatedData['signature_data'];
        }

        return $uploadedFiles;
    }

    /**
     * Calculate event duration in hours
     */
    private function calculateEventDuration($startTime, $endTime)
    {
        $start = \Carbon\Carbon::createFromTimeString($startTime);
        $end = \Carbon\Carbon::createFromTimeString($endTime);
        return $end->diffInHours($start);
    }

    /**
     * Calculate total fee based on facility and duration
     */
    private function calculateTotalFee($facility, $duration)
    {
        $baseFee = $facility->daily_rate; // ₱5,000 for 3 hours
        $extensionFee = 0;

        if ($duration > 3) {
            $extraHours = $duration - 3;
            $extensionFee = $extraHours * $facility->hourly_rate; // ₱2,000 per hour
        }

        return $baseFee + $extensionFee;
    }

    /**
     * API endpoint to get AI recommendations for a specific facility and time
     */
    public function getAIRecommendations(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|integer',
            'event_date' => 'required|date',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'expected_attendees' => 'required|integer|min:1',
            'event_type' => 'nullable|string'
        ]);

        try {
            $aiService = new AIRecommendationService();
            
            $recommendations = $aiService->getRecommendations(
                $request->facility_id,
                $request->event_date,
                $request->start_time,
                $request->end_time,
                $request->expected_attendees,
                $request->event_type ?? 'general'
            );

            return response()->json($recommendations);

        } catch (\Exception $e) {
            \Log::error('AI Recommendations API Error:', [
                'message' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Unable to fetch recommendations at this time.'
            ], 500);
        }
    }

    /**
     * Test the AI recommendation system (Admin only)
     */
    public function testAISystem()
    {
        try {
            $aiService = new AIRecommendationService();
            $testResult = $aiService->testRecommendationSystem();

            return response()->json([
                'status' => 'success',
                'message' => 'AI System test completed',
                'test_result' => $testResult
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'AI System test failed: ' . $e->getMessage()
            ], 500);
        }
    }
}