<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'facility_id',
        'maintenance_type',
        'title',
        'description',
        'reported_by',
        'reported_by_id',
        'assigned_to',
        'assigned_contact',
        'status',
        'priority',
        'scheduled_date',
        'completed_date',
        'estimated_cost',
        'actual_cost',
        'notes',
        'completion_notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    /**
     * Get the facility that this maintenance log belongs to.
     */
    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id', 'facility_id');
    }

    /**
     * Get the user who reported this maintenance issue.
     */
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by_id');
    }

    /**
     * Scope a query to only include pending maintenance logs.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include in-progress maintenance logs.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope a query to only include completed maintenance logs.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include urgent priority logs.
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }
}
