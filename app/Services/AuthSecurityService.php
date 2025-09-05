<?php

namespace App\Services;

use App\Models\User;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;
use Twilio\Rest\Client;

class AuthSecurityService
{
    protected $google2fa;

    public function __construct()
    {
        $this->google2fa = new Google2FA();
    }

    /**
     * Send email verification
     */
    public function sendEmailVerification(User $user): bool
    {
        try {
            $token = $user->generateEmailVerificationToken();
            $verificationUrl = route('citizen.auth.verify-email', ['token' => $token]);

            // Log the intent to send a verification email
            Log::info('Attempting to send email verification', [
                'user_id' => $user->id,
                'email' => $user->email,
                'verification_url' => $verificationUrl,
            ]);

            // Send the actual email using the Mailable
            Mail::to($user->email)->send(new EmailVerificationMail($user, $verificationUrl));
            
            Log::info('Email verification Mailable dispatched successfully', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);
            
            return true;

        } catch (\Exception $e) {
            // Log the email sending failure
            Log::error('Failed to send verification email', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
            
            return false;
        }
    }

    /**
     * Send SMS OTP verification
     */
    public function sendSmsVerification(User $user): bool
    {
        try {
            $code = $user->generatePhoneVerificationCode();
          
            // For development: Store SMS code in session for easy access
            if (config('app.env') === 'local') {
                session()->put('dev_sms_verification', [
                    'phone' => $user->phone_number,
                    'code' => $code,
                    'expires_at' => now()->addMinutes(10)->format('Y-m-d H:i:s')
                ]);
            }

            // Log the SMS details
            Log::info('SMS Verification Sent', [
                'user_id' => $user->id,
                'phone' => $user->phone_number,
                'code' => $code
            ]);

            // Send SMS via Twilio
            $this->sendSms($user->phone_number, "Your LGU1 verification code is: {$code}");

            // TODO: In production, integrate with actual SMS service (Twilio, Nexmo, etc.)
            // $this->sendSms($user->phone_number, "Your LGU1 verification code is: {$code}");

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send SMS verification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    protected function sendSms(string $recipient, string $message): void
    {
        $accountSid = config('services.twilio.sid');
        $authToken = config('services.twilio.token');
        $fromNumber = config('services.twilio.from');

        if (!$accountSid || !$authToken || !$fromNumber) {
            Log::error('Twilio credentials are not configured.');
            return;
        }

        // Format Philippine phone numbers for international use
        $formattedRecipient = $this->formatPhoneNumber($recipient);

        try {
            $client = new Client($accountSid, $authToken);
            $client->messages->create($formattedRecipient, [
                'from' => $fromNumber,
                'body' => $message,
            ]);

            Log::info('SMS sent successfully via Twilio', [
                'original' => $recipient,
                'formatted' => $formattedRecipient
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send SMS via Twilio', [
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Format phone number for international use (specifically for Philippine numbers)
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any spaces, dashes, or other non-digit characters
        $cleaned = preg_replace('/[^\d+]/', '', $phoneNumber);
        
        // If it already starts with +63, return as is
        if (str_starts_with($cleaned, '+63')) {
            return $cleaned;
        }
        
        // If it starts with 63, add +
        if (str_starts_with($cleaned, '63')) {
            return '+' . $cleaned;
        }
        
        // If it starts with 09, convert to +639
        if (str_starts_with($cleaned, '09')) {
            return '+63' . substr($cleaned, 1);
        }
        
        // If it starts with 9 (without the 0), convert to +639
        if (str_starts_with($cleaned, '9') && strlen($cleaned) === 10) {
            return '+63' . $cleaned;
        }
        
        // For any other format, assume it's Philippine and add +63
        return '+63' . ltrim($cleaned, '0');
    }

    public function generateTotpSecret(User $user): string
    {
        $secret = $this->google2fa->generateSecretKey();
        
        $user->update([
            'two_factor_secret' => $secret,
        ]);

        return $secret;
    }

    /**
     * Generate QR code URL for authenticator apps
     */
    public function generateQrCodeUrl(User $user): string
    {
        $companyName = config('app.name', 'LGU1 Portal');
        $companyEmail = $user->email;
        
        return $this->google2fa->getQRCodeUrl(
            $companyName,
            $companyEmail,
            $user->two_factor_secret
        );
    }

    /**
     * Verify TOTP code
     */
    public function verifyTotpCode(User $user, string $code): bool
    {
        if (!$user->two_factor_secret) {
            return false;
        }

        return $this->google2fa->verifyKey($user->two_factor_secret, $code);
    }

    /**
     * Enable two-factor authentication for user
     */
    public function enableTwoFactor(User $user, string $verificationCode): bool
    {
        if ($this->verifyTotpCode($user, $verificationCode)) {
            $recoveryCodes = $this->generateRecoveryCodes();
            
            $user->update([
                'two_factor_enabled' => true,
                'two_factor_enabled_at' => now(),
                'two_factor_recovery_codes' => $recoveryCodes,
            ]);

            Log::info('Two-factor authentication enabled', [
                'user_id' => $user->id,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Disable two-factor authentication
     */
    public function disableTwoFactor(User $user): bool
    {
        $user->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_enabled_at' => null,
        ]);

        Log::info('Two-factor authentication disabled', [
            'user_id' => $user->id,
        ]);

        return true;
    }

    /**
     * Generate recovery codes for 2FA
     */
    protected function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(4)));
        }
        return $codes;
    }

    /**
     * Verify recovery code
     */
    public function verifyRecoveryCode(User $user, string $code): bool
    {
        $recoveryCodes = $user->two_factor_recovery_codes;
        
        if (!$recoveryCodes || !in_array(strtoupper($code), $recoveryCodes)) {
            return false;
        }

        // Remove used recovery code
        $updatedCodes = array_filter($recoveryCodes, function($recoveryCode) use ($code) {
            return strtoupper($recoveryCode) !== strtoupper($code);
        });

        $user->update([
            'two_factor_recovery_codes' => array_values($updatedCodes)
        ]);

        return true;
    }

    /**
     * Check if user can proceed with verification
     */
    public function canProceedWithVerification(User $user): bool
    {
        return !$user->isVerificationLocked();
    }

    /**
     * Reset failed verification attempts
     */
    public function resetFailedVerificationAttempts(User $user): void
    {
        $user->update([
            'failed_verification_attempts' => 0,
            'verification_locked_until' => null,
        ]);
    }

    /**
     * Get development verification codes (for testing only)
     */
    public function getDevVerificationCodes(): array
    {
        if (config('app.env') !== 'local') {
            return [];
        }

        return [
            'email' => session('dev_email_verification'),
            'sms' => session('dev_sms_verification'),
        ];
    }
