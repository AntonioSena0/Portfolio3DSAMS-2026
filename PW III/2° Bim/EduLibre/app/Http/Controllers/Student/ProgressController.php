<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\VideoProgress;
use App\Models\Video;
use App\Models\Enrollment;
use App\Jobs\UpdateEnrollmentProgress;

class ProgressController extends Controller
{
    public function history()
    {
        $user = Auth::user();

        $history = VideoProgress::where('student_id', $user->id)
            ->where('is_completed', true)
            ->with(['video:id,title,slug,duration', 'video.subject:id,title,slug'])
            ->orderByDesc('completed_at')
            ->paginate(12);

        return view('student.history', compact('history'));
    }

    public function continueWatching()
    {
        $user = Auth::user();

        $continueWatching = VideoProgress::where('student_id', $user->id)
            ->where('watched_seconds', '>', 0)
            ->where('is_completed', false)
            ->with(['video:id,title,slug,duration', 'video.subject:id,title,slug'])
            ->orderByDesc('updated_at')
            ->take(6)
            ->get();

        return view('student.continue-watching', compact('continueWatching'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'video_id' => ['required', 'exists:videos,id'],
            'watched_seconds' => ['required', 'integer', 'min:0'],
        ]);

        $user = Auth::user();
        $video = Video::findOrFail($request->input('video_id'));

        // Save progress
        $progress = VideoProgress::updateOrCreate(
            [
                'student_id' => $user->id,
                'video_id' => $video->id,
            ],
            [
                'watched_seconds' => $request->input('watched_seconds'),
                'is_completed' => $request->input('watched_seconds') >= ($video->duration * 0.9),
                'completed_at' => $request->input('watched_seconds') >= ($video->duration * 0.9) ? now() : null,
            ]
        );

        // Update enrollment progress
        UpdateEnrollmentProgress::dispatch($user, $video->subject);

        return response()->json(['success' => true]);
    }
}