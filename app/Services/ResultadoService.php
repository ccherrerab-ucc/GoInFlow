<?php

namespace App\Services;

use App\Repositories\Contracts\ResultadoRepositoryInterface;
use App\Services\Contracts\StatusResolverInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ResultadoService
{
    public function __construct(
        private readonly ResultadoRepositoryInterface $repository,
        private readonly StatusResolverInterface $statusResolver,
    ) {}

    public function listar(): Collection
    {
        return $this->repository->all();
    }

    public function obtener(int $id): Model
    {
        return $this->repository->findById($id);
    }

    public function crear(array $datos): Model
    {
        $evidenciaIds = $datos['evidencias'] ?? [];
        unset($datos['evidencias'], $datos['evidencias_enviadas']);

        $datos['created_by']    = Auth::id();
        $datos['updated_by']    = Auth::id();
        $datos['status_id']     = 1;
        $datos['tipo_relacion'] = 'aspecto';
        $datos['id_referencia'] = 0;

        $resultado = $this->repository->create($datos);

        if (!empty($evidenciaIds)) {
            $pivot = collect($evidenciaIds)
                ->mapWithKeys(fn($id) => [$id => ['anexado_por' => Auth::id()]]);
            $resultado->evidencias()->sync($pivot);
        }

        return $resultado;
    }

    public function actualizar(int $id, array $datos): Model
    {
        $doSync       = isset($datos['evidencias_enviadas']);
        $evidenciaIds = $datos['evidencias'] ?? [];
        unset($datos['evidencias'], $datos['evidencias_enviadas']);

        $datos['updated_by'] = Auth::id();
        $resultado = $this->repository->update($id, $datos);

        if ($doSync) {
            $pivot = collect($evidenciaIds)
                ->mapWithKeys(fn($id) => [$id => ['anexado_por' => Auth::id()]]);
            $resultado->evidencias()->sync($pivot);
        }

        return $resultado;
    }

    public function eliminar(int $id): void
    {
        $this->repository->update($id, [
            'status_id'  => $this->statusResolver->suprimido(),
            'updated_by' => Auth::id(),
        ]);
    }

    public function listarPorRelacion(string $tipo, int $idReferencia): Collection
    {
        return $this->repository->allByTipoRelacion($tipo, $idReferencia);
    }
}
