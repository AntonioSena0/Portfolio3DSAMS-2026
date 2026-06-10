<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        'App\Events\Professor\ProfessorRegistered' => [
            'App\Listeners\Professor\NotifyAdminsOfNewProfessor',
            'App\Listeners\Professor\SendProfessorWelcomeEmail',
        ],
        'App\Events\Professor\ProfessorApproved' => [
            'App\Listeners\Professor\SendProfessorApprovedEmail',
        ],
        'App\Events\Subject\SubjectSubmittedForReview' => [
            'App\Listeners\Subject\NotifyAdminOfSubjectReview',
        ],
        'App\Events\Subject\SubjectPublished' => [
            'App\Listeners\Subject\NotifyProfessorOfApproval',
        ],
        'App\Events\Subject\SubjectRejected' => [
            'App\Listeners\Subject\NotifyProfessorOfRejection',
        ],
        'App\Events\Video\VideoWatched' => [
            'App\Listeners\Video\IncrementVideoViewCount',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}