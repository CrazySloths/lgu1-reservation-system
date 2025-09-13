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
                'name' => 'Facilities Staff',
                'email' => 'Staff-Facilities123',
                'password' => Hash::make('Staff-Facilities123'),
                'role' => 'staff',
                'first_name' => 'Facilities',
                'last_name' => 'Staff',
                'region' => 'NCR',
                'city' => 'Caloocan City',
                'barangay' => 'South Caloocan',
                'street_address' => 'LGU Office, City Hall',
                'phone_number' => '09151234567',
                'email_verified' => true,
                'phone_verified' => true,
                'is_verified' => true,
                'verified_at' => now(),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
                'external_id' => null // Same as admin users - let SSO handle dynamically
            ],
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
                'verified_at' => now(),
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
                'verified_at' => now(),
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
        
        $this->command->info(' Created LGU staff members:');
        $this->command->info('   • Facilities Staff (Staff-Facilities123) - Password: Staff-Facilities123');
        $this->command->info('   • Elena Rodriguez (elena.rodriguez@lgu1.gov.ph) - Password: staff123');
        $this->command->info('   • Carlos Mendoza (carlos.mendoza@lgu1.gov.ph) - Password: staff123');
        $this->command->info('   Staff members can verify citizen requirements before admin approval.');
        $this->command->info('');
        $this->command->info(' Staff Portal Access:');
        $this->command->info('   Login at: https://local-government-unit-1-ph.com/public/login.php');
        $this->command->info('   Username: Staff-Facilities123');
        $this->command->info('   Password: Staff-Facilities123');
        $this->command->info('   Will redirect to: /staff/dashboard');
    }
}
