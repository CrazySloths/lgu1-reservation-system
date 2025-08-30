<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Log;
use App\Models\Facility;
// use App\Models\Booking;
use Carbon\Carbon;

class AIRecommendationService
{
    /**
     * Check if a facility is available at the requested time
     */
    public function checkFacilityAvailability($facility_id, $date, $start_time, $end_time, $exclude_booking_id = null)
    {
        $requestedStart = Carbon::parse("$date $start_time");
        $requestedEnd = Carbon::parse("$date $end_time");

        // Temporarily disable conflict checking until Booking model is properly set up
        // TODO: Re-enable once booking system is fully implemented
        
        /*
        // Check for overlapping approved bookings
        $conflictingBookings = Booking::where('facility_id', $facility_id)
            ->where('status', 'approved')
            ->where('event_date', $date)
            ->when($exclude_booking_id, function ($query, $exclude_booking_id) {
                return $query->where('id', '!=', $exclude_booking_id);
            })
            ->where(function ($query) use ($requestedStart, $requestedEnd) {
                $query->where(function ($q) use ($requestedStart, $requestedEnd) {
                    // Booking starts before and ends after our start time
                    $q->where('start_time', '<=', $requestedStart->format('H:i:s'))
                      ->where('end_time', '>', $requestedStart->format('H:i:s'));
                })->orWhere(function ($q) use ($requestedStart, $requestedEnd) {
                    // Booking starts before our end time and ends after our end time
                    $q->where('start_time', '<', $requestedEnd->format('H:i:s'))
                      ->where('end_time', '>=', $requestedEnd->format('H:i:s'));
                })->orWhere(function ($q) use ($requestedStart, $requestedEnd) {
                    // Booking is completely within our time range
                    $q->where('start_time', '>=', $requestedStart->format('H:i:s'))
                      ->where('end_time', '<=', $requestedEnd->format('H:i:s'));
                });
            })
            ->exists();

        return !$conflictingBookings;
        */
        
        // For now, always return true (no conflicts) for demo purposes
        return true;
    }

