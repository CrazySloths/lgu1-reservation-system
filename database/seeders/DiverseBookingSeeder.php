<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Facility;
use Carbon\Carbon;

class DiverseBookingSeeder extends Seeder
{
    /**
     * Run the database seeder to create diverse bookings with different users.
     */
    public function run(): void
    {
        $this->command->info('Creating diverse bookings with multiple users...');

        // Create multiple test citizens with diverse names
        $citizens = [
            ['name' => 'Juan Dela Cruz', 'email' => 'juan.delacruz@email.com'],
            ['name' => 'Maria Santos', 'email' => 'maria.santos@email.com'],
            ['name' => 'Pedro Reyes', 'email' => 'pedro.reyes@email.com'],
            ['name' => 'Ana Garcia', 'email' => 'ana.garcia@email.com'],
            ['name' => 'Jose Mercado', 'email' => 'jose.mercado@email.com'],
            ['name' => 'Rosa Flores', 'email' => 'rosa.flores@email.com'],
            ['name' => 'Carlos Ramirez', 'email' => 'carlos.ramirez@email.com'],
            ['name' => 'Linda Gomez', 'email' => 'linda.gomez@email.com'],
        ];

        $createdUsers = [];
        foreach ($citizens as $citizenData) {
            $nameParts = explode(' ', $citizenData['name']);
            $firstName = $nameParts[0];
            $lastName = $nameParts[count($nameParts) - 1];
            $middleName = count($nameParts) > 2 ? $nameParts[1] : '';
            
            $user = User::firstOrCreate(
                ['email' => $citizenData['email']],
                [
                    'name' => $citizenData['name'],
                    'first_name' => $firstName,
                    'middle_name' => $middleName,
                    'last_name' => $lastName,
                    'password' => bcrypt('password123'),
                    'role' => 'citizen',
                    'is_verified' => true,
                    'email_verified' => true,
                    'phone_number' => '0912345678' . rand(0, 9),
                    'address' => 'Test Address, Quezon City',
                ]
            );
            $createdUsers[] = $user;
        }

        $this->command->info('Created ' . count($createdUsers) . ' diverse users');

        // Get all facilities
        $facilities = Facility::all();
        
        if ($facilities->isEmpty()) {
            $this->command->warn('No facilities found. Please add facilities first.');
            return;
        }

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
            'Training Seminar',
            'Alumni Homecoming',
            'Awards Night',
            'Fundraising Gala',
            'Team Building',
            'Company Outing'
        ];

        $bookingCount = 0;

        // Create bookings for the past 6 months with better distribution
        // This ensures we have multiple data points per month
        for ($monthsAgo = 6; $monthsAgo >= 1; $monthsAgo--) {
            // Create 15-25 bookings per month for better data
            $bookingsPerMonth = rand(15, 25);
            
            for ($i = 0; $i < $bookingsPerMonth; $i++) {
                $facility = $facilities->random();
                $user = $createdUsers[array_rand($createdUsers)];
                $eventType = $eventTypes[array_rand($eventTypes)];
                
                // Random day within the month
                $daysInMonth = Carbon::now()->subMonths($monthsAgo)->daysInMonth;
                $randomDay = rand(1, $daysInMonth);
                $eventDate = Carbon::now()->subMonths($monthsAgo)->day($randomDay);
                
                // Random times
                $startHour = rand(8, 18);
                $duration = rand(3, 6); // 3-6 hours
                $endHour = min($startHour + $duration, 22);
                
                $attendees = rand(30, 200);
                $baseFee = 5000;
                $extraHours = max(0, $duration - 3);
                $totalFee = $baseFee + ($extraHours * 2000);
                
                Booking::create([
                    'facility_id' => $facility->facility_id,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'applicant_name' => $user->name,
                    'applicant_email' => $user->email,
                    'applicant_phone' => $user->phone_number ?? '09123456789',
                    'applicant_address' => $user->address ?? 'Test Address',
                    'event_name' => $eventType,
                    'event_description' => 'Community event organized by ' . $user->name,
                    'event_date' => $eventDate->format('Y-m-d'),
                    'start_time' => sprintf('%02d:00', $startHour),
                    'end_time' => sprintf('%02d:00', $endHour),
                    'expected_attendees' => $attendees,
                    'total_fee' => $totalFee,
                    'status' => 'approved',
                    'approved_by' => 1,
                    'approved_at' => $eventDate->copy()->subDays(rand(3, 10)),
                    'created_at' => $eventDate->copy()->subDays(rand(11, 20)),
                    'updated_at' => $eventDate->copy()->subDays(rand(3, 10)),
                ]);
                
                $bookingCount++;
            }
        }

        // Create some future approved bookings (next 2 months)
        for ($monthsAhead = 0; $monthsAhead < 2; $monthsAhead++) {
            $bookingsPerMonth = rand(10, 15);
            
            for ($i = 0; $i < $bookingsPerMonth; $i++) {
                $facility = $facilities->random();
                $user = $createdUsers[array_rand($createdUsers)];
                $eventType = $eventTypes[array_rand($eventTypes)];
                
                $daysInMonth = Carbon::now()->addMonths($monthsAhead)->daysInMonth;
                $randomDay = rand(1, min($daysInMonth, 28)); // Avoid end of month issues
                $eventDate = Carbon::now()->addMonths($monthsAhead)->day($randomDay);
                
                $startHour = rand(8, 18);
                $duration = rand(3, 6);
                $endHour = min($startHour + $duration, 22);
                
                $attendees = rand(30, 200);
                $baseFee = 5000;
                $extraHours = max(0, $duration - 3);
                $totalFee = $baseFee + ($extraHours * 2000);
                
                Booking::create([
                    'facility_id' => $facility->facility_id,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'applicant_name' => $user->name,
                    'applicant_email' => $user->email,
                    'applicant_phone' => $user->phone_number ?? '09123456789',
                    'applicant_address' => $user->address ?? 'Test Address',
                    'event_name' => $eventType,
                    'event_description' => 'Upcoming event organized by ' . $user->name,
                    'event_date' => $eventDate->format('Y-m-d'),
                    'start_time' => sprintf('%02d:00', $startHour),
                    'end_time' => sprintf('%02d:00', $endHour),
                    'expected_attendees' => $attendees,
                    'total_fee' => $totalFee,
                    'status' => 'approved',
                    'approved_by' => 1,
                    'approved_at' => Carbon::now()->subDays(rand(1, 5)),
                    'created_at' => Carbon::now()->subDays(rand(6, 15)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 5)),
                ]);
                
                $bookingCount++;
            }
        }

        $this->command->info("Successfully created $bookingCount approved bookings with diverse users");
        $this->command->info('Historical data: Past 6 months (15-25 bookings/month)');
        $this->command->info('Future data: Next 2 months (10-15 bookings/month)');
        
        // Show month distribution
        $monthCounts = Booking::where('status', 'approved')
            ->selectRaw('DATE_FORMAT(event_date, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $this->command->info("\nBookings per month:");
        foreach ($monthCounts as $monthData) {
            $this->command->info("  {$monthData->month}: {$monthData->count} bookings");
        }
    }
}

