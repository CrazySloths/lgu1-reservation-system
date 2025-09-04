<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'phone_number',
        'region',
        'city',
        'barangay',
        'street_address',
        'address',
        'date_of_birth',
        'id_type',
        'id_number',
        'is_verified',
        'verified_at',
        // Authentication Security Fields
        'email_verified',
        'email_verification_token',
        'email_verification_sent_at',
        'phone_verified',
        'phone_verification_code',
        'phone_verification_sent_at',
        'phone_verification_attempts',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_enabled_at',
        'failed_verification_attempts',
        'verification_locked_until',
        'last_security_check',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verification_token',
        'phone_verification_code',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
            // Authentication Security Casts
            'email_verified' => 'boolean',
            'email_verification_sent_at' => 'datetime',
            'phone_verified' => 'boolean',
            'phone_verification_sent_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'two_factor_enabled_at' => 'datetime',
            'two_factor_recovery_codes' => 'json',
            'verification_locked_until' => 'datetime',
            'last_security_check' => 'datetime',
        ];
    }

    /**
     * Check if user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is a citizen
     */
    public function isCitizen(): bool
    {
        return $this->role === 'citizen';
    }

    /**
     * Check if citizen is verified
     */
    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    /**
     * Get user's reservations
     */
    public function reservations()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Get user's payment slips
     */
    public function paymentSlips()
    {
        return $this->hasMany(\App\Models\PaymentSlip::class, 'user_id');
    }

    /**
     * Get full name from components (fallback to 'name' if components are empty)
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            $fullName = $this->first_name;
            if ($this->middle_name) {
                $fullName .= ' ' . $this->middle_name;
            }
            $fullName .= ' ' . $this->last_name;
            return $fullName;
        }

        return $this->name ?: 'User';
    }

    /**
     * Get avatar initials for profile display
     */
    public function getAvatarInitialsAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return strtoupper(
                substr($this->first_name, 0, 1) . 
                substr($this->last_name, 0, 1)
            );
        }

        // Fallback to generating from the 'name' field
        $nameParts = explode(' ', $this->name ?: 'U');
        $firstName = $nameParts[0] ?? 'U';
        $lastName = end($nameParts);
        
        return strtoupper(
            substr($firstName, 0, 1) . 
            (($lastName !== $firstName) ? substr($lastName, 0, 1) : '')
        );
    }

    // ========================================
    // AUTHENTICATION SECURITY METHODS
    // ========================================

    /**
     * Check if email is verified
     */
    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified;
    }

    /**
     * Check if phone is verified
     */
    public function hasVerifiedPhone(): bool
    {
        return $this->phone_verified;
    }

    /**
     * Check if user has two-factor authentication enabled
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled && !empty($this->two_factor_secret);
    }

    /**
     * Check if user is currently locked due to failed verification attempts
     */
    public function isVerificationLocked(): bool
    {
        return $this->verification_locked_until && $this->verification_locked_until->isFuture();
    }

    /**
     * Generate email verification token
     */
    public function generateEmailVerificationToken(): string
    {
        $token = \Str::random(64);
        $this->update([
            'email_verification_token' => $token,
            'email_verification_sent_at' => now(),
        ]);
        return $token;
    }

    /**
     * Generate phone verification code
     */
    public function generatePhoneVerificationCode(): string
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->update([
            'phone_verification_code' => $code,
            'phone_verification_sent_at' => now(),
            'phone_verification_attempts' => 0,
        ]);
        return $code;
    }

    /**
     * Verify email with token
     */
    public function verifyEmail(string $token): bool
    {
        if ($this->email_verification_token === $token) {
            $this->update([
                'email_verified' => true,
                'email_verification_token' => null,
                'email_verification_sent_at' => null,
                'failed_verification_attempts' => 0,
            ]);
            return true;
        }
        
        $this->incrementFailedVerificationAttempts();
        return false;
    }

    /**
     * Verify phone with code
     */
    public function verifyPhone(string $code): bool
    {
        if ($this->phone_verification_code === $code && 
            $this->phone_verification_sent_at && 
            $this->phone_verification_sent_at->diffInMinutes(now()) <= 10) {
            
            $this->update([
                'phone_verified' => true,
                'phone_verification_code' => null,
                'phone_verification_sent_at' => null,
                'phone_verification_attempts' => 0,
                'failed_verification_attempts' => 0,
            ]);
            return true;
        }
        
        $this->increment('phone_verification_attempts');
        $this->incrementFailedVerificationAttempts();
        return false;
    }

    /**
     * Increment failed verification attempts and apply security locks
     */
    public function incrementFailedVerificationAttempts(): void
    {
        $this->increment('failed_verification_attempts');
        
        // Lock account for 30 minutes after 5 failed attempts
        if ($this->failed_verification_attempts >= 5) {
            $this->update([
                'verification_locked_until' => now()->addMinutes(30),
            ]);
        }
    }

    /**
     * Check if user has completed all required verifications
     */
    public function hasCompletedRequiredVerifications(): bool
    {
        return $this->hasVerifiedEmail() && $this->hasVerifiedPhone();
    }

    /**
     * Auto-populate 'name' field when saving (for backward compatibility)
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if ($user->first_name && $user->last_name) {
                $user->name = $user->full_name;
            }
        });
    }
}
