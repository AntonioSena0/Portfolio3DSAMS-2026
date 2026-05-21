<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function like(Post $post)
    {
        $user = auth()->user();

        $user->likedPosts()->toggle($post->id);

        $likesCount = $post->fresh()->likedBy()->count();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'likes' => $likesCount,
                'liked' => $user->likedPosts()->where('post_id', $post->id)->exists()
            ]);
        }

        return back()->with('success', 'Post curtido');
    }

    public function unlike(Post $post)
    {
        $user = auth()->user();

        $user->likedPosts()->detach($post->id);

        $likesCount = $post->fresh()->likedBy()->count();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'likes' => $likesCount,
                'liked' => false
            ]);
        }

        return back()->with('success', 'Curtida removida');
    }
}
