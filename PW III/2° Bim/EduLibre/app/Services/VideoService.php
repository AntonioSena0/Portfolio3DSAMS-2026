<?php

namespace App\Services;

use App\Repositories\Contracts\VideoRepositoryInterface;
use App\Models\Video;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class VideoService
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository,
    ) {}

    public function create(array $data, $subject, $professor): Video
    {
        // Set slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);

            // Ensure uniqueness within the subject
            $count = 0;
            $existing = $this->videoRepository->findBySlug($data['slug']);
            while ($existing && $existing->subject_id == $subject->id) {
                $count++;
                $data['slug'] = Str::slug($data['title']) . '-' . ($count + 1);
                $existing = $this->videoRepository->findBySlug($data['slug']);
            }
        }

        // Set professor_id
        $data['professor_id'] = $professor->id;

        // Create video
        $video = $this->videoRepository->create($data);

        // Associate with subject
        $subject->videos()->save($video);

        return $video;
    }

    public function update(Video $video, array $data): Video
    {
        // Handle order change
        if (isset($data['order']) && $data['order'] != $video->order) {
            $newOrder = $data['order'];
            $oldOrder = $video->order;
            $subjectId = $video->subject_id;

            if ($newOrder > $oldOrder) {
                // Moving down - shift videos between oldOrder+1 and newOrder up
                $video->subject->videos()
                    ->where('order', '>', $oldOrder)
                    ->where('order', '<=', $newOrder)
                    ->where('id', '!=', $video->id)
                    ->decrement('order');
            } else {
                // Moving up - shift videos between newOrder and oldOrder-1 down
                $video->subject->videos()
                    ->where('order', '>=', $newOrder)
                    ->where('order', '<', $oldOrder)
                    ->where('id', '!=', $video->id)
                    ->increment('order');
            }
        }

        // Update slug if title changed
        if (isset($data['title']) && $data['title'] !== $video->title) {
            $newSlug = Str::slug($data['title']);
            $count = 0;
            $existing = $this->videoRepository->findBySlug($newSlug);
            while ($existing && $existing->subject_id == $video->subject_id && $existing->id != $video->id) {
                $count++;
                $newSlug = Str::slug($data['title']) . '-' . ($count + 1);
                $existing = $this->videoRepository->findBySlug($newSlug);
            }
            $data['slug'] = $newSlug;
        }

        // Update video
        $this->videoRepository->update($video, $data);

        return $video;
    }

    public function delete(Video $video): bool
    {
        $videoOrder = $video->order;
        $subjectId = $video->subject_id;

        // Delete video
        $result = $this->videoRepository->delete($video);

        // Decrement order for videos after the deleted one
        if ($result) {
            $video->subject->videos()
                ->where('order', '>', $videoOrder)
                ->decrement('order');
        }

        return $result;
    }

    public function incrementViewCount(Video $video): Video
    {
        return $this->videoRepository->incrementViewCount($video);
    }
}