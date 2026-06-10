<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Rating;
use App\Models\Video;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $ratings = Rating::where('user_id', $user->id)
            ->with(['video:id,title,slug', 'video.subject:id,title,slug'])
            ->latest()
            ->paginate(12);

        return view('student.ratings.index', compact('ratings'));
    }

    public function store(Request $request, Video $video)
    {
        // Check if user has watched at least 50% of the video
        $progress = $video->progress()
            ->where('student_id', Auth::id())
            ->first();

        if (!$progress || $progress->watched_seconds < ($video->duration * 0.5)) {
            throw ValidationException::withMessages([
                'score' => 'Você precisa assistir pelo menos 50% do vídeo para poder avaliá-lo.',
            ]);
        }

        $request->validate([
            'score' => ['required', 'integer', 'between:1,5'],
        ]);

        $rating = Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'video_id' => $video->id,
            ],
            [
                'score' => $request->input('score'),
            ]
        );

        // Recalculate subject average rating if needed
        $video->subject->refresh();

        return redirect()->back()
            ->with('status', 'Sua avaliação foi registrada com sucesso!');
    }
}