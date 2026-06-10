<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function show(string $slug)
    {
        $subject = Subject::published()
            ->where('slug', $slug)
            ->with(['professor:id,name,bio,specialty', 'category:id,name,color'])
            ->withCount(['activeVideos'])
            ->firstOrFail();

        $videos = $subject->activeVideos()
            ->withCount(['ratings', 'comments'])
            ->get();

        $enrollment = null;

        if (auth()->check()) {
            $userId = auth()->id();
            $videos->each(function($video) use ($userId) {
                $progress = $video->progress()
                    ->where('student_id', $userId)
                    ->first();

                $video->setRelation('user_progress', $progress);
            });

            $enrollment = $subject->enrollments()
                ->where('student_id', $userId)
                ->first();
        }

        return view('public.catalog.show', compact('subject', 'videos', 'enrollment'));
    }
}
