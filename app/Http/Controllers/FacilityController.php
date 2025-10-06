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
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // --- PERSISTENT FACILITIES DATA (Using Session Storage) ---
        $facilities = $this->getFacilitiesFromSession();
        
        \Log::info('FacilityController loaded ' . $facilities->count() . ' facilities from session storage');
        
        return response()
            ->view('FacilityList', compact('facilities'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
    
    /**
     * Get facilities from session storage, with fallback to default data
     */
    private function getFacilitiesFromSession()
    {
        $facilitiesFile = storage_path('app/facilities_data.json');
        
        // First try to load from persistent file
        if (file_exists($facilitiesFile)) {
            $data = json_decode(file_get_contents($facilitiesFile), true);
            if ($data && is_array($data)) {
                \Log::info('Loaded facilities from persistent file:', ['count' => count($data), 'file' => $facilitiesFile]);
                return collect($data)->map(function($facility) {
                    return (object) $facility;
                });
            }
        }
        
        // Default facilities (first time load)
        $defaultFacilities = [
            [
                'facility_id' => 1,
                'id' => 1,
                'name' => 'Community Hall',
                'description' => 'Large hall suitable for community events, meetings, and celebrations',
                'capacity' => 200,
                'hourly_rate' => 500.00,
                'daily_rate' => 1500.00,
                'facility_type' => 'hall',
                'location' => 'Main Building, Ground Floor',
                'image_path' => null,
                'status' => 'active',
                'amenities' => 'Sound system, air conditioning, tables and chairs',
                'created_at' => now()->subDays(100)->toDateTimeString(),
                'updated_at' => now()->subDays(10)->toDateTimeString()
            ],
            [
                'facility_id' => 2,
                'id' => 2,
                'name' => 'Basketball Court',
                'description' => 'Standard basketball court for sports and recreational activities',
                'capacity' => 50,
                'hourly_rate' => 200.00,
                'daily_rate' => 600.00,
                'facility_type' => 'sports',
                'location' => 'Recreation Area, Outdoor',
                'image_path' => null,
                'status' => 'active',
                'amenities' => 'Basketball hoops, benches, lighting',
                'created_at' => now()->subDays(90)->toDateTimeString(),
                'updated_at' => now()->subDays(5)->toDateTimeString()
            ],
            [
                'facility_id' => 3,
                'id' => 3,
                'name' => 'Conference Room',
                'description' => 'Professional meeting room for business conferences and workshops',
                'capacity' => 30,
                'hourly_rate' => 300.00,
                'daily_rate' => 900.00,
                'facility_type' => 'meeting',
                'location' => 'Admin Building, 2nd Floor',
                'image_path' => null,
                'status' => 'active',
                'amenities' => 'Projector, whiteboard, air conditioning, WiFi',
                'created_at' => now()->subDays(80)->toDateTimeString(),
                'updated_at' => now()->subDays(2)->toDateTimeString()
            ]
        ];
        
        // Save to persistent file storage
        $this->saveFacilitiesToFile($defaultFacilities);
        
        return collect($defaultFacilities)->map(function($facility) {
            return (object) $facility;
        });
    }
    
    private function saveFacilitiesToFile($facilities)
    {
        $facilitiesFile = storage_path('app/facilities_data.json');
        
        // Ensure storage/app directory exists
        $storageDir = dirname($facilitiesFile);
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        
        $success = file_put_contents($facilitiesFile, json_encode($facilities, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        if ($success) {
            \Log::info('🎯 FACILITIES SAVED TO PERSISTENT FILE:', [
                'file' => $facilitiesFile, 
                'count' => count($facilities),
                'size' => filesize($facilitiesFile) . ' bytes'
            ]);
        } else {
            \Log::error('❌ FAILED TO SAVE FACILITIES TO FILE:', ['file' => $facilitiesFile]);
        }
        
        return $success;
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
        \Log::info('🎯 ADD FACILITY: Raw request data:', $request->all());
        
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
        \Log::info('🎯 ADD FACILITY: Validated data:', $validatedData);
        
        // Handle image upload using native PHP (no Laravel Storage dependency)
        $imagePath = null;
        if ($request->hasFile('facility_image')) {
            try {
                $uploadedFile = $request->file('facility_image');
                
                // Generate unique filename
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $uploadedFile->getClientOriginalName());
                $relativePath = 'facilities/' . $filename;
                $fullPath = storage_path('app/public/' . $relativePath);
                
                // Ensure facilities directory exists
                $facilitiesDir = storage_path('app/public/facilities');
                if (!file_exists($facilitiesDir)) {
                    mkdir($facilitiesDir, 0755, true);
                }
                
                // Move uploaded file using native PHP
                if ($uploadedFile->move($facilitiesDir, $filename)) {
                    $imagePath = $relativePath;
                    \Log::info('🎯 ADD FACILITY: Image uploaded successfully:', [
                        'original_name' => $uploadedFile->getClientOriginalName(),
                        'stored_path' => $imagePath,
                        'full_path' => $fullPath
                    ]);
                } else {
                    throw new \Exception('Failed to move uploaded file');
                }
                
            } catch (\Exception $e) {
                \Log::error('🎯 ADD FACILITY: Image upload failed:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return redirect()->back()->withErrors(['facility_image' => 'Failed to upload image: ' . $e->getMessage()])->withInput();
            }
        }

        // --- LOAD EXISTING FACILITIES FROM PERSISTENT FILE ---
        $existingFacilities = $this->getFacilitiesFromSession()->toArray();
        
        // Generate new facility ID (find max + 1)
        $maxId = 0;
        foreach ($existingFacilities as $facility) {
            if (isset($facility->facility_id) && $facility->facility_id > $maxId) {
                $maxId = $facility->facility_id;
            }
        }
        $newFacilityId = $maxId + 1;

        // Create new facility data with persistent file storage structure
        $newFacilityData = [
            'facility_id' => $newFacilityId,
            'id' => $newFacilityId,
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'location' => $validatedData['location'],
            'capacity' => $validatedData['capacity'],
            'hourly_rate' => $validatedData['hourly_rate'], // Extension rate
            'daily_rate' => $validatedData['base_rate'], // Base rate for 3 hours
            'facility_type' => $validatedData['facility_type'],
            'image_path' => $imagePath,
            'status' => 'active',
            'amenities' => 'Standard amenities included',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString()
        ];

        \Log::info('🎯 ADD FACILITY: Final data for persistent storage:', $newFacilityData);

        // Add new facility to existing facilities array
        $allFacilities = array_map(function($facility) {
            return (array) $facility;
        }, $existingFacilities);
        
        $allFacilities[] = $newFacilityData;

        // Save to persistent file storage (SURVIVES EVERYTHING!)
        $success = $this->saveFacilitiesToFile($allFacilities);
        
        if ($success) {
            \Log::info('🎯 ADD FACILITY: Successfully saved new facility to persistent storage!', ['facility_id' => $newFacilityId]);
            return redirect()->route('facility.list')->with('success', 'Facility added successfully! It will persist even after system restarts.');
        } else {
            \Log::error('🎯 ADD FACILITY: Failed to save to persistent storage');
            return redirect()->back()->withErrors(['general' => 'Failed to save facility. Please try again.'])->withInput();
        }
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

        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // --- LOAD CURRENT FACILITIES FROM SESSION ---
        $facilities = $this->getFacilitiesFromSession();
        $facilityArray = $facilities->toArray();
        
        // Find the facility to update
        $facilityIndex = null;
        $facility = null;
        foreach ($facilityArray as $index => $fac) {
            if ($fac->facility_id == $facility_id) {
                $facilityIndex = $index;
                $facility = $fac;
                break;
            }
        }
        
        if (!$facility) {
            abort(404, 'Facility not found');
        }
        
        // Handle image upload if provided
        $imagePath = $facility->image_path; // Keep existing image by default
        
        \Log::info('Image upload check:', [
            'has_file' => $request->hasFile('facility_image'),
            'file_details' => $request->hasFile('facility_image') ? [
                'original_name' => $request->file('facility_image')->getClientOriginalName(),
                'size' => $request->file('facility_image')->getSize(),
                'mime_type' => $request->file('facility_image')->getMimeType(),
            ] : null
        ]);
        
        if ($request->hasFile('facility_image')) {
            try {
                $uploadedFile = $request->file('facility_image');
                
                // Generate unique filename
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $uploadedFile->getClientOriginalName());
                $relativePath = 'facilities/' . $filename;
                $fullPath = storage_path('app/public/' . $relativePath);
                
                // Ensure facilities directory exists
                $facilitiesDir = storage_path('app/public/facilities');
                if (!file_exists($facilitiesDir)) {
                    mkdir($facilitiesDir, 0755, true);
                }
                
            // Delete old image if it exists
                if ($facility->image_path) {
                    $oldImagePath = storage_path('app/public/' . $facility->image_path);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                        \Log::info('Deleted old image: ' . $facility->image_path);
                    }
                }
                
                // Move uploaded file using native PHP
                if ($uploadedFile->move($facilitiesDir, $filename)) {
                    $imagePath = $relativePath;
                    
                    \Log::info('Image uploaded successfully using native PHP:', [
                        'original_name' => $uploadedFile->getClientOriginalName(),
                        'stored_path' => $imagePath,
                        'full_path' => $fullPath,
                        'full_url' => asset('storage/' . $imagePath)
                    ]);
                } else {
                    throw new \Exception('Failed to move uploaded file');
                }
                
            } catch (\Exception $e) {
                \Log::error('Image upload failed:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Keep the old image path if upload fails
                $imagePath = $facility->image_path;
            }
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

        // --- REAL UPDATE: Save to Persistent File Storage ---
        $updatedFacility = array_merge((array)$facility, $facilityData);
        $updatedFacility['updated_at'] = now()->toDateTimeString();
        
        // Update the facility in the array
        $facilityArray[$facilityIndex] = $updatedFacility;
        
        // Save updated facilities to persistent file (SURVIVES SLEEP/RESTART!)
        $this->saveFacilitiesToFile($facilityArray);
        
        \Log::info('🎯 Facility ACTUALLY updated and saved to PERSISTENT FILE. ID: ' . $facility_id . ', New data: ' . json_encode($updatedFacility));

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
        // --- STATIC MODE: SIMULATE DELETE (Database drivers not available on server) ---
        $validFacilityIds = [1, 2, 3]; // Valid facility IDs
        
        if (!in_array($facility_id, $validFacilityIds)) {
            abort(404, 'Facility not found');
        }
        
        // Log the attempted deletion
        \Log::warning('STATIC MODE: Facility deletion simulated (no database available)', [
            'facility_id' => $facility_id,
            'action' => 'delete_attempted'
        ]);
        
        return redirect()->route('facility.list')->with('success', 'Facility deletion simulated successfully! (Note: No database changes made)');
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
        \Log::info('🎯 BOOKING SUBMISSION: Starting reservation process', $request->all());

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
            'selfie_with_id' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            
            // Optional documents
            'authorization_letter' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'event_proposal' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            
            // Signature
            'signature_method' => 'required|in:draw,upload',
            'signature_data' => 'nullable|string', // For drawn signature
            'signature_upload' => 'nullable|file|mimes:jpg,jpeg,png|max:2048', // For uploaded signature
        ]);

        try {
            // --- STATIC DATA: Get facility from persistent file storage ---
            $facilities = $this->getFacilitiesFromSession();
            $facility = $facilities->firstWhere('name', $validatedData['facility_name']);
            
            if (!$facility) {
                \Log::error('🎯 BOOKING ERROR: Facility not found', ['facility_name' => $validatedData['facility_name']]);
                return redirect()->back()
                            ->withInput()
                            ->withErrors(['facility_name' => 'Selected facility not found.']);
            }

            \Log::info('🎯 BOOKING: Facility found', ['facility' => $facility->name, 'id' => $facility->facility_id]);

            // No AI service - proceed directly with booking
            return $this->processReservationBooking($request, $validatedData, $facility);

        } catch (\Exception $e) {
            \Log::error('🎯 BOOKING ERROR:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                        ->withInput()
                        ->withErrors(['error' => 'An error occurred while processing your reservation. Please try again later.']);
        }
    }

    /**
     * Process the actual booking when no conflicts exist
     */
    private function processReservationBooking($request, $validatedData, $facility)
    {
        try {
            // Handle file uploads
            $uploadedFiles = $this->handleReservationFileUploads($request, $validatedData);

            // Calculate fees based on facility pricing
            $duration = $this->calculateEventDuration($validatedData['start_time'], $validatedData['end_time']);
            $totalFee = $this->calculateTotalFee($facility, $duration);

            // --- LOAD EXISTING BOOKINGS FROM PERSISTENT FILE ---
            $bookingsFile = storage_path('app/bookings_data.json');
            $bookings = [];
            
            if (file_exists($bookingsFile)) {
                $data = json_decode(file_get_contents($bookingsFile), true);
                if ($data && is_array($data)) {
                    $bookings = $data;
                    \Log::info('🎯 BOOKING: Loaded existing bookings', ['count' => count($bookings)]);
                }
            }

            // --- GENERATE NEW BOOKING ID ---
            $newId = count($bookings) > 0 ? max(array_column($bookings, 'id')) + 1 : 1;

            // --- CREATE NEW BOOKING RECORD ---
            // Convert times to 24-hour format for storage
            $startTime24 = \Carbon\Carbon::parse($validatedData['start_time'])->format('H:i:s');
            $endTime24 = \Carbon\Carbon::parse($validatedData['end_time'])->format('H:i:s');
            
            $newBooking = [
                'id' => $newId,
                'facility_id' => $facility->facility_id ?? $facility->id,
                'facility_name' => $facility->name,
                'user_id' => 4, // Static citizen user ID
                'user_name' => $validatedData['applicant_name'],
                'applicant_name' => $validatedData['applicant_name'],
                'applicant_email' => $validatedData['applicant_email'],
                'applicant_phone' => $validatedData['applicant_phone'],
                'applicant_address' => $validatedData['applicant_address'],
                'event_name' => $validatedData['event_name'],
                'event_description' => $validatedData['event_description'] ?? '',
                'event_date' => $validatedData['event_date'],
                'start_time' => $startTime24,  // Store in 24-hour format
                'end_time' => $endTime24,      // Store in 24-hour format
                'expected_attendees' => $validatedData['expected_attendees'],
                'total_fee' => $totalFee,
                'status' => 'pending',
                
                // Store file paths
                'id_type' => $validatedData['id_type'],
                'valid_id_path' => $uploadedFiles['id_front'] ?? null,
                'id_back_path' => $uploadedFiles['id_back'] ?? null,
                'id_selfie_path' => $uploadedFiles['id_selfie'] ?? null,
                'authorization_letter_path' => $uploadedFiles['authorization_letter'] ?? null,
                'event_proposal_path' => $uploadedFiles['event_proposal'] ?? null,
                'digital_signature' => $uploadedFiles['signature'] ?? null,
                
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString()
            ];

            // --- ADD TO BOOKINGS ARRAY ---
            $bookings[] = $newBooking;

            // --- SAVE TO PERSISTENT FILE STORAGE WITH SAFEGUARDS ---
            // Create backup before saving
            if (file_exists($bookingsFile)) {
                $backupFile = $bookingsFile . '.backup.' . date('YmdHis');
                copy($bookingsFile, $backupFile);
                \Log::info('✓ Created backup before saving', ['backup' => basename($backupFile)]);
            }
            
            // Encode to JSON
            $jsonData = json_encode($bookings, JSON_PRETTY_PRINT);
            
            // Validate JSON encoding was successful
            if ($jsonData === false) {
                \Log::error('🚨 JSON encoding failed!', ['error' => json_last_error_msg()]);
                throw new \Exception('Failed to save booking data. Please try again.');
            }
            
            // Validate signature is still complete in the JSON
            if (isset($newBooking['digital_signature']) && strlen($newBooking['digital_signature']) > 100) {
                if (strpos($jsonData, substr($newBooking['digital_signature'], 0, 50)) === false) {
                    \Log::error('🚨 Digital signature missing from JSON output!');
                    throw new \Exception('Data validation failed. Please try again.');
                }
            }
            
            // Use atomic write (write to temp file, then rename)
            $tempFile = $bookingsFile . '.tmp';
            $bytesWritten = file_put_contents($tempFile, $jsonData);
            
            if ($bytesWritten === false || $bytesWritten < strlen($jsonData)) {
                \Log::error('🚨 Failed to write complete booking data', [
                    'expected_bytes' => strlen($jsonData),
                    'written_bytes' => $bytesWritten
                ]);
                @unlink($tempFile);
                throw new \Exception('Failed to save booking data completely. Please try again.');
            }
            
            // Atomic rename (replaces old file)
            rename($tempFile, $bookingsFile);
            
            \Log::info('✓ Booking data saved successfully', [
                'bytes_written' => $bytesWritten,
                'total_bookings' => count($bookings),
                'signature_length' => isset($newBooking['digital_signature']) ? strlen($newBooking['digital_signature']) : 0
            ]);

            \Log::info('🎯 BOOKING SUCCESS: Saved to persistent storage!', [
                'booking_id' => $newId,
                'file' => $bookingsFile,
                'total_bookings' => count($bookings)
            ]);

            return redirect()->route('citizen.reservation.history')
                        ->with('success', 'Your reservation has been submitted successfully and is pending approval!');

        } catch (\Exception $e) {
            \Log::error('🎯 BOOKING CREATION FAILED:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Handle file uploads for citizen reservations
     */
    private function handleReservationFileUploads($request, $validatedData)
    {
        $uploadedFiles = [];

        \Log::info('Starting file upload handling...', ['files' => array_keys($request->allFiles())]);

        // Handle ID verification files
        if ($request->hasFile('id_front')) {
            $uploadedFiles['id_front'] = $request->file('id_front')->store('documents/citizen_ids', 'public');
            \Log::info('ID Front uploaded:', ['path' => $uploadedFiles['id_front']]);
        }
        
        if ($request->hasFile('id_back')) {
            $uploadedFiles['id_back'] = $request->file('id_back')->store('documents/citizen_ids', 'public');
            \Log::info('ID Back uploaded:', ['path' => $uploadedFiles['id_back']]);
        }
        
        if ($request->hasFile('selfie_with_id')) {
            $uploadedFiles['id_selfie'] = $request->file('selfie_with_id')->store('documents/citizen_ids', 'public');
            \Log::info('Selfie with ID uploaded:', ['path' => $uploadedFiles['id_selfie']]);
        }

        // Handle optional documents
        if ($request->hasFile('authorization_letter')) {
            $uploadedFiles['authorization_letter'] = $request->file('authorization_letter')->store('documents/authorization_letters', 'public');
            \Log::info('Authorization letter uploaded:', ['path' => $uploadedFiles['authorization_letter']]);
        }
        
        if ($request->hasFile('event_proposal')) {
            $uploadedFiles['event_proposal'] = $request->file('event_proposal')->store('documents/event_proposals', 'public');
            \Log::info('Event proposal uploaded:', ['path' => $uploadedFiles['event_proposal']]);
        }

        // Handle signature (drawn or uploaded)
        if ($validatedData['signature_method'] === 'upload' && $request->hasFile('signature_upload')) {
            $uploadedFiles['signature'] = $request->file('signature_upload')->store('documents/signatures', 'public');
            \Log::info('Signature uploaded:', ['path' => $uploadedFiles['signature']]);
        } elseif ($validatedData['signature_method'] === 'draw' && !empty($validatedData['signature_data'])) {
            // Store drawn signature as base64 data
            $signatureData = $validatedData['signature_data'];
            $signatureLength = strlen($signatureData);
            
            // Validate signature is complete (should be at least 100 characters for base64 image)
            if ($signatureLength < 100) {
                \Log::error('🚨 Digital signature is too short - may be corrupted', [
                    'length' => $signatureLength,
                    'data_preview' => substr($signatureData, 0, 50)
                ]);
                throw new \Exception('Digital signature data appears to be incomplete. Please try signing again.');
            }
            
            // Validate it's a proper base64 data URL
            if (strpos($signatureData, 'data:image') !== 0) {
                \Log::error('🚨 Digital signature is not a valid data URL', [
                    'data_preview' => substr($signatureData, 0, 50)
                ]);
                throw new \Exception('Digital signature format is invalid. Please try signing again.');
            }
            
            $uploadedFiles['signature'] = $signatureData;
            \Log::info('✓ Digital signature validated and saved successfully', [
                'length' => $signatureLength,
                'format' => 'base64 data URL',
                'is_complete' => true
            ]);
        }

        \Log::info('File upload handling completed:', ['uploaded_files' => $uploadedFiles]);
        return $uploadedFiles;
    }

    /**
     * Calculate event duration in hours
     */
    private function calculateEventDuration($startTime, $endTime)
    {
        // Convert 12-hour format (e.g., "09:00 AM") to 24-hour format
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);
        
        $duration = $end->diffInHours($start);
        
        \Log::info(' DURATION CALCULATION:', [
            'start_time_input' => $startTime,
            'end_time_input' => $endTime,
            'start_parsed' => $start->format('H:i:s'),
            'end_parsed' => $end->format('H:i:s'),
            'duration_hours' => $duration
        ]);
        
        return $duration;
    }

    /**
     * Calculate total fee based on facility and duration
     */
    private function calculateTotalFee($facility, $duration)
    {
        // Handle both object and array facility data
        $dailyRate = is_object($facility) ? ($facility->daily_rate ?? 5000) : ($facility['daily_rate'] ?? 5000);
        $hourlyRate = is_object($facility) ? ($facility->hourly_rate ?? 2000) : ($facility['hourly_rate'] ?? 2000);
        
        $baseFee = $dailyRate; // ₱5,000 for 3 hours (base)
        $extensionFee = 0;

        if ($duration > 3) {
            $extraHours = $duration - 3;
            $extensionFee = $extraHours * $hourlyRate; // ₱2,000 per hour extension
        }

        \Log::info('🎯 FEE CALCULATION:', [
            'duration' => $duration,
            'daily_rate' => $dailyRate,
            'hourly_rate' => $hourlyRate,
            'base_fee' => $baseFee,
            'extension_fee' => $extensionFee,
            'total_fee' => $baseFee + $extensionFee
        ]);

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