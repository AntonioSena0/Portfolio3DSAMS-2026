<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Subject;
use App\Models\Video;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // User statistics
        $totalUsers = User::count();
        $totalStudents = User::where('role', 'student')->count();
        $totalProfessors = User::where('role', 'professor')->count();
        $totalAdmins = User::where('role', 'admin')->count();

        $pendingProfessors = User::where('role', 'professor')
            ->where('status', 'pending')
            ->count();

        $blockedUsers = User::where('status', 'blocked')->count();

        // Content statistics
        $totalSubjects = Subject::count();
        $publishedSubjects = Subject::where('status', 'published')->count();
        $pendingSubjects = Subject::where('status', 'under_review')->count();
        $draftSubjects = Subject::where('status', 'draft')->count();

        $totalVideos = Video::count();
        $activeVideos = Video::where('status', 'active')->count();
        $inactiveVideos = Video::where('status', 'inactive')->count();

        $totalViews = Video::sum('views_count');

        // Recent activity (simplified)
        $recentUsers = User::latest()->take(5)->get();
        $recentSubjects = Subject::with('professor:id,name')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStudents',
            'totalProfessors',
            'totalAdmins',
            'pendingProfessors',
            'blockedUsers',
            'totalSubjects',
            'publishedSubjects',
            'pendingSubjects',
            'draftSubjects',
            'totalVideos',
            'activeVideos',
            'inactiveVideos',
            'totalViews',
            'recentUsers',
            'recentSubjects'
        ));
    }
}