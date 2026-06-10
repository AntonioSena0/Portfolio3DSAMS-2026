<?php

namespace App\Services;

use App\Models\VideoProgress;
use App\Models\Video;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class VideoProgressService
{
    public function saveProgress(User $student, Video $video, int $watchedSeconds): VideoProgress
    {
        // Validate watched seconds
        $watchedSeconds = max(0, min($watchedSeconds, $video->duration ?? 0));

        // Upsert video progress
        $progress = VideoProgress::updateOrCreate(
            [
                'student_id' => $student->id,
                'video_id' => $video->id,
            ],
            [
                'watched_seconds' => $watchedSeconds,
                'is_completed' => $watchedSeconds >= ($video->duration * 0.9),
                'completed_at' => $watchedSeconds >= ($video->duration * 0.9) ? now() : null,
            ]
        );

        // Update enrollment progress
        $this->updateEnrollmentProgress($student, $video->subject);

        return $progress;
    }

    public function updateEnrollmentProgress(User $student, $subject): void
    {
        // Use a transaction to ensure consistency
        DB::transaction(function () use ($student, $subject) {
            // Lock the enrollment record to prevent race conditions
            $enrollment = $student->enrollments()
                ->where('subject_id', $subject->id)
                ->lockForUpdate()
                ->first();

            // If enrollment doesn't exist, create it
            if (!$enrollment) {
                $enrollment = $student->enrollments()->create([
                    'subject_id' => $subject->id,
                    'status' => 'in_progress',
                    'progress_percent' => 0,
                ]);
            }

            // Count total active videos in the subject
            $totalVideos = $subject->videos()
                ->where('status', 'active')
                ->count();

            // If no videos, set progress to 0
            if ($totalVideos === 0) {
                $enrollment->update([
                    'progress_percent' => 0,
                    'status' => 'in_progress',
                ]);

                return;
            }

            # Count completed videos for this student in this subject
            $completedVideos = $subject->videos()
                ->where('status', 'active')
                ->whereHas('progress', function($query) use ($student) {
                    $query->where('student_id', $student->id)
                          ->where('is_completed', true);
                })
                ->count();

            # Calculate progress percentage
            $progressPercent = intval(($completedVideos / $totalVideos) * 100);

            # Determine enrollment status
            $status = 'in_progress';
            if ($progressPercent >= 100) {
                $status = 'completed';
            } elseif ($progressPercent == 0) {
                # Check if student has started any video
                $hasStarted = $subject->videos()
                    ->where('status', 'active')
                    ->whereHas('progress', function($query) use ($student) {
                        $query->where('student_id', $student->id)
                              ->where('watched_seconds', '>', 0);
                    })
                    ->exists();

                if (!$hasStarted) {
                    $status = 'in_progress'; # Still in progress, just not started
                } else {
                    $status = 'in_progress'; # Has started but 0% complete
                }
            }

            # Update enrollment
            $enrollment->update([
                'progress_percent' => $progressPercent,
                'status' => $status,
                'completed_at' => $status == 'completed' ? now() : null,
            ]);
        });
    }

    public function getLastWatched(User $student): Collection
    {
        return VideoProgress::where('student_id', $student->id)
            ->where('watched_seconds', '>', 0)
            ->with(['video:id,title,slug,duration', 'video.subject:id,title,slug'])
            ->orderByDesc('updated_at')
            ->take(6)
            ->get();
    }
}