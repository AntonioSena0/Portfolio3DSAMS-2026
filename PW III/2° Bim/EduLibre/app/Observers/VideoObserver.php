<?php

namespace App\Observers;

use App\Models\Video;
use Illuminate\Support\Str;

class VideoObserver
{
    /**
     * Handle the Video "creating" event.
     */
    public function creating(Video $video)
    {
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
    }

    /**
     * Handle the Video "updating" event.
     */
    public function updating(Video $video)
    {
        // Additional validation or processing could go here
    }
}