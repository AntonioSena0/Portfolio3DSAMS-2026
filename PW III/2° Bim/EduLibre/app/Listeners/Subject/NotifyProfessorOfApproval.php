<?php

namespace App\Listeners\Subject;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Subject\SubjectPublished;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubjectNotificationMail;

class NotifyProfessorOfApproval
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
    public function handle(SubjectPublished $event)
    {
        // Notify professor about subject approval
        Mail::to($event->subject->professor->email)->send(new SubjectNotificationMail($event->subject, 'approved'));
    }
}