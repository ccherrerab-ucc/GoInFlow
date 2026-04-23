<?php

namespace App\Repositories;

use App\Models\Resultado;
use App\Repositories\Contracts\ResultadoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Principio: Responsabilidad Única (S de SOLID).
 * Solo se encarga de la persistencia de Resultado.
 */
class ResultadoRepository implements ResultadoRepositoryInterface
{
    public function __construct(private readonly Resultado $model) {}

    public function all(): Collection
    {
        return $this->model
            ->with(['status', 'createdBy', 'updatedBy'])
            ->orderBy('id_resultado', 'desc')
            ->get();
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
