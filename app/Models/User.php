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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
