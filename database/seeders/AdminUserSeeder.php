<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create System Administrator
        User::updateOrCreate(
            ['email' => 'admin@lgu1.gov.ph'],
            [
                'name' => 'System Administrator',
                'email' => 'admin@lgu1.gov.ph',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone_number' => '09123456789',
                'address' => 'LGU1 Office, Caloocan City',
                'date_of_birth' => Carbon::parse('1985-01-01'),
                'id_type' => 'Government-Issued ID',
                'id_number' => 'GOV-ADMIN-001',
                'is_verified' => true,
                'verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        // Create Department Head
        User::updateOrCreate(
            ['email' => 'dept.head@lgu1.gov.ph'],
            [
                'name' => 'Department Head',
                'email' => 'dept.head@lgu1.gov.ph',
                'password' => Hash::make('depthead123'),
                'role' => 'admin',
                'phone_number' => '09123456790',
                'address' => 'LGU1 Department Office, Caloocan City',
                'date_of_birth' => Carbon::parse('1980-05-15'),
                'id_type' => 'Government-Issued ID',
                'id_number' => 'GOV-DEPT-001',
                'is_verified' => true,
                'verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        // Create Supervisor
        User::updateOrCreate(
            ['email' => 'supervisor@lgu1.gov.ph'],
            [
                'name' => 'Facility Supervisor',
                'email' => 'supervisor@lgu1.gov.ph',
                'password' => Hash::make('supervisor123'),
                'role' => 'admin',
                'phone_number' => '09123456791',
                'address' => 'LGU1 Facility Office, Caloocan City',
                'date_of_birth' => Carbon::parse('1988-03-20'),
                'id_type' => 'Government-Issued ID',
                'id_number' => 'GOV-SUP-001',
                'is_verified' => true,
                'verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        // Create Test Citizen User
        User::updateOrCreate(
            ['email' => 'citizen.test@email.com'],
            [
                'name' => 'Test Citizen User',
                'email' => 'citizen.test@email.com',
                'password' => Hash::make('citizen123'),
                'role' => 'citizen',
                'phone_number' => '09987654321',
                'address' => '123 Test Street, Caloocan City',
                'date_of_birth' => Carbon::parse('1995-07-10'),
                'id_type' => 'School ID',
                'id_number' => 'SCH-TEST-001',
                'is_verified' => true,
                'verified_at' => now(),
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin and test users created successfully!');
        $this->command->table(
            ['Role', 'Email', 'Password', 'Status'],
            [
                ['System Admin', 'admin@lgu1.gov.ph', 'admin123', 'Verified'],
                ['Department Head', 'dept.head@lgu1.gov.ph', 'depthead123', 'Verified'],
                ['Supervisor', 'supervisor@lgu1.gov.ph', 'supervisor123', 'Verified'],
                ['Test Citizen', 'citizen.test@email.com', 'citizen123', 'Verified'],
            ]
        );
    }
}