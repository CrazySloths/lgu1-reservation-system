<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PaymentSlip extends Model
{
    /**
     * The database connection that should be used by the model.
     *
     * @var string
     */
    protected $connection = 'facilities_db';

    protected $fillable = [
        'slip_number',
        'booking_id',
        'user_id',
        'generated_by',
        'amount',
        'status',
        'due_date',
        'paid_at',
        'payment_method',
        'cashier_notes',
        'paid_by_cashier'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Generate a unique slip number
     */
    public static function generateSlipNumber()
    {
        $year = Carbon::now()->year;
        $lastSlip = self::where('slip_number', 'like', "PS-{$year}-%")
                       ->orderBy('slip_number', 'desc')
                       ->first();
        
        if ($lastSlip) {
            $lastNumber = intval(substr($lastSlip->slip_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "PS-{$year}-{$newNumber}";
    }

    /**
     * Relationships
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function paidByCashier()
    {
        return $this->belongsTo(User::class, 'paid_by_cashier');
    }

    /**
     * Check if payment slip is expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->status === 'unpaid' && $this->due_date->isPast();
    }

    /**
     * Get days until due
     */
    public function getDaysUntilDueAttribute()
    {
        if ($this->status === 'paid') {
            return 0;
        }
        
        return max(0, Carbon::now()->diffInDays($this->due_date, false));
    }
}

