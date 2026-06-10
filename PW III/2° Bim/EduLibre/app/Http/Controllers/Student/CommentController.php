<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Comment;
use App\Models\Video;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $comments = Comment::where('user_id', $user->id)
            ->with(['video:id,title,slug', 'video.subject:id,title,slug'])
            ->latest()
            ->paginate(12);

        return view('student.comments.index', compact('comments'));
    }

    public function store(Request $request, Video $video)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'video_id' => $video->id,
            'content' => strip_tags($request->input('content')),
            'status' => 'published',
        ]);

        return redirect()->back()
            ->with('status', 'Comentário adicionado com sucesso!');
    }

    public function destroy(Comment $comment)
    {
        // Ensure the comment belongs to the authenticated user
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return redirect()->back()
            ->with('status', 'Comentário excluído com sucesso!');
    }
}