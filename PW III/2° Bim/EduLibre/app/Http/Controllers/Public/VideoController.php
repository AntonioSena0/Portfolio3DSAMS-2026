<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\Subject;

class VideoController extends Controller
{
    public function show(string $subjectSlug, string $videoSlug)
    {
        $subject = Subject::published()
            ->where('slug', $subjectSlug)
            ->with(['professor:id,name', 'category:id,name,color'])
            ->firstOrFail();

        $video = $subject->videos()
            ->where('slug', $videoSlug)
            ->where('status', 'active')
            ->with(['professor:id,name'])
            ->firstOrFail();

        $video->increment('views_count');

        $userProgress = null;
        $userRating = null;
        $enrollment = null;

        if (auth()->check()) {
            $userProgress = $video->progress()
                ->where('student_id', auth()->id())
                ->first();

            $userRating = $video->ratings()
                ->where('user_id', auth()->id())
                ->first();

            $enrollment = $subject->enrollments()
                ->firstOrCreate(
                    ['student_id' => auth()->id()],
                    ['status' => 'in_progress', 'progress_percent' => 0]
                );
        }

        $prevVideo = $subject->videos()
            ->where('order', '<', $video->order)
            ->where('status', 'active')
            ->orderByDesc('order')
            ->first();

        $nextVideo = $subject->videos()
            ->where('order', '>', $video->order)
            ->where('status', 'active')
            ->orderBy('order')
            ->first();

        $comments = $video->comments()
            ->where('status', 'published')
            ->with(['user:id,name'])
            ->latest()
            ->take(10)
            ->get();

        $averageRating = $video->ratings()->avg('score');

        return view('public.video.show', compact(
            'subject',
            'video',
            'userProgress',
            'userRating',
            'enrollment',
            'prevVideo',
            'nextVideo',
            'comments',
            'averageRating'
        ));
    }
}
