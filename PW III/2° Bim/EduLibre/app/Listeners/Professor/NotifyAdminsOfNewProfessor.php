<?php

namespace App\Listeners\Professor;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Professor\ProfessorRegistered;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProfessorWelcomeMail;
use App\Models\User;

class NotifyAdminsOfNewProfessor
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
        // Notify admins about new professor registration
        $admins = User::admins()->get();

        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ProfessorWelcomeMail($event->professor, 'new_registration'));
        }
    }
}