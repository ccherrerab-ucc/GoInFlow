<?php

namespace App\Repositories;

use App\Models\Resultado;
use App\Models\User;
use App\Repositories\Contracts\ResultadoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ResultadoRepository implements ResultadoRepositoryInterface
{
    public function __construct(private readonly Resultado $model) {}

    public function all(): Collection
    {
        $raw  = Auth::user();
        $user = $raw instanceof User ? $raw : null;

        $query = $this->model
            ->with(['status', 'createdBy'])
            ->withCount('evidencias')
            ->orderBy('id_resultado', 'desc');

        if ($user?->isDirector()) {
            $query->whereHas(
                'evidencias.aspecto.caracteristica.factor',
                fn($q) => $q->where('responsable', $user->id)
            );
        } elseif ($user?->isLiderCaracteristica()) {
            // Líder ve resultados cuyas evidencias pertenecen a sus características
            $query->whereHas(
                'evidencias.aspecto.caracteristica',
                fn($q) => $q->where('responsable', $user->id)
            );
        } elseif ($user?->isEnlace()) {
            // Enlace ve resultados que creó o cuyas evidencias son de sus aspectos
            $query->where(fn($q) => $q
                ->where('created_by', $user->id)
                ->orWhereHas(
                    'evidencias.aspecto',
                    fn($q2) => $q2->where('responsable', $user->id)
                )
            );
        }

        return $query->get();
    }

    public function findById(int $id): ?Model
    {
        return $this->model
            ->with(['status', 'createdBy', 'updatedBy', 'evidencias'])
            ->findOrFail($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $resultado = $this->model->findOrFail($id);
        $resultado->update($data);
        return $resultado->fresh();
    }

    public function delete(int $id): bool
    {
        $resultado = $this->model->findOrFail($id);
        return $resultado->delete();
    }

    public function allByTipoRelacion(string $tipo, int $idReferencia): Collection
    {
        return $this->model
            ->with(['status'])
            ->where('tipo_relacion', $tipo)
            ->where('id_referencia', $idReferencia)
            ->orderBy('id_resultado', 'desc')
            ->get();
    }
}
