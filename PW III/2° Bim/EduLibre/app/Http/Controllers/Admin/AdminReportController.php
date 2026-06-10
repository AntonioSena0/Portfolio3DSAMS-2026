<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Subject;
use App\Models\Video;
use App\Models\Enrollment;
use App\Models\VideoProgress;
use App\Models\Rating;
use App\Models\Comment;

class AdminReportController extends Controller
{
    public function index()
    {
        // User growth over time (last 6 months)
        $userGrowth = User::selectRaw('COUNT(*) as count, MONTH(created_at) as month, YEAR(created_at) as year')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // User registration by role
        $registrationsByRole = User::selectRaw('COUNT(*) as count, role')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        // Content statistics
        $contentStats = [
            'subjects' => Subject::count(),
            'published_subjects' => Subject::where('status', 'published')->count(),
            'videos' => Video::count(),
            'active_videos' => Video::where('status', 'active')->count(),
        ];

        // Engagement metrics
        $engagementStats = [
            'total_views' => Video::sum('views_count'),
            'total_enrollments' => Enrollment::count(),
            'completed_enrollments' => Enrollment::where('status', 'completed')->count(),
            'total_ratings' => Rating::count(),
            'total_comments' => Comment::where('status', 'published')->count(),
        ];

        // Popular subjects
        $popularSubjects = Subject::withCount('enrollments')
            ->where('status', 'published')
            ->orderByDesc('enrollments_count')
            ->take(10)
            ->get(['id', 'title', 'slug', 'enrollments_count']);

        // Most viewed videos
        $mostViewedVideos = Video::with(['subject:id,title', 'professor:id,name'])
            ->where('status', 'active')
            ->orderByDesc('views_count')
            ->take(10)
            ->get(['id', 'title', 'slug', 'views_count']);

        // Top rated subjects (with at least 5 ratings)
        $topRatedSubjects = Subject::with([
            'videos' => fn($q) => $q->selectRaw('video_id, AVG(score) as avg_rating')
        ])
            ->where('status', 'published')
            ->havingRaw('COUNT(ratings.id) >= 5')
            ->orderByDesc('avg_rating')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'userGrowth',
            'registrationsByRole',
            'contentStats',
            'engagementStats',
            'popularSubjects',
            'mostViewedVideos',
            'topRatedSubjects'
        ));
    }
}