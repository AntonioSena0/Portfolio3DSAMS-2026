<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Subject extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'professor_id',
        'category_id',
        'title',
        'slug',
        'description',
        'cover',
        'status',
        'rejection_reason',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the professor who owns the subject.
     */
    public function professor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    /**
     * Get the category of the subject.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the videos for the subject.
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    /**
     * Get the enrollments for the subject.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get the active videos for the subject.
     */
    public function activeVideos(): HasMany
    {
        return $this->hasMany(Video::class)->where('status', 'active')->orderBy('order');
    }

    /**
     * Scope a query to only include published subjects.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include draft subjects.
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include subjects under review.
     */
    public function scopeUnderReview(Builder $query): Builder
    {
        return $query->where('status', 'under_review');
    }

    /**
     * Check if the subject is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Check if the subject is a draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if the subject is under review.
     */
    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    /**
     * Get the cover URL for the subject.
     */
    public function getCoverUrlAttribute(): string
    {
        if (!$this->cover) {
            return asset('images/default-subject-cover.png'); // You might want to set a default image
        }

        return asset('storage/' . $this->cover);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subject) {
            if (empty($subject->slug)) {
                $subject->slug = Str::slug($subject->title);
                // Ensure uniqueness of the slug
                $count = Subject::withTrashed()->where('slug', 'like', $subject->slug . '%')->count();
                if ($count > 0) {
                    $subject->slug .= '-' . ($count + 1);
                }
            }
        });

        static::updating(function ($subject) {
            if ($subject->isDirty('status') && $subject->status === 'published') {
                $subject->published_at = now();
            }
        });
    }
}