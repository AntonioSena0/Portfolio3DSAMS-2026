<?php

namespace App\Listeners\Subject;

use App\Events\Subject\SubjectSubmittedForReview;
use App\Mail\SubjectNotificationMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfSubjectReview
{
    public function handle(SubjectSubmittedForReview $event): void
    {
        User::admins()->each(function (User $admin) use ($event) {
            Mail::to($admin->email)->send(new SubjectNotificationMail($event->subject, 'submitted_for_review'));
        });
    }
}
