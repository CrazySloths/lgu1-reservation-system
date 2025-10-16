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

    /**
     * Check if extending this booking would cause conflicts with other bookings
     * 
     * @param string $newEndTime The proposed new end time
     * @return array Array with 'hasConflict' boolean and 'conflicts' collection
     */
    public function checkExtensionConflict($newEndTime): array
    {
        // Validate that the new end time is actually an extension
        if ($newEndTime <= $this->end_time) {
            return [
                'hasConflict' => false,
                'conflicts' => collect(),
                'message' => 'New end time must be later than current end time'
            ];
        }

        // Find conflicting bookings on the same facility and date
        $conflicts = self::where('facility_id', $this->facility_id)
            ->where('event_date', $this->event_date)
            ->where('id', '!=', $this->id) // Exclude current booking
            ->whereIn('status', ['approved', 'pending']) // Only check approved/pending bookings
            ->where(function($query) use ($newEndTime) {
                // Check if the EXTENDED time (current start to new end) overlaps with other bookings
                // Overlap occurs when: this.start < other.end AND this.newEnd > other.start
                $query->where(function($q) use ($newEndTime) {
                    $q->where('start_time', '<', $newEndTime)
                      ->where('end_time', '>', $this->start_time);
                });
            })
            ->with(['facility', 'user'])
            ->get();

        return [
            'hasConflict' => $conflicts->isNotEmpty(),
            'conflicts' => $conflicts,
            'message' => $conflicts->isNotEmpty() 
                ? 'Extension would conflict with ' . $conflicts->count() . ' existing booking(s)'
                : 'No conflicts detected'
        ];
    }

    /**
     * Extend the booking end time
     * 
     * @param string $newEndTime
     * @return bool
     */
    public function extendBooking($newEndTime): bool
    {
        // Check for conflicts first
        $conflictCheck = $this->checkExtensionConflict($newEndTime);
        
        if ($conflictCheck['hasConflict']) {
            return false;
        }

        // Update the end time
        $this->end_time = $newEndTime;
        return $this->save();
    }
}