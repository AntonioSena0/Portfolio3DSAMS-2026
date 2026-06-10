<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Video;
use App\Models\Subject;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProfessorVideoController extends Controller
{
    public function index(Subject $subject)
    {
        $this->authorize('update', $subject);

        $videos = $subject->videos()
            ->orderBy('order')
            ->get();

        return view('professor.videos.index', compact('subject', 'videos'));
    }

    public function create(Subject $subject)
    {
        $this->authorize('update', $subject);

        // Get the next order number
        $nextOrder = $subject->videos()->max('order') + 1;
        if (!$nextOrder) {
            $nextOrder = 1;
        }

        return view('professor.videos.create', compact('subject', 'nextOrder'));
    }

    public function store(Request $request, Subject $subject)
    {
        $this->authorize('update', $subject);

        $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'url' => ['required', 'url', 'max:500'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // Check if order already exists for this subject
        $existing = $subject->videos()
            ->where('order', $request->input('order'))
            ->exists();

        if ($existing) {
            // Shift videos with order >= new order
            $subject->videos()
                ->where('order', '>=', $request->input('order'))
                ->increment('order');
        }

        $data = $request->only('title', 'description', 'url', 'duration', 'order', 'status');
        $data['slug'] = Str::slug($request->input('title'));
        $data['professor_id'] = Auth::id();

        // Ensure slug is unique within the subject
        $count = $subject->videos()
            ->where('slug', 'like', $data['slug'] . '%')
            ->count();

        if ($count > 0) {
            $data['slug'] .= '-' . ($count + 1);
        }

        $video = $subject->videos()->create($data);

        return redirect()->route('professor.videos.edit', [$subject, $video])
            ->with('status', 'Vídeo criado com sucesso!');
    }

    public function show(Subject $subject, Video $video)
    {
        $this->ensureVideoBelongsToSubject($subject, $video);
        $this->authorize('view', $video);

        $video->load(['subject:id,title,slug', 'professor:id,name,avatar']);

        return view('professor.videos.show', compact('subject', 'video'));
    }

    public function edit(Subject $subject, Video $video)
    {
        $this->ensureVideoBelongsToSubject($subject, $video);
        $this->authorize('update', $video);

        return view('professor.videos.edit', compact('subject', 'video'));
    }

    public function update(Request $request, Subject $subject, Video $video)
    {
        $this->ensureVideoBelongsToSubject($subject, $video);
        $this->authorize('update', $video);

        $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'url' => ['required', 'url', 'max:500'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'order' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // Handle order change
        $newOrder = $request->input('order');
        $oldOrder = $video->order;

        if ($newOrder != $oldOrder) {
            if ($newOrder > $oldOrder) {
                // Moving down - shift videos between oldOrder+1 and newOrder up
                $subject->videos()
                    ->where('order', '>', $oldOrder)
                    ->where('order', '<=', $newOrder)
                    ->where('id', '!=', $video->id)
                    ->decrement('order');
            } else {
                // Moving up - shift videos between newOrder and oldOrder-1 down
                $subject->videos()
                    ->where('order', '>=', $newOrder)
                    ->where('order', '<', $oldOrder)
                    ->where('id', '!=', $video->id)
                    ->increment('order');
            }
        }

        $data = $request->only('title', 'description', 'url', 'duration', 'order', 'status');

        // Update slug if title changed
        if ($request->input('title') !== $video->title) {
            $newSlug = Str::slug($request->input('title'));
            $count = $subject->videos()
                ->where('slug', 'like', $newSlug . '%')
                ->where('id', '!=', $video->id)
                ->count();

            $data['slug'] = $count > 0 ? $newSlug . '-' . ($count + 1) : $newSlug;
        }

        $video->update($data);

        return redirect()->route('professor.videos.edit', [$subject, $video])
            ->with('status', 'Vídeo atualizado com sucesso!');
    }

    public function destroy(Subject $subject, Video $video)
    {
        $this->ensureVideoBelongsToSubject($subject, $video);
        $this->authorize('delete', $video);

        $videoOrder = $video->order;
        $video->delete();

        // Decrement order for videos after the deleted one
        $subject->videos()
            ->where('order', '>', $videoOrder)
            ->decrement('order');

        return redirect()->route('professor.videos.index', $subject)
            ->with('status', 'Vídeo excluído com sucesso!');
    }

    public function reorder(Request $request, Subject $subject)
    {
        $this->authorize('update', $subject);

        $request->validate([
            'video_ids' => ['required', 'array'],
            'video_ids.*' => ['required', 'exists:videos,id', 'distinct'],
        ]);

        // Verify all videos belong to this subject
        $count = $subject->videos()
            ->whereIn('id', $request->input('video_ids'))
            ->count();

        if ($count !== count($request->input('video_ids'))) {
            abort(403);
        }

        // Update order for each video
        foreach ($request->input('video_ids') as $index => $videoId) {
            $subject->videos()
                ->where('id', $videoId)
                ->update(['order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    private function ensureVideoBelongsToSubject(Subject $subject, Video $video): void
    {
        abort_unless($video->subject_id === $subject->id, 404);
    }
}
