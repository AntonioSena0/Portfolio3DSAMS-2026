<?php

namespace App\Repositories\Contracts;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

interface CourseRepositoryInterface
{
    public function all(): Collection;

    public function latest(int $limit = 6): Collection;

    public function find(int $id): ?Course;

    public function findBySlug(string $slug): ?Course;

    public function create(array $data): Course;

    public function update(int $id, array $data): ?Course;

    public function delete(int $id): bool;
}
