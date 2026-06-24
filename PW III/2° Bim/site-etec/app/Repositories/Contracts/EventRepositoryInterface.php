<?php

namespace App\Repositories\Contracts;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

interface EventRepositoryInterface
{
    /**
     * Retorna todos os eventos ordenados do mais recente para o mais antigo.
     */
    public function all(): Collection;

    /**
     * Retorna os eventos mais recentes respeitando um limite.
     */
    public function latest(int $limit = 6): Collection;

    /**
     * Busca um evento pelo ID.
     */
    public function find(int $id): ?Event;

    /**
     * Busca um evento pelo slug.
     */
    public function findBySlug(string $slug): ?Event;

    /**
     * Cria um novo evento.
     */
    public function create(array $data): Event;

    /**
     * Atualiza um evento existente.
     */
    public function update(int $id, array $data): ?Event;

    /**
     * Remove um evento.
     */
    public function delete(int $id): bool;
}
