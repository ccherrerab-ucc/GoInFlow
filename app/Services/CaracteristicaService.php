<?php

namespace App\Services;

use App\Repositories\CaracteristicaRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CaracteristicaService
{
    public function __construct(private readonly CaracteristicaRepository $repository) {}

    public function listar(): Collection
    {
        return $this->repository->all();
    }

    public function listarPorFactor(int $factorId): Collection
    {
        return $this->repository->allByFactor($factorId);
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
        return $this->repository->delete($id);//cambio de estado a suprimido
    }
}