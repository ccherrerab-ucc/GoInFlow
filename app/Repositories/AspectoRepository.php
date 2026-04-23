<?php

namespace App\Repositories;

use App\Models\Aspecto;
use App\Repositories\Contracts\AspectoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Repositorio Aspecto.
 * Principio: Responsabilidad Única (S de SOLID).
 */
class AspectoRepository implements AspectoRepositoryInterface
{
    public function __construct(private readonly Aspecto $model) {}

    public function all(): Collection
    {
        return $this->model
            ->with(['caracteristica.factor', 'status', 'responsableUser'])
            ->orderBy('id_aspecto', 'desc')
            ->get();
    }

    public function findById(int $id): ?Model
    {
        return $this->model
            ->with(['caracteristica.factor', 'status', 'responsableUser'])
            ->findOrFail($id);
    }

    public function allByCaracteristica(int $caracteristicaId): Collection
    {
        return $this->model
            ->with(['status', 'responsableUser'])
            ->where('caracteristica_id', $caracteristicaId)
            ->orderBy('id_aspecto', 'desc')
            ->get();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $aspecto = $this->model->findOrFail($id);
        $aspecto->update($data);
        return $aspecto->fresh();
    }

    public function delete(int $id): bool
    {
        $aspecto = $this->model->findOrFail($id);
        return $aspecto->delete();
    }

    public function allByFactor(int $id): Collection
    {
        return $this->model
            ->with(['caracteristica.factor', 'status', 'responsableUser'])
            ->whereHas('caracteristica', function ($query) use ($id) {
                $query->where('factor_id', $id);
            })
            ->orderBy('id_aspecto', 'desc')
            ->get();
    }
}