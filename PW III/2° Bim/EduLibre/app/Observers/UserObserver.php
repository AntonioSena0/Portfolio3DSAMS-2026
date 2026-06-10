<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user)
    {
        // Normalize data when creating
        if (!empty($user->name)) {
            $user->name = trim($user->name);
        }

        if (!empty($user->email)) {
            $user->email = strtolower(trim($user->email));
        }

        if (!empty($user->bio)) {
            $user->bio = trim($user->bio);
        }

        if (!empty($user->specialty)) {
            $user->specialty = trim($user->specialty);
        }
    }

    /**
     * Handle the User "updating" event.
     */
    public function updating(User $user)
    {
        // Normalize data when updating
        if (!empty($user->name)) {
            $user->name = trim($user->name);
        }

        if (!empty($user->email)) {
            $user->email = strtolower(trim($user->email));
        }

        if (!empty($user->bio)) {
            $user->bio = trim($user->bio);
        }

        if (!empty($user->specialty)) {
            $user->specialty = trim($user->specialty);
        }
    }
}