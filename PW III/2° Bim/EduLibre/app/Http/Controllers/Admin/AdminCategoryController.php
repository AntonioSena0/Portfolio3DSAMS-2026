<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Category;
use App\Models\Subject;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('subjects')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
        ]);

        $data = $request->only('name', 'description', 'color');
        $data['slug'] = Str::slug($request->input('name'));

        // Ensure slug is unique
        $count = Category::where('slug', 'like', $data['slug'] . '%')->count();
        if ($count > 0) {
            $data['slug'] .= '-' . ($count + 1);
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('status', 'Categoria criada com sucesso!');
    }

    public function show(Category $category)
    {
        $category->loadCount('subjects');
        $category->load(['subjects:id,title,slug,status']);

        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
        ]);

        $data = $request->only('name', 'description', 'color');

        // Update slug if name changed
        if ($request->input('name') !== $category->name) {
            $newSlug = Str::slug($request->input('name'));
            $count = Category::where('slug', 'like', $newSlug . '%')
                ->where('id', '!=', $category->id)
                ->count();

            $data['slug'] = $count > 0 ? $newSlug . '-' . ($count + 1) : $newSlug;
        }

        $category->update($data);

        return redirect()->route('admin.categories.show', $category)
            ->with('status', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        // Check if category has subjects
        if ($category->subjects()->exists()) {
            throw ValidationException::withMessages([
                'delete' => 'Não é possível excluir uma categoria que possui matérias associadas.',
            ]);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('status', 'Categoria excluída com sucesso!');
    }
}
