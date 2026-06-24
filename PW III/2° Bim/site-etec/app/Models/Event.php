<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'event_date',
        'image_url',
        'category',
        'target_grade',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    /**
     * Eventos direcionados a uma série específica ou a todas (target_grade = null).
     */
    public function scopeForGrade($query, ?string $grade)
    {
        if ($grade) {
            return $query->where(function ($q) use ($grade) {
                $q->where('target_grade', $grade)
                  ->orWhereNull('target_grade');
            });
        }

        return $query->whereNull('target_grade');
    }

    public function readByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_user')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    public function isReadBy(?User $user): bool
    {
        if (! $user) {
            return false;
        }

        return $this->readByUsers()
            ->where('user_id', $user->id)
            ->whereNotNull('event_user.read_at')
            ->exists();
    }
}
