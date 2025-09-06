<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;

class FixBookingApplicantNames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:fix-applicant-names {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix mismatched applicant info (name, email, phone) to match user accounts (LGU policy enforcement)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking for booking applicant name mismatches...');
        $this->newLine();

        $bookings = Booking::with('user')->get();
        $mismatches = [];
        $fixed = 0;

        foreach ($bookings as $booking) {
            if (!$booking->user) {
                $this->warn("⚠️  Booking #{$booking->id}: No user associated");
                continue;
            }

            $userName = $booking->user->name;
            $applicantName = $booking->applicant_name;
            $userEmail = $booking->user->email;
            $applicantEmail = $booking->applicant_email;
            $userPhone = $booking->user->phone_number;
            $applicantPhone = $booking->applicant_phone;

            $nameMismatch = $userName !== $applicantName;
            $emailMismatch = $userEmail !== $applicantEmail;
            $phoneMismatch = $userPhone !== $applicantPhone;

            if ($nameMismatch || $emailMismatch || $phoneMismatch) {
                $mismatches[] = [
                    'id' => $booking->id,
                    'event' => $booking->event_name,
                    'user_name' => $userName,
                    'applicant_name' => $applicantName,
                    'user_email' => $booking->user->email,
                    'applicant_email' => $booking->applicant_email
                ];

                $this->line("📋 Booking #{$booking->id}: {$booking->event_name}");
                $this->line("   User Account: {$userName} | {$userEmail} | {$userPhone}");
                $this->line("   Applicant:    {$applicantName} | {$applicantEmail} | {$applicantPhone}");
                
                $violations = [];
                if ($nameMismatch) $violations[] = 'NAME';
                if ($emailMismatch) $violations[] = 'EMAIL';
                if ($phoneMismatch) $violations[] = 'PHONE';
                
                $this->line("   Status: <fg=yellow>MISMATCH (" . implode(', ', $violations) . ") - Violates LGU policy</>");
                
                if (!$this->option('dry-run')) {
                    // Fix the mismatch by updating applicant info to match user account
                    $booking->update([
                        'applicant_name' => $userName,
                        'applicant_email' => $userEmail,
                        'applicant_phone' => $userPhone,
                        'user_name' => $userName  // Also ensure user_name field matches
                    ]);
                    $this->line("   ✅ FIXED: Updated applicant info to match user account");
                    $fixed++;
                } else {
                    $this->line("   🔧 WOULD FIX: Update applicant to match user account");
                }
                $this->newLine();
            }
        }

        if (empty($mismatches)) {
            $this->info('✅ All booking applicant names match their user accounts! LGU policy compliant.');
        } else {
            $count = count($mismatches);
            if ($this->option('dry-run')) {
                $this->warn("🚨 Found {$count} policy violations. Run without --dry-run to fix them.");
            } else {
                $this->info("🎯 Fixed {$fixed} booking applicant mismatches. All bookings now comply with LGU policy.");
            }
        }

        return Command::SUCCESS;
    }
}
