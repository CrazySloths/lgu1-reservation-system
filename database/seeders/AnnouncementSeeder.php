<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get an admin user to be the creator
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->warn('No admin user found. Please run AdminUserSeeder first.');
            return;
        }

        $this->command->info('Creating sample announcements...');

        $announcements = [
            // Pinned urgent announcement
            [
                'title' => 'Facility Maintenance Schedule - September 2025',
                'content' => "Important Notice: All facilities will undergo scheduled maintenance from September 15-17, 2025.\n\nAffected Facilities:\n• Buena Park Community Center\n• Bulwagan Hall\n• Sports Complex\n\nDuring this period, no reservations will be accepted. All existing bookings for these dates will be rescheduled.\n\nWe apologize for any inconvenience. Please contact our office for rescheduling assistance.",
                'type' => 'maintenance',
                'priority' => 'urgent',
                'target_audience' => 'citizens',
                'is_active' => true,
                'is_pinned' => true,
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(25),
                'created_by' => $admin->id,
                'additional_info' => 'Contact our office at (02) 8123-4567 for rescheduling assistance.',
            ],

            // General announcement
            [
                'title' => 'New Online Reservation System Now Live!',
                'content' => "We're excited to announce that our new online facility reservation system is now available!\n\nNew Features:\n• 24/7 online booking\n• Real-time availability checking\n• Digital document upload\n• Automated approval notifications\n• Reservation history tracking\n\nCitizens can now make reservations anytime through our citizen portal. Registration is required and subject to verification.",
                'type' => 'general',
                'priority' => 'high',
                'target_audience' => 'citizens',
                'is_active' => true,
                'is_pinned' => false,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => null,
                'created_by' => $admin->id,
                'additional_info' => 'Visit our citizen portal to register and start making reservations online.',
            ],

            // Event announcement
            [
                'title' => 'Community Health Fair - October 15, 2025',
                'content' => "Join us for the Annual Community Health Fair at Buena Park Community Center!\n\nDate: October 15, 2025\nTime: 8:00 AM - 5:00 PM\nVenue: Buena Park Community Center\n\nFree Services:\n• Health screenings\n• Blood pressure monitoring\n• BMI assessment\n• Vaccination services\n• Health consultations\n\nNote: The facility will be unavailable for private reservations on this date.",
                'type' => 'event',
                'priority' => 'medium',
                'target_audience' => 'citizens',
                'is_active' => true,
                'is_pinned' => false,
                'start_date' => Carbon::now()->subDays(3),
                'end_date' => Carbon::now()->addDays(45),
                'created_by' => $admin->id,
                'additional_info' => 'Bring your health insurance card and valid ID.',
            ],

            // Facility update
            [
                'title' => 'Enhanced Sound System Available at Bulwagan Hall',
                'content' => "We're pleased to announce that Bulwagan Hall now features a state-of-the-art sound system!\n\nNew Equipment:\n• Professional-grade speakers\n• Wireless microphone system\n• Audio mixing console\n• High-definition projector\n• LED lighting system\n\nThe enhanced sound system is now available for all events at no additional cost. Perfect for conferences, seminars, cultural events, and celebrations.",
                'type' => 'facility_update',
                'priority' => 'medium',
                'target_audience' => 'citizens',
                'is_active' => true,
                'is_pinned' => false,
                'start_date' => Carbon::now()->subDays(7),
                'end_date' => null,
                'created_by' => $admin->id,
                'additional_info' => 'Technical support staff will be available to assist with equipment setup.',
            ],

            // Important policy update
            [
                'title' => 'Updated Reservation Policy - Effective September 1, 2025',
                'content' => "Please note the following updates to our facility reservation policy:\n\n1. Advanced Booking: Reservations must be made at least 5 business days in advance\n2. Cancellation Policy: Cancellations must be made 48 hours before the event\n3. Payment Terms: Full payment required upon approval\n4. Documentation: All required documents must be submitted during booking\n5. Capacity Limits: Strict adherence to facility capacity for safety\n\nThese changes ensure better service and facility management for all citizens.",
                'type' => 'general',
                'priority' => 'high',
                'target_audience' => 'citizens',
                'is_active' => true,
                'is_pinned' => false,
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => null,
                'created_by' => $admin->id,
                'additional_info' => 'Contact our office if you have questions about the new policies.',
            ],

            // Holiday notice
            [
                'title' => 'Holiday Schedule - Independence Day Week',
                'content' => "Office and facility operations during Independence Day week:\n\nJune 12 (Independence Day): All facilities closed\nJune 13 (Friday): Limited operations, emergency only\nJune 14-15 (Weekend): Normal weekend operations\n\nOnline reservations will continue to be processed. Approved bookings will be confirmed when office operations resume.\n\nWe wish everyone a happy Independence Day celebration!",
                'type' => 'general',
                'priority' => 'medium',
                'target_audience' => 'all',
                'is_active' => true,
                'is_pinned' => false,
                'start_date' => Carbon::now()->subDays(2),
                'end_date' => Carbon::now()->addDays(30),
                'created_by' => $admin->id,
                'additional_info' => 'Emergency contact: (02) 8123-4567',
            ],
        ];

        foreach ($announcements as $announcementData) {
            Announcement::create($announcementData);
        }

        $this->command->info('Successfully created ' . count($announcements) . ' sample announcements');
        $this->command->info('Announcements include:');
        $this->command->info('- 1 pinned urgent maintenance notice');
        $this->command->info('- 1 system announcement (high priority)');
        $this->command->info('- 1 community event notice');
        $this->command->info('- 1 facility update notice');
        $this->command->info('- 1 policy update notice');
        $this->command->info('- 1 holiday schedule notice');
    }
}