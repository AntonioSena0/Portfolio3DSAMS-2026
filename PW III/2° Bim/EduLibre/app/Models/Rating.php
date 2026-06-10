<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Rating extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'video_id',
        'score',
    ];

    /**
     * Get the user who gave the rating.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the video that was rated.
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rating) {
            // Ensure the score is between 1 and 5
            if ($rating->score < 1 || $rating->score > 5) {
                throw new \InvalidArgumentException('Score must be between 1 and 5.');
            }
        });

        static::updating(function ($rating) {
            // Ensure the score is between 1 and 5
            if ($rating->score < 1 || $rating->score > 5) {
                throw new \InvalidArgumentException('Score must be between 1 and 5.');
            }
        });
    }
}