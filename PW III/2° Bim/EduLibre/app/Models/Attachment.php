<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Attachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'subject_id',
        'video_id',
        'name',
        'path',
        'mime_type',
        'size',
    ];

    /**
     * Get the subject that the attachment belongs to (if any).
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the video that the attachment belongs to (if any).
     */
    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}