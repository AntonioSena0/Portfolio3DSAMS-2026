<?php

namespace App\Services;

use App\Repositories\Contracts\CourseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CourseService
{
    public function __construct(
        private readonly CourseRepositoryInterface $courses,
    ) {
    }

    public function listCourses(): Collection
    {
        return $this->courses->all();
    }

    public function latestCourses(int $limit = 6): Collection
    {
        return $this->courses->latest($limit);
    }
}
