<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceSchedule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'facility_id',
        'maintenance_type',
        'start_date',
        'end_date',
        'description',
        'contractor_name',
        'estimated_cost',
        'status',
        'scheduled_by',
        'completed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    /**
     * Relationship: Maintenance schedule belongs to a facility
     */
    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }
}