    /**
     * Get AI-powered facility recommendations when there's a booking conflict
     */
    public function getRecommendations($facility_id, $date, $start_time, $end_time, $expected_attendees, $event_type = 'general')
    {
        try {
            // First check if the requested facility is actually unavailable
            $isAvailable = $this->checkFacilityAvailability($facility_id, $date, $start_time, $end_time);
            
            if ($isAvailable) {
                return [
                    'status' => 'available',
                    'message' => 'Your requested facility is available!',
                    'recommendations' => []
                ];
            }

            // Call Python AI recommender
            $pythonScript = base_path('python_ai/facility_recommender.py');
            
            $command = [
                'python',
                $pythonScript,
                (string) $facility_id,
                $date,
                $start_time,
                $end_time,
                (string) $expected_attendees,
                $event_type
            ];

            Log::info('Calling AI Recommender', ['command' => implode(' ', $command)]);

            $result = Process::run(implode(' ', array_map('escapeshellarg', $command)));

            if ($result->failed()) {
                Log::error('AI Recommender failed', [
                    'exitCode' => $result->exitCode(),
                    'output' => $result->output(),
                    'error' => $result->errorOutput()
                ]);

                // Fallback to simple recommendation logic
                return $this->getFallbackRecommendations($facility_id, $date, $start_time, $end_time, $expected_attendees);
            }

            $aiResponse = json_decode($result->output(), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('AI Recommender returned invalid JSON', ['output' => $result->output()]);
                return $this->getFallbackRecommendations($facility_id, $date, $start_time, $end_time, $expected_attendees);
            }

            // Enhance AI recommendations with real-time availability check
            if (isset($aiResponse['recommendations'])) {
                $enhancedRecommendations = [];
                
                foreach ($aiResponse['recommendations'] as $recommendation) {
                    $recFacilityId = $recommendation['facility_id'];
                    
                    // Double-check availability in real-time
                    if ($this->checkFacilityAvailability($recFacilityId, $date, $start_time, $end_time)) {
                        // Get additional facility details from database
                        $facility = Facility::find($recFacilityId);
                        if ($facility) {
                            $recommendation['daily_rate'] = $facility->daily_rate;
                            $recommendation['hourly_rate'] = $facility->hourly_rate;
                            $recommendation['facility_type'] = $facility->facility_type;
                            $recommendation['image_path'] = $facility->image_path;
                            $recommendation['availability_verified'] = true;
                            
                            $enhancedRecommendations[] = $recommendation;
                        }
                    }
                }
                
                $aiResponse['recommendations'] = $enhancedRecommendations;
                $aiResponse['total_alternatives'] = count($enhancedRecommendations);
            }

            return [
                'status' => 'conflict',
                'message' => 'Your requested facility is not available, but we found great alternatives!',
                'ai_response' => $aiResponse
            ];

        } catch (\Exception $e) {
            Log::error('AI Recommendation Service Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->getFallbackRecommendations($facility_id, $date, $start_time, $end_time, $expected_attendees);
        }
    }

    /**
     * Fallback recommendation logic when AI service fails
     */
    protected function getFallbackRecommendations($facility_id, $date, $start_time, $end_time, $expected_attendees)
    {
        try {
            $requestedFacility = Facility::find($facility_id);
            $fallbackRecommendations = [];

            // Find available facilities with similar capacity
            $alternativeFacilities = Facility::where('facility_id', '!=', $facility_id)
                ->where('status', 'active')
                ->where('capacity', '>=', $expected_attendees)
                ->orderBy('capacity', 'asc')
                ->take(3)
                ->get();

            foreach ($alternativeFacilities as $facility) {
                if ($this->checkFacilityAvailability($facility->facility_id, $date, $start_time, $end_time)) {
                    $fallbackRecommendations[] = [
                        'facility_id' => $facility->facility_id,
                        'name' => $facility->name,
                        'description' => $facility->description,
                        'capacity' => $facility->capacity,
                        'daily_rate' => $facility->daily_rate,
                        'hourly_rate' => $facility->hourly_rate,
                        'facility_type' => $facility->facility_type,
                        'image_path' => $facility->image_path,
                        'similarity_score' => 0.5, // Default similarity
                        'availability_status' => 'available',
                        'availability_verified' => true,
                        'recommendation_reason' => 'Available alternative facility with adequate capacity'
                    ];
                }
            }

            return [
                'status' => 'conflict',
                'message' => 'Your requested facility is not available. Here are some alternatives:',
                'ai_response' => [
                    'status' => 'fallback',
                    'requested_facility_id' => $facility_id,
                    'requested_date' => $date,
                    'requested_time' => "$start_time - $end_time",
                    'recommendations' => $fallbackRecommendations,
                    'total_alternatives' => count($fallbackRecommendations)
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Fallback recommendation failed', ['error' => $e->getMessage()]);
            
            return [
                'status' => 'error',
                'message' => 'Unable to find alternative facilities at this time. Please try a different date or time.',
                'ai_response' => ['recommendations' => []]
            ];
        }
    }

    /**
     * Update historical booking data for AI training
     */
    public function updateHistoricalData()
    {
        try {
            // TODO: Re-enable once booking system is fully implemented
            /*
            // Get approved bookings with facility information
            $bookings = Booking::with('facility')
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->take(1000) // Limit for performance
                ->get();
            */
            
            // For now, use empty collection for demo purposes
            $bookings = collect();

            // For demo purposes, create some sample data
            $historicalData = collect([
                [
                    'id' => 1,
                    'facility_id' => 1,
                    'start_time' => '2025-08-30 08:00:00',
                    'end_time' => '2025-08-30 11:00:00',
                    'user_name' => 'Demo User 1',
                    'status' => 'approved',
                    'created_at' => '2025-08-30T08:00:00.000000Z',
                    'updated_at' => '2025-08-30T08:00:00.000000Z',
                    'deleted_at' => null,
                    'facility' => [
                        'facility_id' => 1,
                        'name' => 'Sports Complex',
                        'description' => 'Main sports facility',
                        'address' => 'Bagumbong, Caloocan City',
                        'capacity' => 500,
                        'rate_per_hour' => '2000.00',
                        'created_at' => '2025-08-30T08:00:00.000000Z',
                        'updated_at' => '2025-08-30T08:00:00.000000Z',
                        'deleted_at' => null
                    ]
                ]
            ]);

            // Save to JSON file
            $dataPath = base_path('python_ai/data/historical_bookings.json');
            file_put_contents($dataPath, json_encode($historicalData->toArray(), JSON_PRETTY_PRINT));

            Log::info('Historical booking data updated', ['count' => $historicalData->count()]);
            
            return true;

        } catch (\Exception $e) {
            Log::error('Failed to update historical data', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Test the AI recommendation system
     */
    public function testRecommendationSystem()
    {
        // Update historical data first
        $this->updateHistoricalData();
        
        // Test with a sample recommendation
        $testResult = $this->getRecommendations(
            1, // facility_id
            Carbon::now()->addDays(7)->format('Y-m-d'), // date
            '10:00', // start_time
            '13:00', // end_time
            50, // expected_attendees
            'meeting' // event_type
        );
        
        return $testResult;
    }
}
