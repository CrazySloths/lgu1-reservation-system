<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StaffUserSeeder extends Seeder
{
    /**
     * Run the database seeds for creating LGU staff users.
     */
    public function run(): void
    {
        $staffMembers = [
            [
                'name' => 'Elena Rodriguez',
                'email' => 'elena.rodriguez@lgu1.gov.ph',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'first_name' => 'Elena',
                'last_name' => 'Rodriguez',
                'region' => 'NCR',
                'city' => 'Caloocan City',
                'barangay' => 'South Caloocan',
                'street_address' => 'LGU Office, City Hall',
                'phone_number' => '09151234567',
                'email_verified' => true,
                'phone_verified' => true,
                'is_verified' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Carlos Mendoza',
                'email' => 'carlos.mendoza@lgu1.gov.ph',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'first_name' => 'Carlos',
                'last_name' => 'Mendoza',
                'region' => 'NCR',
                'city' => 'Caloocan City',
                'barangay' => 'South Caloocan',
                'street_address' => 'LGU Office, City Hall',
                'phone_number' => '09161234567',
                'email_verified' => true,
                'phone_verified' => true,
                'is_verified' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($staffMembers as $staff) {
            User::updateOrCreate(
                ['email' => $staff['email']], // Check if staff with this email exists
                $staff // Create or update with these values
            );
        }
        
        $this->command->info('✅ Created LGU staff members:');
        $this->command->info('   • Elena Rodriguez (elena.rodriguez@lgu1.gov.ph) - Password: staff123');
        $this->command->info('   • Carlos Mendoza (carlos.mendoza@lgu1.gov.ph) - Password: staff123');
        $this->command->info('   Staff members can verify citizen requirements before admin approval.');
    }
}
