<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Subject;
use App\Models\Video;
use App\Models\Enrollment;
use App\Models\VideoProgress;
use App\Models\Rating;
use App\Models\Comment;

class ProfessorMetricsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get subjects with metrics
        $subjects = $user->subjects()
            ->withCount([
                'videos as total_videos' => fn($q) => $q->where('status', 'active'),
                'videos as active_videos' => fn($q) => $q->where('status', 'active'),
                'enrollments as total_enrollments',
                'enrollments as active_enrollments' => fn($q) => $q->where('status', 'in_progress'),
                'enrollments as completed_enrollments' => fn($q) => $q->where('status', 'completed'),
            ])
            ->get();

        // Calculate aggregate metrics
        $totalVideos = $user->videos()
            ->where('status', 'active')
            ->count();

        $totalViews = $user->videos()
            ->where('status', 'active')
            ->sum('views_count');

        $totalEnrollments = Enrollment::join('subjects', 'enrollments.subject_id', '=', 'subjects.id')
            ->where('subjects.professor_id', $user->id)
            ->count();

        $totalCompletedEnrollments = Enrollment::join('subjects', 'enrollments.subject_id', '=', 'subjects.id')
            ->where('subjects.professor_id', $user->id)
            ->where('enrollments.status', 'completed')
            ->count();

        $averageRating = Rating::join('videos', 'ratings.video_id', '=', 'videos.id')
            ->where('videos.professor_id', $user->id)
            ->avg('ratings.score');

        $totalComments = Comment::join('videos', 'comments.video_id', '=', 'videos.id')
            ->where('videos.professor_id', $user->id)
            ->where('comments.status', 'published')
            ->count();

        return view('professor.metrics', compact(
            'subjects',
            'totalVideos',
            'totalViews',
            'totalEnrollments',
            'totalCompletedEnrollments',
            'averageRating',
            'totalComments'
        ));
    }
}