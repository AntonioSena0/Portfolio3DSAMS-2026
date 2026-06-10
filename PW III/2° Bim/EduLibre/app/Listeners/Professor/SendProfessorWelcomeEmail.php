<?php

namespace App\Listeners\Professor;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Professor\ProfessorRegistered;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProfessorWelcomeMail;

class SendProfessorWelcomeEmail
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
    public function handle(ProfessorRegistered $event)
    {
        // Send welcome email to the new professor
        Mail::to($event->professor->email)->send(new ProfessorWelcomeMail($event->professor, 'welcome'));
    }
}