<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin 
                            {--email=admin@lgu1.gov.ph : Admin email address}
                            {--password=admin123 : Admin password}
                            {--name=LGU1 Administrator : Admin full name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user for the LGU1 Portal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $password = $this->option('password');
        $name = $this->option('name');

        // Validate email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            $this->error('❌ Invalid email address: ' . $email);
            return 1;
        }

        // Check if admin already exists
        $existingAdmin = User::where('email', $email)->first();
        
        if ($existingAdmin) {
            $this->error('❌ User with email ' . $email . ' already exists!');
            
            if ($existingAdmin->isAdmin()) {
                $this->info('ℹ️  This user is already an admin.');
            } else {
                $this->info('ℹ️  This user exists but is not an admin (role: ' . $existingAdmin->role . ')');
            }
            
            return 1;
        }

        try {
            // Create admin user
            $admin = User::create([
                'first_name' => 'LGU1',
                'middle_name' => null,
                'last_name' => 'Administrator',
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
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

            $this->info('');
            $this->info('🎉 Admin user created successfully!');
            $this->info('');
            $this->info('📧 Email: ' . $email);
            $this->info('🔑 Password: ' . $password);
            $this->info('👤 Name: ' . $name);
            $this->info('🆔 User ID: ' . $admin->id);
            $this->info('');
            $this->warn('⚠️  IMPORTANT: Please change the password after first login!');
            $this->info('');
            $this->info('🔗 You can now login at: /admin/login');

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to create admin user: ' . $e->getMessage());
            return 1;
        }
    }
}
