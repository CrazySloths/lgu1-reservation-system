<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\User;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds for testing dashboard functionality.
     */
    public function run(): void
    {
        $facilities = Facility::all();
        $users = User::where('role', 'citizen')->get();
        $staff = User::where('role', 'staff')->get();
        
        if ($facilities->isEmpty()) {
            $this->command->error('No facilities found. Please run FacilitySeeder first.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->error('No citizen users found. Creating test users...');
            // Create some test citizens if none exist
            $users = collect([
                User::factory()->create([
                    'name' => 'Juan Cruz',
                    'email' => 'juan.cruz@example.com',
                    'role' => 'citizen',
                    'email_verified' => true,
                    'phone_verified' => true,
                    'is_verified' => true
                ]),
                User::factory()->create([
                    'name' => 'Maria Santos', 
                    'email' => 'maria.santos@example.com',
                    'role' => 'citizen',
                    'email_verified' => true,
                    'phone_verified' => true,
                    'is_verified' => true
                ])
            ]);
        }

        if ($staff->isEmpty()) {
            $this->command->error('No staff users found. Please run StaffUserSeeder first.');
            return;
        }

        $sampleBookings = [
            // Pending approvals
            [
                'facility_id' => $facilities->where('name', 'Buena Park')->first()->facility_id,
                'user_id' => $users->random()->id,
                'user_name' => 'Juan Cruz',
                'applicant_name' => 'Juan Cruz',
                'applicant_email' => 'juan.cruz@example.com',
                'applicant_phone' => '09171234567',
                'applicant_address' => 'Barangay 180, Caloocan City',
                'event_name' => 'Community Fiesta 2025',
                'event_description' => 'Annual community celebration with food stalls, cultural shows, and games.',
                'event_date' => Carbon::now()->addDays(15),
                'start_time' => '14:00',
                'end_time' => '20:00',
                'expected_attendees' => 300,
                'total_fee' => 12000.00,
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(2)
            ],
            [
                'facility_id' => $facilities->where('name', 'Sports Complex')->first()->facility_id,
                'user_id' => $users->random()->id,
                'user_name' => 'Maria Santos',
                'applicant_name' => 'Maria Santos',
                'applicant_email' => 'maria.santos@example.com', 
                'applicant_phone' => '09181234567',
                'applicant_address' => 'Barangay 178, Caloocan City',
                'event_name' => 'Inter-Barangay Basketball Tournament',
                'event_description' => 'Semi-annual basketball tournament for local barangays.',
                'event_date' => Carbon::now()->addDays(20),
                'start_time' => '08:00',
                'end_time' => '17:00',
                'expected_attendees' => 500,
                'total_fee' => 13500.00,
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(1)
            ],
            // Approved bookings (upcoming events)
            [
                'facility_id' => $facilities->where('name', 'Bulwagan Katipunan')->first()->facility_id,
                'user_id' => $users->random()->id,
                'user_name' => 'Sofia Reyes',
                'applicant_name' => 'Sofia Reyes',
                'applicant_email' => 'sofia.reyes@example.com',
                'applicant_phone' => '09191234567',
                'applicant_address' => 'Barangay 180, Caloocan City',
                'event_name' => 'Youth Leadership Seminar',
                'event_description' => 'Educational seminar for high school students on leadership and governance.',
                'event_date' => Carbon::now()->addDays(5),
                'start_time' => '09:00',
                'end_time' => '16:00',
                'expected_attendees' => 150,
                'total_fee' => 21000.00,
                'status' => 'approved',
                'staff_verified_by' => $staff->random()->id,
                'staff_verified_at' => Carbon::now()->subDays(5),
                'staff_notes' => 'All requirements verified. Valid ID, event proposal, and authorization letter submitted.',
                'approved_by' => 1, // Admin user ID
                'approved_at' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(7)
            ],
            [
                'facility_id' => $facilities->where('name', 'Pacquiao Court')->first()->facility_id,
                'user_id' => $users->random()->id,
                'user_name' => 'Roberto Martinez',
                'applicant_name' => 'Roberto Martinez',
                'applicant_email' => 'info@caloocanboxing.com',
                'applicant_phone' => '09201234567',
                'applicant_address' => 'Barangay 176, Caloocan City',
                'event_name' => 'Amateur Boxing Exhibition',
                'event_description' => 'Local boxing exhibition match featuring amateur boxers.',
                'event_date' => Carbon::today()->addDays(3),
                'start_time' => '18:00',
                'end_time' => '21:00',
                'expected_attendees' => 200,
                'total_fee' => 3000.00,
                'status' => 'approved',
                'staff_verified_by' => $staff->random()->id,
                'staff_verified_at' => Carbon::now()->subDays(7),
                'staff_notes' => 'Requirements verified. Event permit and insurance documents submitted.',
                'approved_by' => 1,
                'approved_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(10)
            ],
            // Today's event
            [
                'facility_id' => $facilities->where('name', 'Sports Complex')->first()->facility_id,
                'user_id' => $users->random()->id,
                'user_name' => 'Ana Cruz',
                'applicant_name' => 'Ana Cruz',
                'applicant_email' => 'run@caloocan.com',
                'applicant_phone' => '09211234567',
                'applicant_address' => 'Barangay 179, Caloocan City',
                'event_name' => 'Morning Fun Run 2025',
                'event_description' => 'Weekly community fun run promoting health and wellness.',
                'event_date' => Carbon::today(),
                'start_time' => '05:30',
                'end_time' => '08:00',
                'expected_attendees' => 100,
                'total_fee' => 3750.00,
                'status' => 'approved',
                'staff_verified_by' => $staff->random()->id,
                'staff_verified_at' => Carbon::now()->subDays(10),
                'staff_notes' => 'Medical clearance and safety protocols verified for sports event.',
                'approved_by' => 1,
                'approved_at' => Carbon::now()->subDays(7),
                'created_at' => Carbon::now()->subDays(14)
            ],
            // This month's approved bookings (for statistics)
            [
                'facility_id' => $facilities->where('name', 'Buena Park')->first()->facility_id,
                'user_id' => $users->random()->id,
                'user_name' => 'NGO Partners Inc.',
                'applicant_name' => 'NGO Partners Inc.',
                'applicant_email' => 'events@ngopartners.org',
                'applicant_phone' => '09221234567',
                'applicant_address' => 'Manila City',
                'event_name' => 'Environmental Awareness Day',
                'event_description' => 'Community event promoting environmental protection and sustainability.',
                'event_date' => Carbon::now()->subDays(10),
                'start_time' => '10:00',
                'end_time' => '16:00',
                'expected_attendees' => 400,
                'total_fee' => 12000.00,
                'status' => 'approved',
                'staff_verified_by' => $staff->random()->id,
                'staff_verified_at' => Carbon::now()->subDays(18),
                'staff_notes' => 'NGO registration documents and environmental impact assessment verified.',
                'approved_by' => 1,
                'approved_at' => Carbon::now()->subDays(15),
                'created_at' => Carbon::now()->subDays(20)
            ]
        ];

        foreach ($sampleBookings as $bookingData) {
            Booking::create($bookingData);
        }

        $this->command->info('✅ Created sample bookings for dashboard testing:');
        $this->command->info('   • 2 Pending approvals (need admin action)');
        $this->command->info('   • 3 Approved upcoming events');
        $this->command->info('   • 1 Today\'s event');
        $this->command->info('   • Sample monthly statistics data');
    }
}