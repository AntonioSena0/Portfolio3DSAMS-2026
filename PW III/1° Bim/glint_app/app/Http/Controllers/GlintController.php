<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PostController;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class GlintController extends Controller
{
    public function index(){

        return view("landing");

    }

    public function indexLogin(){

        return view("login");

    }

    public function indexRegister(){

        return view("register");

    }

    public function indexHomePosts() {

        $user = Auth::user();
        $feedPosts = app(PostController::class)->findAll();

        return view('home-posts', compact('user', 'feedPosts'));
    }

    public function indexHomeSearch(Request $request){

        $user = Auth::user();
        $term = $request->input('q');

        if (empty($term)) {
            $searchPosts = collect();
            $searchUsers = collect();
        } else {
            $searchPosts = app(PostController::class)->search($request);
            $searchUsers = app(UserController::class)->search($request);
        }

        return view('home-search', compact('user', 'searchPosts', 'searchUsers', 'term'));
    }

    public function indexHomeCreatePost(){

        $user = Auth::user();

        return view('home-create-post', compact('user'));
    }

    public function profile(User $user = null)
    {
        $authUser = auth()->user();

        if ($user === null) {
            $user = $authUser;
        }

        $posts = $user->posts()->latest()->get();
        $isFollowing = false;
        $isOwnProfile = false;

        if ($authUser) {
            $isFollowing = $authUser->following()->where('followed_id', $user->id)->exists();
            $isOwnProfile = $authUser->id === $user->id;
        }

        return view('perfil', [
            'user' => $user,
            'posts' => $posts,
            'isFollowing' => $isFollowing,
            'isOwnProfile' => $isOwnProfile
        ]);
    }
}
