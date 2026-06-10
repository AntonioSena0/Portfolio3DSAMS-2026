<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\SubjectRepositoryInterface;
use App\Models\Subject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class SubjectRepository implements SubjectRepositoryInterface
{
    public function findById(int $id): ?Subject
    {
        return Subject::withTrashed()->find($id);
    }

    public function findBySlug(string $slug): ?Subject
    {
        return Subject::where('slug', $slug)->first();
    }

    public function getPublished(int $perPage = 12): LengthAwarePaginator
    {
        return Subject::published()
            ->with(['professor:id,name,avatar', 'category:id,name,color', '_count_videos'])
            ->withCount(['videos' => fn($q) => $q->active()])
            ->latest('published_at')
            ->paginate($perPage);
    }

    public function getByProfessor($professor): Collection
    {
        return $professor->subjects()
            ->withCount([
                'videos as videos_count' => fn($q) => $q->where('status', 'active'),
                'enrollments as enrollments_count'
            ])
            ->get();
    }

    public function getUnderReview(): Collection
    {
        return Subject::underReview()
            ->with(['professor:id,name,avatar', 'category:id,name,color'])
            ->get();
    }

    public function create(array $data): Subject
    {
        return Subject::create($data);
    }

    public function update(Subject $subject, array $data): Subject
    {
        $subject->update($data);
        return $subject;
    }

    public function delete(Subject $subject): bool
    {
        return $subject->delete();
    }

    public function searchPublished(string $query, ?int $categoryId = null): LengthAwarePaginator
    {
        return Subject::published()
            ->when($query, fn($q) => $q->where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%"))
            ->when($categoryId, fn($q) => $q->where('category_id', $categoryId))
            ->with(['professor:id,name', 'category:id,name,color'])
            ->withCount(['videos' => fn($q) => $q->active()])
            ->paginate(12);
    }
}