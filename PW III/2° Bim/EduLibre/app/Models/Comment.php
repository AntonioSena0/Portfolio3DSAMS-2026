<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'video_id',
        'content',
        'status',
    ];

    /**
     * Get the user who posted the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the video that the comment is for.
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Scope a query to only include published comments.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope a query to only include hidden comments.
     */
    public function scopeHidden(Builder $query): Builder
    {
        return $query->where('status', 'hidden');
    }

    /**
     * Scope a query to only include flagged comments.
     */
    public function scopeFlagged(Builder $query): Builder
    {
        return $query->where('status', 'flagged');
    }
}