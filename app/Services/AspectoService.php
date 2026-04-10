<?php

namespace App\Services;

use App\Repositories\AspectoRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AspectoService
{
    public function __construct(private readonly AspectoRepository $repository) {}

    public function listar(): Collection
    {
        return $this->repository->all();
    }

    public function listarPorCaracteristica(int $caracteristicaId): Collection
    {
        return $this->repository->allByCaracteristica($caracteristicaId);
    }

    public function obtener(int $id): Model
    {
        return $this->repository->findById($id);
    }

    public function crear(array $datos): Model
    {
        $datos['created_by'] = Auth::id();
        $datos['updated_by'] = Auth::id();
        return $this->repository->create($datos);
    }

    public function actualizar(int $id, array $datos): Model
    {
        $datos['updated_by'] = Auth::id();
        return $this->repository->update($id, $datos);
    }

    public function eliminar(int $id): bool
    {
        return $this->repository->delete($id);
    }
}