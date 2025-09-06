<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bookings';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'facility_id',
        'user_id',
        'user_name',
        'applicant_name',
        'applicant_email', 
        'applicant_phone',
        'applicant_address',
        'event_name',
        'event_description',
        'event_date',
        'start_time',
        'end_time',
        'expected_attendees',
        'total_fee',
        'status',
        'staff_verified_by',
        'staff_verified_at',
        'staff_notes',
        'admin_notes',
        'approved_by',
        'approved_at',
        'rejected_reason',
        // Document file paths
        'valid_id_path',
        'id_back_path',
        'id_selfie_path',
        'authorization_letter_path',
        'event_proposal_path',
        'digital_signature'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'expected_attendees' => 'integer',
        'total_fee' => 'float',
        'staff_verified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the facility that owns the booking.
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }

    /**
     * Get the user that owns the booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the staff member who verified the booking requirements.
     */
    public function staffVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_verified_by');
    }

    /**
     * Get the admin who approved the booking.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the payment slip for this booking.
     */
    public function paymentSlip()
    {
        return $this->hasOne(PaymentSlip::class);
    }

    /**
     * Scope to get approved bookings only
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope to get pending bookings only
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if booking is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if booking is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if booking is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}