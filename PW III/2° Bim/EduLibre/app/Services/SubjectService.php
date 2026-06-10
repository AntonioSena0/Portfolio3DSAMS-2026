<?php

namespace App\Services;

use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Repositories\Contracts\VideoRepositoryInterface;
use App\Models\Subject;
use App\Events\Subject\SubjectSubmittedForReview;
use App\Events\Subject\SubjectPublished;
use App\Events\Subject\SubjectRejected;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class SubjectService
{
    public function __construct(
        private SubjectRepositoryInterface $subjectRepository,
        private VideoRepositoryInterface $videoRepository,
    ) {}

    public function create(array $data, $professor): Subject
    {
        // Handle cover upload if present
        if (isset($data['cover']) && $data['cover']) {
            // Validate file
            if (!in_array($data['cover']->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                throw new \InvalidFileTypeException('Invalid file type for cover image');
            }

            // Generate unique filename
            $filename = 'cover_' . Str::uuid() . '.' . $data['cover']->getClientOriginalExtension();
            $path = $data['cover']->storeAs('subjects/covers', $filename, 'public');

            // Replace cover data with path
            $data['cover'] = $path;
        }

        // Set slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);

            // Ensure uniqueness
            $count = $this->subjectRepository->findBySlug($data['slug']) ? 1 : 0;
            if ($count > 0) {
                $data['slug'] .= '-' . ($count + 1);
            }
        }

        // Create subject
        $subject = $this->subjectRepository->create($data);

        // Associate with professor
        $professor->subjects()->save($subject);

        return $subject;
    }

    public function update(Subject $subject, array $data): Subject
    {
        // Handle cover upload if present
        if (isset($data['cover']) && $data['cover']) {
            // Validate file
            if (!in_array($data['cover']->getMimeType(), ['image/jpeg', 'image/png', 'image/webp'])) {
                throw new \InvalidFileTypeException('Invalid file type for cover image');
            }

            // Delete old cover if exists
            if ($subject->cover) {
                Storage::disk('public')->delete($subject->cover);
            }

            // Generate unique filename
            $filename = 'cover_' . Str::uuid() . '.' . $data['cover']->getClientOriginalExtension();
            $path = $data['cover']->storeAs('subjects/covers', $filename, 'public');

            // Replace cover data with path
            $data['cover'] = $path;
        } elseif (isset($data['remove_cover']) && $data['remove_cover'] == 'true') {
            // Remove cover if requested
            if ($subject->cover) {
                Storage::disk('public')->delete($subject->cover);
            }
            $data['cover'] = null;
        }

        // Update slug if title changed
        if (isset($data['title']) && $data['title'] !== $subject->title) {
            $data['slug'] = Str::slug($data['title']);

            // Ensure uniqueness
            $existing = $this->subjectRepository->findBySlug($data['slug']);
            if ($existing && $existing->id !== $subject->id) {
                $count = 1;
                while ($this->subjectRepository->findBySlug($data['slug'] . '-' . $count)) {
                    $count++;
                }
                $data['slug'] .= '-' . $count;
            }
        }

        // Update subject
        $this->subjectRepository->update($subject, $data);

        return $subject;
    }

    public function submitForReview(Subject $subject): Subject
    {
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

        return $subject;
    }

    public function approve(Subject $subject): Subject
    {
        $subject->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        event(new SubjectPublished($subject));

        return $subject;
    }

    public function reject(Subject $subject, string $reason): Subject
    {
        $subject->update([
            'status' => 'draft',
            'rejection_reason' => $reason,
        ]);

        event(new SubjectRejected($subject));

        return $subject;
    }

    public function archive(Subject $subject): Subject
    {
        $subject->update(['status' => 'archived']);
        return $subject;
    }

    public function delete(Subject $subject): bool
    {
        // Delete cover image if exists
        if ($subject->cover) {
            Storage::disk('public')->delete($subject->cover);
        }

        return $this->subjectRepository->delete($subject);
    }
}
