<?php

namespace App\Repositories\Contracts;

use App\Models\Video;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface VideoRepositoryInterface
{
    public function findById(int $id): ?Video;
    public function findBySlug(string $slug): ?Video;
    public function getBySubject($subject): Collection;
    public function getActiveBySubject($subject): Collection;
    public function create(array $data): Video;
    public function update(Video $video, array $data): Video;
    public function delete(Video $video): bool;
    public function incrementViewCount(Video $video): Video;
}