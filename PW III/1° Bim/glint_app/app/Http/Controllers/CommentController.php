<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function makeComment(Request $request, Post $post){

        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $comment = $post->comments()->create([
            'body' => $data['body'],
            'user_id' => auth()->id()
        ]);

        $comment->load('user');

        return response()->json([
            'id' => $comment->id,
            'body' => $comment->body,
            'created_at' => $comment->created_at,
            'user' => [
                'username' => $comment->user->username
            ]
        ], 201);


    }

    public function delete(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Você não pode deletar este comentário.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Comentário deletado com sucesso.');
    }


}
