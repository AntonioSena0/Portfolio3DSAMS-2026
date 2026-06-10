<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Subject;
use App\Models\Video;
use App\Models\Comment;
use App\Policies\SubjectPolicy;
use App\Policies\VideoPolicy;
use App\Policies\CommentPolicy;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Subject::class => SubjectPolicy::class,
        Video::class => VideoPolicy::class,
        Comment::class => CommentPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define gates for role-based access
        Gate::define('access-admin-panel', fn(User $user) => $user->isAdmin());
        Gate::define('access-professor-panel', fn(User $user) => $user->isProfessor() && $user->isActive());
        Gate::define('approve-content', fn(User $user) => $user->isAdmin());
        Gate::define('manage-categories', fn(User $user) => $user->isAdmin());
    }
}
