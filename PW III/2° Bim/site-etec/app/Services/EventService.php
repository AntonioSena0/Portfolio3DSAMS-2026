<?php

namespace App\Services;

use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EventService
{
    public function __construct(
        private readonly EventRepositoryInterface $events,
    ) {
    }

    /**
     * Retorna todos os eventos ordenados do mais recente para o mais antigo.
     */
    public function listEvents(): Collection
    {
        return $this->events->all();
    }

    /**
     * Retorna os eventos mais recentes com limite definido.
     */
    public function latestEvents(int $limit = 6): Collection
    {
        return $this->events->latest($limit);
    }
}
