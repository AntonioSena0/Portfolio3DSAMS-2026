<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Category;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::published()
            ->with(['professor:id,name,avatar', 'category:id,name,color'])
            ->withCount(['videos' => fn($q) => $q->active()]);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        switch ($sort) {
            case 'most-videos':
                $query->orderByDesc('active_videos_count');
                break;
            case 'title-asc':
                $query->orderBy('title');
                break;
            case 'title-desc':
                $query->orderByDesc('title');
                break;
            default: // latest
                $query->latest('published_at');
                break;
        }

        $subjects = $query->paginate(12)->withQueryString();
        $categories = Category::all(['id', 'name', 'color']);

        return view('public.catalog.index', compact('subjects', 'categories'));
    }
}