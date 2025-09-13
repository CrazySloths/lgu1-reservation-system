<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facility extends Model
{
    use HasFactory;

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'facility_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'location',
        'capacity',
        'hourly_rate',
        'daily_rate',
        'facility_type',
        'amenities',
        'operating_hours_start',
        'operating_hours_end',
        'status',
        'image_path',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'hourly_rate' => 'float',
        'daily_rate' => 'float',
        'latitude' => 'float',
        'longitude' => 'float',
        'capacity' => 'integer',
    ];

    /**
     * Relationship: Facility has many bookings
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'facility_id', 'facility_id');
    }
}