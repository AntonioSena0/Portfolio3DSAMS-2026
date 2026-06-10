<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Subject;
use App\Models\Video;
use App\Models\User;

class ProfessorDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get subjects by status
        $subjectsByStatus = $user->subjects()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Get total videos
        $totalVideos = $user->videos()
            ->where('status', 'active')
            ->count();

        // Get total enrollments in professor's subjects
        $totalEnrollments = \DB::table('enrollments')
            ->join('subjects', 'enrollments.subject_id', '=', 'subjects.id')
            ->where('subjects.professor_id', $user->id)
            ->count();

        // Get total views on professor's videos
        $totalViews = $user->videos()
            ->where('status', 'active')
            ->sum('views_count');

        // Get recent subjects
        $recentSubjects = $user->subjects()
            ->withCount([
                'videos as videos_count' => fn($q) => $q->where('status', 'active'),
                'enrollments as enrollments_count'
            ])
            ->latest()
            ->take(5)
            ->get();

        return view('professor.dashboard', compact(
            'subjectsByStatus',
            'totalVideos',
            'totalEnrollments',
            'totalViews',
            'recentSubjects'
        ));
    }
}