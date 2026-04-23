<?php
 
namespace App\Repositories;

use App\Models\Evidencia;
use App\Repositories\Contracts\EvidenciaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Repositorio Evidencia.
 * Principio: Responsabilidad Única (S de SOLID).
 * Solo se encarga de la persistencia de Evidencia.
 */
class EvidenciaRepository implements EvidenciaRepositoryInterface
{
    public function __construct(private readonly Evidencia $model) {}
 
    public function all(): Collection
    {
        return $this->model
            ->with(['status', 'responsable', 'aspectos'])
            ->orderBy('id_evidencia', 'desc')
            ->get();
    }
 
    public function findById(int $id): ?Model
    {
        return $this->model
            ->with(['status', 'responsable', 'aspectos'])
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
            ->with(['status', 'responsable'])
            ->where('id_aspecto', $id)
            ->orderBy('id_aspecto', 'desc')
            ->get();
    }
}