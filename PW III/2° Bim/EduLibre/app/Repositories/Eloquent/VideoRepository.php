<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\VideoRepositoryInterface;
use App\Models\Video;
use Illuminate\Database\Eloquent\Collection;

class VideoRepository implements VideoRepositoryInterface
{
    public function findById(int $id): ?Video
    {
        return Video::withTrashed()->find($id);
    }

    public function findBySlug(string $slug): ?Video
    {
        return Video::where('slug', $slug)->first();
    }

    public function getBySubject($subject): Collection
    {
        return $subject->videos()
            ->orderBy('order')
            ->get();
    }

    public function getActiveBySubject($subject): Collection
    {
        return $subject->videos()
            ->where('status', 'active')
            ->orderBy('order')
            ->get();
    }

    public function create(array $data): Video
    {
        return Video::create($data);
    }

    public function update(Video $video, array $data): Video
    {
        $video->update($data);
        return $video;
    }

    public function delete(Video $video): bool
    {
        return $video->delete();
    }

    public function incrementViewCount(Video $video): Video
    {
        $video->increment('views_count');
        return $video;
    }
}