<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = User::professors()
            ->active()
            ->withCount([
                'subjects as subjects_count' => fn($q) => $q->published(),
                'videos as videos_count' => fn($q) => $q->whereHas('subject', fn($q) => $q->published())
            ]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('specialty', 'like', "%{$search}%");
            });
        }

        // Specialty filter
        if ($request->filled('specialty')) {
            $query->where('specialty', 'like', "%{$request->input('specialty')}%");
        }

        $teachers = $query->paginate(12)->withQueryString();

        return view('public.teachers', compact('teachers'));
    }
}
