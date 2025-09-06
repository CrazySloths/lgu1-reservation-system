<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $adminExists = User::where('email', 'admin@lgu1.gov.ph')->first();
        
        if (!$adminExists) {
            // Create admin user
            $admin = User::create([
                'first_name' => 'LGU1',
                'middle_name' => null,
                'last_name' => 'Administrator',
                'name' => 'LGU1 Administrator', // This will be auto-generated but we set it explicitly
                'email' => 'admin@lgu1.gov.ph',
                'password' => Hash::make('admin123'), // Change this password after first login!
                'role' => 'admin',
                'phone_number' => '09123456789',
                'region' => 'NCR',
                'city' => 'Manila',
                'barangay' => 'District 1',
                'street_address' => 'LGU Building',
                'address' => 'LGU Building, District 1, Manila, NCR',
                'date_of_birth' => '1990-01-01',
                'id_type' => 'Government-Issued ID',
                'id_number' => 'ADMIN-001',
                // Mark as verified since this is an admin
                'is_verified' => true,
                'verified_at' => now(),
                'email_verified' => true,
                'phone_verified' => true,
                'two_factor_enabled' => false,
                'failed_verification_attempts' => 0,
                'phone_verification_attempts' => 0,
                'last_security_check' => now(),
            ]);

            $this->command->info('✅ Admin user created successfully!');
            $this->command->info('📧 Email: admin@lgu1.gov.ph');
            $this->command->info('🔑 Password: admin123');
            $this->command->info('⚠️  Please change the password after first login!');
        } else {
            $this->command->info('ℹ️  Admin user already exists.');
            $this->command->info('📧 Email: admin@lgu1.gov.ph');
        }
    }
}