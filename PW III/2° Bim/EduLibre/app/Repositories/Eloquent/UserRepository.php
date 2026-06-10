<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        return User::withTrashed()->find($id);
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getProfessors(?string $status = null): Collection
    {
        $query = User::professors();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    public function getAdmins(): Collection
    {
        return User::admins()->get();
    }

    public function getStudents(): Collection
    {
        return User::students()->get();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }

    public function getPendingProfessors(): Collection
    {
        return User::professors()->pending()->get();
    }
}