<?php

namespace App\Repositories;

use App\Models\Caracteristica;
use App\Models\User;
use App\Repositories\Contracts\CaracteristicaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CaracteristicaRepository implements CaracteristicaRepositoryInterface
{
    public function __construct(private readonly Caracteristica $model) {}

    public function all(): Collection
    {
        $raw  = Auth::user();
        $user = $raw instanceof User ? $raw : null;
        $query = $this->model
            ->with(['factor', 'status', 'responsableUser', 'aspectos'])
            ->orderBy('id_caracteristica', 'desc');

        if ($user?->isDirector()) {
            // Director ve solo características de sus factores asignados
            $query->whereHas('factor', fn($q) => $q->where('responsable', $user->id));
        } elseif ($user?->isLiderCaracteristica()) {
            // Líder ve solo las características donde es responsable
            $query->where('responsable', $user->id);
        }
        // Admin y DirPrograma ven todas; Enlace no accede (policy devuelve false en viewAny)

        return $query->get();
    }

    public function findById(int $id): ?Model
    {
        return $this->model
            ->with(['factor', 'status', 'responsableUser', 'aspectos', 'flujoActivo.pasos'])
            ->findOrFail($id);
    }

    public function allByFactor(int $factorId): Collection
    {
        return $this->model
            ->with(['status', 'responsableUser'])
            ->where('factor_id', $factorId)
            ->orderBy('id_caracteristica', 'desc')
            ->get();
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): Model
    {
        $caracteristica = $this->model->findOrFail($id);
        $caracteristica->update($data);
        return $caracteristica->fresh();
    }

    public function delete(int $id): bool
    {
        $caracteristica = $this->model->findOrFail($id);
        return $caracteristica->delete();
    }

    public function findWithEvidencias(int $id): ?Model
    {
        return $this->model
            ->with([
                'factor',
                'status',
                'responsableUser',
                'aspectos.responsableUser',
                'aspectos.status',
                'flujoActivo.pasos.rolRequerido',
                'aspectos.flujoActivo.pasos.rolRequerido',
                'aspectos.evidencias.estadoActual',
                'aspectos.evidencias.status',
                'aspectos.evidencias.createdBy',
                'aspectos.evidencias.resultados',
                'aspectos.evidencias.flujoEjecuciones' => fn($q) => $q
                    ->orderByDesc('id_ejecucion')
                    ->with([
                        'pasoActual.rolRequerido',
                        'historial' => fn($h) => $h
                            ->orderBy('fecha')
                            ->with(['usuario', 'paso.rolRequerido']),
                    ]),
            ])
            ->findOrFail($id);
    }
}
