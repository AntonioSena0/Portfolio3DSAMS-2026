<?php

namespace App\Events\Subject;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Subject;

class SubjectPublished
{
    use Dispatchable, SerializesModels;

    public Subject $subject;

    /**
     * Create a new event instance.
     */
    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
    }
}