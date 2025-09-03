<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'type',
        'priority',
        'target_audience',
        'is_active',
        'is_pinned',
        'start_date',
        'end_date',
        'created_by',
        'attachment_path',
        'additional_info'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_pinned' => 'boolean',
    ];

    /**
     * Get the user who created the announcement.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope to get active announcements only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('start_date', '<=', now())
                    ->where(function ($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    /**
     * Scope to get pinned announcements
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope to get announcements for specific audience
     */
    public function scopeForAudience($query, $audience)
    {
        return $query->whereIn('target_audience', ['all', $audience]);
    }

    /**
     * Scope to get announcements by priority
     */
    public function scopeByPriority($query, $priority = null)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
        
        return $query->orderByRaw("FIELD(priority, 'urgent', 'high', 'medium', 'low')");
    }

    /**
     * Scope to get announcements by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if announcement is currently active
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        
        if ($this->start_date > $now) {
            return false;
        }

        if ($this->end_date && $this->end_date < $now) {
            return false;
        }

        return true;
    }

    /**
     * Check if announcement is expired
     */
    public function isExpired(): bool
    {
        return $this->end_date && $this->end_date < now();
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get type badge color
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'urgent' => 'bg-red-100 text-red-800',
            'maintenance' => 'bg-orange-100 text-orange-800',
            'event' => 'bg-blue-100 text-blue-800',
            'facility_update' => 'bg-purple-100 text-purple-800',
            'general' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get formatted content with line breaks
     */
    public function getFormattedContentAttribute(): string
    {
        return nl2br(e($this->content));
    }
}