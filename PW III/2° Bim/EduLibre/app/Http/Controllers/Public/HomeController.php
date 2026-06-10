<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\User;
use App\Models\Video;

class HomeController extends Controller
{
    public function index()
    {
        $subjects = Subject::published()
            ->with(['professor:id,name,avatar', 'category:id,name,color'])
            ->withCount(['videos' => fn($q) => $q->active()])
            ->latest('published_at')
            ->take(6)
            ->get();

        $stats = [
            'students' => User::where('role', 'student')->where('status', 'active')->count(),
            'professors' => User::where('role', 'professor')->where('status', 'active')->count(),
            'subjects' => Subject::published()->count(),
            'videos' => Video::active()->count(),
        ];

        return view('public.home', compact('subjects', 'stats'));
    }

    public function about()
    {
        return view('public.about');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
        ]);

        // Here you would typically send an email or store the message
        // For now, we'll just redirect back with a success message

        return redirect()->route('contact')
            ->with('status', 'Sua mensagem foi enviada com sucesso! Entraremos em contato em breve.');
    }
}
