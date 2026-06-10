<?php

namespace App\Jobs;

use App\Mail\SubjectNotificationMail;
use App\Models\Subject;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendSubjectStatusNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Subject $subject,
        public string $type
    ) {
    }

    public function handle(): void
    {
        $this->subject->loadMissing('professor:id,email');

        if ($this->subject->professor?->email) {
            Mail::to($this->subject->professor->email)
                ->send(new SubjectNotificationMail($this->subject, $this->type));
        }
    }
}
