<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Auth;
use Illuminate\Http\Request;

class PostController extends Controller
{

    public function findAll()
    {
        return Post::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->get();
    }

    public function search(Request $request){

        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:200']
        ]);

        $term = $data['q'] ?? null;

        if (empty($term)) {
            return collect();
        }

        return Post::with('user')
            ->where('subject','like','%'.$term.'%')
            ->latest()
            ->limit(10)
            ->get();

    }

    public function create(Request $request){
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:4096'],
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('imagens', 'public');
        }

        $post = Post::create(['subject' => $data['subject'], 'image' => $path, 'user_id' => auth()->id()]);

        return redirect()->route('perfil')->with('success','Post criado com sucesso');
    }

    public function delete(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Você não pode deletar este post.');
        }

        $post->delete();

        return redirect()->back()->with('success', 'Post deletado com sucesso.');
    }
}
