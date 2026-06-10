<?php

namespace App\Repositories\Contracts;

use App\Models\Subject;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SubjectRepositoryInterface
{
    public function findById(int $id): ?Subject;
    public function findBySlug(string $slug): ?Subject;
    public function getPublished(int $perPage = 12): LengthAwarePaginator;
    public function getByProfessor($professor): Collection;
    public function getUnderReview(): Collection;
    public function create(array $data): Subject;
    public function update(Subject $subject, array $data): Subject;
    public function delete(Subject $subject): bool;
    public function searchPublished(string $query, ?int $categoryId = null): LengthAwarePaginator;
}