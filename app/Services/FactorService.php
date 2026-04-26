<?php

namespace App\Services;

//use App\Repositories\FactorRepository;
//use App\Repositories\Contracts\CnaRepositoryInterface;
use App\Repositories\Contracts\FactorRepositoryInterface;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Servicio Factor.
 * Principio: Responsabilidad Única — solo contiene lógica de negocio de Factor.
 * Principio: Inversión de Dependencias — depende del repositorio vía inyección.
 * Principio: Abierto/Cerrado — se puede extender sin modificar el controlador.
 */
class FactorService
{
    public function __construct(private readonly FactorRepositoryInterface $repository) {}

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
        $datos['created_by'] = Auth::id();
        $datos['updated_by'] = Auth::id();
        $datos['status_id']  = 1; // Activo al crear
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