<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['error' => 'Não pode seguir a si mesmo'], 400);
            }
            return back()->with('error', 'Não pode seguir a si mesmo');
        }

        $authUser->following()->syncWithoutDetaching([$user->id]);

        $isFollowing = $authUser->following()->where('followed_id', $user->id)->exists();
        $followersCount = $user->followers()->count();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'following' => $isFollowing,
                'followersCount' => $followersCount
            ]);
        }

        return back()->with('success', 'Agora você segue ' . $user->username);
    }

    public function unfollow(User $user)
    {
        $authUser = auth()->user();

        $authUser->following()->detach($user->id);

        $isFollowing = $authUser->following()->where('followed_id', $user->id)->exists();
        $followersCount = $user->followers()->count();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'following' => $isFollowing,
                'followersCount' => $followersCount
            ]);
        }

        return back()->with('success', 'Você deixou de seguir ' . $user->username);
    }
}