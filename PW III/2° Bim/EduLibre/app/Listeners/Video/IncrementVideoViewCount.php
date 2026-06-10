<?php

namespace App\Listeners\Video;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Video\VideoWatched;
use App\Models\Video;

class IncrementVideoViewCount
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VideoWatched $event)
    {
        // Increment the view count for the video
        $event->video->increment('views_count');
    }
}