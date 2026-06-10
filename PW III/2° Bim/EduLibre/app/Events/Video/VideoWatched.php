<?php

namespace App\Events\Video;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Video;
use App\Models\User;

class VideoWatched
{
    use Dispatchable, SerializesModels;

    public Video $video;
    public User $user;
    public int $watchedSeconds;

    /**
     * Create a new event instance.
     */
    public function __construct(Video $video, User $user, int $watchedSeconds)
    {
        $this->video = $video;
        $this->user = $user;
        $this->watchedSeconds = $watchedSeconds;
    }
}