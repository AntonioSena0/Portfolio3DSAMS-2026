<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\EnrollmentRepositoryInterface;
use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;

class EnrollmentRepository implements EnrollmentRepositoryInterface
{
    public function findById(int $id): ?Enrollment
    {
        return Enrollment::withTrashed()->find($id);
    }

    public function findByStudentAndSubject(int $studentId, int $subjectId): ?Enrollment
    {
        return Enrollment::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->first();
    }

    public function getByStudent($student): Collection
    {
        return $student->enrollments()
            ->with(['subject:id,title,slug,cover', 'subject.professor:id,name,avatar'])
            ->get();
    }

    public function getBySubject($subject): Collection
    {
        return $subject->enrollments()
            ->with(['student:id,name,avatar'])
            ->get();
    }

    public function create(array $data): Enrollment
    {
        return Enrollment::create($data);
    }

    public function update(Enrollment $enrollment, array $data): Enrollment
    {
        $enrollment->update($data);
        return $enrollment;
    }

    public function delete(Enrollment $enrollment): bool
    {
        return $enrollment->delete();
    }

    public function getInProgressByStudent($student): Collection
    {
        return $student->enrollments()
            ->where('status', 'in_progress')
            ->with(['subject:id,title,slug,cover', 'subject.professor:id,name,avatar'])
            ->get();
    }

    public function getCompletedByStudent($student): Collection
    {
        return $student->enrollments()
            ->where('status', 'completed')
            ->with(['subject:id,title,slug,cover', 'subject.professor:id,name,avatar'])
            ->get();
    }
}