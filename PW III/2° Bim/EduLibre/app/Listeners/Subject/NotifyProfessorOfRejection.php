<?php

namespace App\Listeners\Subject;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Subject\SubjectRejected;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubjectNotificationMail;

class NotifyProfessorOfRejection
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
    public function handle(SubjectRejected $event)
    {
        // Notify professor about subject rejection
        Mail::to($event->subject->professor->email)->send(new SubjectNotificationMail($event->subject, 'rejected'));
    }
}