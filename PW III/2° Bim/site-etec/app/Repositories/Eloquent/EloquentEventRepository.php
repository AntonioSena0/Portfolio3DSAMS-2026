<?php

namespace App\Repositories\Eloquent;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class EloquentEventRepository implements EventRepositoryInterface
{
    /**
     * Retorna todos os eventos ordenados do mais recente para o mais antigo.
     */
    public function all(): Collection
    {
        return Event::query()
            ->orderByDesc('event_date')
            ->get();
    }

    /**
     * Retorna os eventos mais recentes respeitando um limite.
     */
    public function latest(int $limit = 6): Collection
    {
        return Event::query()
            ->orderByDesc('event_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Busca um evento pelo ID.
     */
    public function find(int $id): ?Event
    {
        return Event::query()->find($id);
    }

    /**
     * Busca um evento pelo slug.
     */
    public function findBySlug(string $slug): ?Event
    {
        return Event::query()
            ->where('slug', $slug)
            ->first();
    }

    /**
     * Cria um novo evento.
     */
    public function create(array $data): Event
    {
        return Event::query()->create($data);
    }

    /**
     * Atualiza um evento existente.
     */
    public function update(int $id, array $data): ?Event
    {
        $event = $this->find($id);

        if (! $event) {
            return null;
        }

        $event->update($data);

        return $event;
    }

    /**
     * Remove um evento.
     */
    public function delete(int $id): bool
    {
        $event = $this->find($id);

        if (! $event) {
            return false;
        }

        return (bool) $event->delete();
    }
}
