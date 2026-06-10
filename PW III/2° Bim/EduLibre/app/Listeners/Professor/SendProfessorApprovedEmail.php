<?php

namespace App\Listeners\Professor;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Professor\ProfessorApproved;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProfessorWelcomeMail;

class SendProfessorApprovedEmail
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
    public function handle(ProfessorApproved $event)
    {
        // Send approval email to the professor
        Mail::to($event->professor->email)->send(new ProfessorWelcomeMail($event->professor, 'approved'));
    }
}