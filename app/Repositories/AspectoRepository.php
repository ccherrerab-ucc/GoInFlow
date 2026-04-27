<?php

namespace App\Repositories;

use App\Models\Aspecto;
use App\Repositories\Contracts\AspectoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AspectoRepository implements AspectoRepositoryInterface
{
    public function __construct(private readonly Aspecto $model) {}

    public function all(): Collection
    {
        $user  = Auth::user();
        $query = $this->model
            ->with(['caracteristica.factor', 'status', 'responsableUser'])
            ->orderBy('id_aspecto', 'desc');

        if ($user?->isEnlace()) {
            // Enlace ve solo los aspectos donde es responsable.
            $query->where('responsable', $user->id);
        } elseif ($user?->isLiderCaracteristica()) {
            // Líder ve los aspectos de sus características asignadas.
            $query->whereHas('caracteristica', fn ($q) => $q->where('responsable', $user->id));
        }
        // Admin y Director ven todos.

        return $query->get();
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
            ->whereHas('caracteristica', fn ($q) => $q->where('factor_id', $id))
            ->orderBy('id_aspecto', 'desc')
            ->get();
    }
}
