<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Facility;
use Carbon\Carbon;

class AITestBookingSeeder extends Seeder
{
    /**
     * Run the database seeder to create historical bookings for AI testing.
     */
    public function run(): void
    {
        $this->command->info('Creating historical bookings for AI testing...');

        // Get the test citizen user
        $citizen = User::where('email', 'citizen.test@email.com')->first();
        
        if (!$citizen) {
            $this->command->warn('Test citizen user not found. Please run AdminUserSeeder first.');
            return;
        }

        // Get all facilities
        $facilities = Facility::all();
        
        if ($facilities->isEmpty()) {
            $this->command->warn('No facilities found. Please add some facilities first.');
            return;
        }

        $bookingCount = 0;
        $eventTypes = [
            'Birthday Celebration',
            'Wedding Reception',
            'Corporate Event',
            'Community Meeting',
            'Sports Tournament',
            'Cultural Festival',
            'Graduation Party',
            'Anniversary Celebration',
            'Business Conference',
            'Family Reunion',
            'Christmas Party',
            'New Year Celebration',
            'Charity Event',
            'Product Launch',
            'Training Seminar'
        ];

        // Create bookings for the past 90 days (more historical data)
        for ($daysAgo = 90; $daysAgo >= 1; $daysAgo--) {
            // Randomly decide if there should be a booking on this day (60% chance)
            if (rand(1, 100) <= 60) {
                $numBookings = rand(1, 3); // 1-3 bookings per day
                
                for ($i = 0; $i < $numBookings; $i++) {
                    $facility = $facilities->random();
                    $eventType = $eventTypes[array_rand($eventTypes)];
                    $eventDate = Carbon::now()->subDays($daysAgo);
                    
                    // Random times
                    $startHour = rand(8, 18);
                    $duration = rand(2, 5); // 2-5 hours
                    $endHour = min($startHour + $duration, 22);
                    
                    $attendees = rand(20, 150);
                    $baseFee = 5000;
                    $extraHours = max(0, $duration - 3);
                    $totalFee = $baseFee + ($extraHours * 2000);
                    
                    Booking::create([
                        'facility_id' => $facility->facility_id,
                        'user_id' => $citizen->id,
                        'user_name' => $citizen->name,
                        'applicant_name' => $citizen->full_name,
                        'applicant_email' => $citizen->email,
                        'applicant_phone' => '0912345678' . rand(0, 9),
                        'applicant_address' => 'Test Address, City',
                        'event_name' => $eventType,
                        'event_description' => 'Test event for AI forecasting',
                        'event_date' => $eventDate->format('Y-m-d'),
                        'start_time' => sprintf('%02d:00', $startHour),
                        'end_time' => sprintf('%02d:00', $endHour),
                        'expected_attendees' => $attendees,
                        'total_fee' => $totalFee,
                        'status' => 'approved',
                        'approved_by' => 1,
                        'approved_at' => $eventDate->copy()->subDays(rand(2, 7)),
                        'created_at' => $eventDate->copy()->subDays(rand(8, 15)),
                        'updated_at' => $eventDate->copy()->subDays(rand(2, 7)),
                    ]);
                    
                    $bookingCount++;
                }
            }
        }

        // Create some future approved bookings (next 30 days)
        for ($daysAhead = 1; $daysAhead <= 30; $daysAhead++) {
            // 40% chance of booking on future dates
            if (rand(1, 100) <= 40) {
                $facility = $facilities->random();
                $eventType = $eventTypes[array_rand($eventTypes)];
                $eventDate = Carbon::now()->addDays($daysAhead);
                
                $startHour = rand(8, 18);
                $duration = rand(2, 5);
                $endHour = min($startHour + $duration, 22);
                
                $attendees = rand(20, 150);
                $baseFee = 5000;
                $extraHours = max(0, $duration - 3);
                $totalFee = $baseFee + ($extraHours * 2000);
                
                Booking::create([
                    'facility_id' => $facility->facility_id,
                    'user_id' => $citizen->id,
                    'user_name' => $citizen->name,
                    'applicant_name' => $citizen->full_name,
                    'applicant_email' => $citizen->email,
                    'applicant_phone' => '0912345678' . rand(0, 9),
                    'applicant_address' => 'Test Address, City',
                    'event_name' => $eventType,
                    'event_description' => 'Test future event for AI forecasting',
                    'event_date' => $eventDate->format('Y-m-d'),
                    'start_time' => sprintf('%02d:00', $startHour),
                    'end_time' => sprintf('%02d:00', $endHour),
                    'expected_attendees' => $attendees,
                    'total_fee' => $totalFee,
                    'status' => 'approved',
                    'approved_by' => 1,
                    'approved_at' => Carbon::now()->subDays(rand(1, 3)),
                    'created_at' => Carbon::now()->subDays(rand(4, 10)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 3)),
                ]);
                
                $bookingCount++;
            }
        }

        $this->command->info("Successfully created $bookingCount approved bookings for AI testing");
        $this->command->info('Historical data: Past 90 days');
        $this->command->info('Future data: Next 30 days');
    }
}

