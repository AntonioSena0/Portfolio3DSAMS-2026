<?php

namespace App\Repositories\Contracts;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;

interface EnrollmentRepositoryInterface
{
    public function findById(int $id): ?Enrollment;
    public function findByStudentAndSubject(int $studentId, int $subjectId): ?Enrollment;
    public function getByStudent($student): Collection;
    public function getBySubject($subject): Collection;
    public function create(array $data): Enrollment;
    public function update(Enrollment $enrollment, array $data): Enrollment;
    public function delete(Enrollment $enrollment): bool;
    public function getInProgressByStudent($student): Collection;
    public function getCompletedByStudent($student): Collection;
}