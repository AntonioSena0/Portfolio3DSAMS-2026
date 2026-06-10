<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class VideoProgress extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'video_id',
        'watched_seconds',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    /**
     * Get the student who owns the progress.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the video that the progress is for.
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Scope a query to only include completed progress.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope a query to only include incomplete progress.
     */
    public function scopeIncomplete(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }
}