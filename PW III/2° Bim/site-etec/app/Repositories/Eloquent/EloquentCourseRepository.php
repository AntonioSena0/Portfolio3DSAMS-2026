<?php

namespace App\Repositories\Eloquent;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentCourseRepository implements CourseRepositoryInterface
{
    public function all(): Collection
    {
        return Course::query()
            ->orderBy('name')
            ->get();
    }

    public function latest(int $limit = 6): Collection
    {
        return Course::query()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function find(int $id): ?Course
    {
        return Course::query()->find($id);
    }

    public function findBySlug(string $slug): ?Course
    {
        return Course::query()
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): Course
    {
        return Course::query()->create($data);
    }

    public function update(int $id, array $data): ?Course
    {
        $course = $this->find($id);

        if (! $course) {
            return null;
        }

        $course->update($data);

        return $course;
    }

    public function delete(int $id): bool
    {
        $course = $this->find($id);

        if (! $course) {
            return false;
        }

        return (bool) $course->delete();
    }
}
