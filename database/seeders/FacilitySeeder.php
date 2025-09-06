<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds based on South Caloocan LGU facilities mentioned in the interview.
     */
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Buena Park',
                'description' => 'Multi-purpose outdoor venue ideal for community events, festivals, and large gatherings. Features open green space with stage area.',
                'location' => 'Barangay 178, South Caloocan City',
                'capacity' => 500,
                'hourly_rate' => 2000.00,
                'daily_rate' => 5000.00,
                'facility_type' => 'outdoor',
                'amenities' => json_encode(['Stage Area', 'Sound System Available', 'Electrical Outlets', 'Parking Space', 'Restrooms']),
                'operating_hours_start' => '06:00',
                'operating_hours_end' => '22:00',
                'status' => 'active'
            ],
            [
                'name' => 'Sports Complex',
                'description' => 'Complete sports facility with basketball court, volleyball court, and athletic track. Suitable for sports events and tournaments.',
                'location' => 'South Caloocan Sports Complex, Caloocan City',
                'capacity' => 1000,
                'hourly_rate' => 1500.00,
                'daily_rate' => 4000.00,
                'facility_type' => 'sports',
                'amenities' => json_encode(['Basketball Court', 'Volleyball Court', 'Athletic Track', 'Bleachers', 'Locker Rooms', 'Equipment Storage']),
                'operating_hours_start' => '05:00',
                'operating_hours_end' => '21:00',
                'status' => 'active'
            ],
            [
                'name' => 'Bulwagan Katipunan',
                'description' => 'Indoor conference hall primarily used for city events, meetings, seminars, and official LGU functions. Air-conditioned venue with modern facilities.',
                'location' => 'City Hall Complex, South Caloocan City',
                'capacity' => 200,
                'hourly_rate' => 3000.00,
                'daily_rate' => 8000.00,
                'facility_type' => 'indoor',
                'amenities' => json_encode(['Air Conditioning', 'Audio-Visual Equipment', 'Tables and Chairs', 'Stage/Podium', 'Restrooms', 'WiFi']),
                'operating_hours_start' => '08:00',
                'operating_hours_end' => '20:00',
                'status' => 'active'
            ],
            [
                'name' => 'Pacquiao Court',
                'description' => 'Covered basketball court named after the boxing legend. Perfect for basketball tournaments, community sports events, and covered activities.',
                'location' => 'Barangay 176, South Caloocan City',
                'capacity' => 300,
                'hourly_rate' => 1000.00,
                'daily_rate' => 3000.00,
                'facility_type' => 'sports',
                'amenities' => json_encode(['Covered Basketball Court', 'Bleacher Seating', 'Scoreboard', 'Lighting System', 'Parking Area']),
                'operating_hours_start' => '06:00',
                'operating_hours_end' => '22:00',
                'status' => 'active'
            ]
        ];

        foreach ($facilities as $facility) {
            Facility::updateOrCreate(
                ['name' => $facility['name']], // Check if facility with this name exists
                $facility // Create or update with these values
            );
        }
        
        $this->command->info('✅ Created 4 South Caloocan LGU facilities');
        $this->command->info('   • Buena Park (Multi-purpose outdoor venue)');
        $this->command->info('   • Sports Complex (Complete sports facility)');
        $this->command->info('   • Bulwagan Katipunan (Indoor conference hall)');
        $this->command->info('   • Pacquiao Court (Covered basketball court)');
    }
}
