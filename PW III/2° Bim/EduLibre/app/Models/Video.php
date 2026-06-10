<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Video extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'subject_id',
        'professor_id',
        'title',
        'slug',
        'description',
        'url',
        'duration',
        'order',
        'status',
        'views_count',
    ];

    /**
     * Get the subject that owns the video.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the professor who owns the video.
     */
    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    /**
     * Get the progress records for the video.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(VideoProgress::class);
    }

    /**
     * Get the comments for the video.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the ratings for the video.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the formatted duration of the video (HH:MM:SS).
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration) {
            return '00:00:00';
        }

        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    /**
     * Get the average rating for the video.
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->ratings()->avg('score') ?: 0.0;
    }

    /**
     * Scope a query to only include active videos.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to order videos by their order field.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($video) {
            if (empty($video->slug)) {
                $video->slug = Str::slug($video->title);
                // Ensure uniqueness of the slug within the subject
                $count = Video::where('subject_id', $video->subject_id)
                    ->where('slug', 'like', $video->slug . '%')
                    ->count();

                if ($count > 0) {
                    $video->slug .= '-' . ($count + 1);
                }
            }
        });
    }
}
