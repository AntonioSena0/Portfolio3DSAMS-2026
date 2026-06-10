<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Subject;
use App\Models\Category;
use App\Events\Subject\SubjectSubmittedForReview;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Jobs\SendSubjectStatusNotification;

class ProfessorSubjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $subjects = $user->subjects()
            ->withCount([
                'videos as videos_count' => fn($q) => $q->where('status', 'active'),
                'enrollments as enrollments_count'
            ])
            ->orderBy('status')
            ->latest()
            ->paginate(12);

        return view('professor.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $this->authorize('create', Subject::class);

        $categories = Category::all(['id', 'name', 'color']);
        return view('professor.subjects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Subject::class);

        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'exists:categories,id'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = $request->only('title', 'description', 'category_id');

        // Handle cover upload
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('subjects/covers', 'public');
            $data['cover'] = $coverPath;
        }

        $subject = Auth::user()->subjects()->create($data);

        return redirect()->route('professor.subjects.edit', $subject)
            ->with('status', 'Matéria criada com sucesso! Agora você pode adicionar vídeos.');
    }

    public function show(Subject $subject)
    {
        $this->authorize('view', $subject);

        $subject->load([
            'videos' => fn($q) => $q->where('status', 'active')->orderBy('order'),
            'category:id,name,color',
            'professor:id,name,avatar'
        ]);

        return view('professor.subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        $this->authorize('update', $subject);

        $categories = Category::all(['id', 'name', 'color']);
        return view('professor.subjects.edit', compact('subject', 'categories'));
    }

    public function update(Request $request, Subject $subject)
    {
        $this->authorize('update', $subject);

        $request->validate([
            'title' => ['required', 'string', 'min:5', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'exists:categories,id'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = $request->only('title', 'description', 'category_id');

        // Handle cover upload
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($subject->cover) {
                \Storage::disk('public')->delete($subject->cover);
            }
            $coverPath = $request->file('cover')->store('subjects/covers', 'public');
            $data['cover'] = $coverPath;
        } elseif ($request->input('remove_cover') == 'true') {
            // Remove cover if requested
            if ($subject->cover) {
                \Storage::disk('public')->delete($subject->cover);
            }
            $data['cover'] = null;
        }

        $subject->update($data);

        return redirect()->route('professor.subjects.edit', $subject)
            ->with('status', 'Matéria atualizada com sucesso!');
    }

    public function destroy(Subject $subject)
    {
        $this->authorize('delete', $subject);

        // Delete cover image if exists
        if ($subject->cover) {
            \Storage::disk('public')->delete($subject->cover);
        }

        $subject->delete();

        return redirect()->route('professor.subjects.index')
            ->with('status', 'Matéria excluída com sucesso!');
    }

    public function submit(Subject $subject)
    {
        $this->authorize('update', $subject);

        // Validate that subject has at least one active video
        $activeVideosCount = $subject->videos()
            ->where('status', 'active')
            ->count();

        if ($activeVideosCount === 0) {
            throw ValidationException::withMessages([
                'submit' => 'A matéria precisa ter pelo menos um vídeo ativo para ser submetida à revisão.',
            ]);
        }

        $subject->update(['status' => 'under_review']);

        event(new SubjectSubmittedForReview($subject));

        return redirect()->route('professor.subjects.edit', $subject)
            ->with('status', 'Matéria submetida para revisão com sucesso!');
    }
}
