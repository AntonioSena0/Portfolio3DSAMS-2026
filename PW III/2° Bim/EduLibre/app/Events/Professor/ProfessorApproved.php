<?php

namespace App\Events\Professor;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ProfessorApproved
{
    use Dispatchable, SerializesModels;

    public User $professor;

    /**
     * Create a new event instance.
     */
    public function __construct(User $professor)
    {
        $this->professor = $professor;
    }
}