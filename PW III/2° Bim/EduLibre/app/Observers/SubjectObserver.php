<?php

namespace App\Observers;

use App\Models\Subject;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class SubjectObserver
{
    /**
     * Handle the Subject "creating" event.
     */
    public function creating(Subject $subject)
    {
        if (empty($subject->slug)) {
            $subject->slug = Str::slug($subject->title);
            // Ensure uniqueness of the slug
            $count = Subject::withTrashed()->where('slug', 'like', $subject->slug . '%')->count();
            if ($count > 0) {
                $subject->slug .= '-' . ($count + 1);
            }
        }
    }

    /**
     * Handle the Subject "updating" event.
     */
    public function updating(Subject $subject)
    {
        if ($subject->isDirty('status') && $subject->status === 'published') {
            $subject->published_at = now();
            // Clear cache when a subject is published
            Cache::forget('catalog.published');
        }
    }
}