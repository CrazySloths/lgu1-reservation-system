<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitizenFeedback extends Model
{
    protected $table = 'citizen_feedback';
    
    protected $fillable = [
        'name',
        'email',
        'category',
        'question',
        'status',
        'admin_response',
        'responded_by',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Get the admin who responded to this feedback.
     */
    public function respondedBy()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Scope to filter by status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending feedback.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}

