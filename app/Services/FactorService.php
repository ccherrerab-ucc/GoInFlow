<?php

namespace App\Services;

use App\Models\Rol;
use App\Models\User;
use App\Repositories\Contracts\FactorRepositoryInterface;
use App\Services\Contracts\StatusResolverInterface;
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
    public function __construct(
        private readonly FactorRepositoryInterface $repository,
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
        $datos['created_by'] = Auth::id();
        $datos['updated_by'] = Auth::id();
        $datos['status_id']  = 1;
        $factor = $this->repository->create($datos);
        if (!empty($datos['responsable'])) {
            $this->asignarRolDirector((int) $datos['responsable']);
        }
        return $factor;
    }

    public function actualizar(int $id, array $datos): Model
    {
        $datos['updated_by'] = Auth::id();
        $factor = $this->repository->update($id, $datos);
        if (!empty($datos['responsable'])) {
            $this->asignarRolDirector((int) $datos['responsable']);
        }
        return $factor;
    }

    private function asignarRolDirector(int $userId): void
    {
        $user = User::find($userId);
        if (!$user || $user->isAdmin() || $user->isDirPrograma()) return;

        $directorId = Rol::where('name', 'Director')->value('id_rol');
        if ($directorId) {
            $user->update(['id_rol' => $directorId]);
        }
    }

    public function eliminar(int $id): void
    {
        $this->repository->update($id, [
            'status_id'  => $this->statusResolver->suprimido(),
            'updated_by' => Auth::id(),
        ]);
    }
}