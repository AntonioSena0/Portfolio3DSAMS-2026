<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Video;
use App\Models\Subject;
use Illuminate\Validation\ValidationException;

class AdminVideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Search by title or description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->input('subject_id'));
        }

        // Filter by professor
        if ($request->filled('professor_id')) {
            $query->where('professor_id', $request->input('professor_id'));
        }

        $videos = $query->with([
            'subject:id,title,slug',
            'professor:id,name,avatar',
        ])
            ->withCount([
                'comments as comments_count' => fn($q) => $q->where('status', 'published'),
                'ratings as ratings_count'
            ])
            ->orderBy('updated_at', 'desc')
            ->paginate(12);

        return view('admin.videos.index', compact('videos'));
    }

    public function show(Video $video)
    {
        $video->load([
            'subject:id,title,slug',
            'professor:id,name,avatar',
            'comments' => fn($q) => $q->where('status', 'published')->with('user:id,name,avatar'),
            'ratings'
        ]);

        return view('admin.videos.show', compact('video'));
    }

    public function destroy(Video $video)
    {
        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('status', 'Vídeo excluído com sucesso!');
    }
}