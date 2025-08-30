<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Facility;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get the test citizen user
        $citizen = User::where('email', 'citizen.test@email.com')->first();
        
        if (!$citizen) {
            $this->command->warn('Test citizen user not found. Please run AdminUserSeeder first.');
            return;
        }

        // Get some facilities
        $facilities = Facility::take(3)->get();
        
        if ($facilities->isEmpty()) {
            $this->command->warn('No facilities found. Please add some facilities first.');
            return;
        }

        $this->command->info('Creating sample bookings...');

        // Create sample bookings for different dates and statuses
        $bookings = [
            // Approved booking - past event
            [
                'facility_id' => $facilities->first()->facility_id,
                'user_id' => $citizen->id,
                'user_name' => $citizen->name,
                'applicant_name' => $citizen->full_name,
                'applicant_email' => $citizen->email,
                'applicant_phone' => '09123456789',
                'applicant_address' => 'Test Address, Quezon City',
                'event_name' => 'Birthday Celebration',
                'event_description' => 'Family birthday party for my daughter',
                'event_date' => Carbon::now()->subDays(15)->format('Y-m-d'),
                'start_time' => '14:00',
                'end_time' => '18:00',
                'expected_attendees' => 50,
                'total_fee' => 7000.00, // 5000 base + 2000 for extra hour
                'status' => 'approved',
                'approved_by' => 1, // Admin user
                'approved_at' => Carbon::now()->subDays(14),
                'created_at' => Carbon::now()->subDays(16),
                'updated_at' => Carbon::now()->subDays(14),
            ],
            
            // Pending booking - future event
            [
                'facility_id' => $facilities->skip(1)->first()->facility_id ?? $facilities->first()->facility_id,
                'user_id' => $citizen->id,
                'user_name' => $citizen->name,
                'applicant_name' => $citizen->full_name,
                'applicant_email' => $citizen->email,
                'applicant_phone' => '09123456789',
                'applicant_address' => 'Test Address, Quezon City',
                'event_name' => 'Community Meeting',
                'event_description' => 'Monthly community association meeting',
                'event_date' => Carbon::now()->addDays(10)->format('Y-m-d'),
                'start_time' => '09:00',
                'end_time' => '12:00',
                'expected_attendees' => 30,
                'total_fee' => 5000.00, // Base rate for 3 hours
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            
            // Another approved booking - upcoming
            [
                'facility_id' => $facilities->first()->facility_id,
                'user_id' => $citizen->id,
                'user_name' => $citizen->name,
                'applicant_name' => $citizen->full_name,
                'applicant_email' => $citizen->email,
                'applicant_phone' => '09123456789',
                'applicant_address' => 'Test Address, Quezon City',
                'event_name' => 'Wedding Reception',
                'event_description' => 'Wedding reception for family member',
                'event_date' => Carbon::now()->addDays(25)->format('Y-m-d'),
                'start_time' => '17:00',
                'end_time' => '22:00',
                'expected_attendees' => 120,
                'total_fee' => 11000.00, // 5000 base + 6000 for 3 extra hours
                'status' => 'approved',
                'approved_by' => 1, // Admin user
                'approved_at' => Carbon::now()->subDay(),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDay(),
            ],
            
            // Rejected booking
            [
                'facility_id' => $facilities->last()->facility_id ?? $facilities->first()->facility_id,
                'user_id' => $citizen->id,
                'user_name' => $citizen->name,
                'applicant_name' => $citizen->full_name,
                'applicant_email' => $citizen->email,
                'applicant_phone' => '09123456789',
                'applicant_address' => 'Test Address, Quezon City',
                'event_name' => 'Corporate Training',
                'event_description' => 'Employee training seminar',
                'event_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
                'start_time' => '08:00',
                'end_time' => '17:00',
                'expected_attendees' => 80,
                'total_fee' => 19000.00, // 5000 base + 14000 for 7 extra hours
                'status' => 'rejected',
                'rejected_reason' => 'Requested date conflicts with scheduled maintenance',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subHours(12),
            ],
        ];

        foreach ($bookings as $bookingData) {
            Booking::create($bookingData);
        }

        $this->command->info('Successfully created ' . count($bookings) . ' sample bookings');
        $this->command->info('Test data includes:');
        $this->command->info('- 1 approved past booking (Birthday Celebration)');
        $this->command->info('- 1 pending future booking (Community Meeting)');
        $this->command->info('- 1 approved future booking (Wedding Reception)');
        $this->command->info('- 1 rejected booking (Corporate Training)');
    }
}