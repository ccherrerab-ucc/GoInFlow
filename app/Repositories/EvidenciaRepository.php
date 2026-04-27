<?php

namespace App\Repositories;

use App\Models\Evidencia;
use App\Repositories\Contracts\EvidenciaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EvidenciaRepository implements EvidenciaRepositoryInterface
{
    public function __construct(private readonly Evidencia $model) {}

    public function all(): Collection
    {
        $user  = Auth::user();
        $query = $this->model
            ->with(['status', 'aspecto.caracteristica', 'estadoActual'])
            ->orderBy('id_evidencia', 'desc');

        if ($user?->isEnlace()) {
            // Enlace ve evidencias que creó O que pertenecen a sus aspectos asignados.
            $query->where(fn ($q) => $q
                ->where('created_by', $user->id)
                ->orWhereHas('aspecto', fn ($q2) => $q2->where('responsable', $user->id))
            );
        } elseif ($user?->isLiderCaracteristica()) {
            // Líder ve evidencias de los aspectos de sus características.
            $query->whereHas(
                'aspecto.caracteristica',
                fn ($q) => $q->where('responsable', $user->id)
            );
        }
        // Admin y Director ven todas.

        return $query->get();
    }

    public function findById(int $id): ?Model
    {
        return $this->model
            ->with(['status', 'aspecto.caracteristica', 'estadoActual', 'createdBy', 'updatedBy'])
            ->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $evidencia = $this->model->findOrFail($id);
        $evidencia->update($data);
        return $evidencia->fresh();
    }

    public function delete(int $id): bool
    {
        $evidencia = $this->model->findOrFail($id);
        return $evidencia->delete();
    }

    public function allByAspecto(int $id): Collection
    {
        return $this->model
            ->with(['status', 'estadoActual'])
            ->where('id_aspecto', $id)
            ->orderBy('id_evidencia', 'desc')
            ->get();
    }
}
