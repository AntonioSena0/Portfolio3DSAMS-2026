<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\VideoProgress;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $continueWatching = VideoProgress::where('student_id', $user->id)
            ->where('watched_seconds', '>', 0)
            ->with(['video:id,title,slug,duration,subject_id', 'video.subject:id,title,slug,status'])
            ->whereHas('video.subject', fn($query) => $query->where('status', 'published'))
            ->orderByDesc('updated_at')
            ->take(3)
            ->get();

        $enrolledSubjects = Enrollment::where('student_id', $user->id)
            ->where('status', 'in_progress')
            ->whereHas('subject', fn($query) => $query->where('status', 'published'))
            ->with(['subject:id,title,slug,professor_id,status', 'subject.professor:id,name'])
            ->latest()
            ->take(4)
            ->get();

        $completedSubjects = Enrollment::where('student_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('subject', fn($query) => $query->where('status', 'published'))
            ->with(['subject:id,title,slug,professor_id,status', 'subject.professor:id,name'])
            ->latest()
            ->take(4)
            ->get();

        $stats = [
            'in_progress' => Enrollment::where('student_id', $user->id)
                ->where('status', 'in_progress')
                ->count(),
            'completed' => Enrollment::where('student_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'videos_watched' => VideoProgress::where('student_id', $user->id)
                ->where('is_completed', true)
                ->count(),
            'study_hours' => VideoProgress::where('student_id', $user->id)
                ->sum('watched_seconds') / 3600
        ];

        return view('student.dashboard', compact(
            'continueWatching',
            'enrolledSubjects',
            'completedSubjects',
            'stats'
        ));
    }
}
