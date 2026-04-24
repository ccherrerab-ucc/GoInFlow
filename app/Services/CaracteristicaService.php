<?php

namespace App\Services;

use App\Repositories\Contracts\CaracteristicaRepositoryInterface;
use App\Services\Contracts\StatusResolverInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * ─────────────────────────────────────────────────────────────
 * ANTES (problemas):
 *   - Mezclaba CRUD + auditoría + roles + estados
 *   - Dependía de User::find() y StatusCna::where() directamente
 *   - Duplicaba el patrón User::find() + roleService->actualizar()
 *   - Difícil de testear (acoplado a Eloquent)
 *
 * AHORA (SOLID aplicado):
 *   S — Solo orquesta el flujo de características.
 *       Auditoría → AuditoriaService
 *       Roles     → ResponsableService
 *       Estados   → StatusResolverInterface
 *
 *   O — Para agregar historial, notificaciones o reglas nuevas
 *       se extiende ResponsableService, no este archivo.
 *
 *   L — Puede reemplazarse por una subclase sin romper el contrato.
 *
 *   I — Consume CnaRepositoryInterface (solo lo que necesita).
 *
 *   D — Depende solo de interfaces e inyecciones, nunca de
 *       clases concretas de Eloquent.
 * ─────────────────────────────────────────────────────────────
 */
class CaracteristicaService
{
    public function __construct(
        private readonly CaracteristicaRepositoryInterface  $repository,
        private readonly AuditoriaService        $auditoria,
        private readonly ResponsableService      $responsable,
        private readonly StatusResolverInterface $statusResolver,
    ) {}

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

    /** Carga la característica con aspectos, evidencias y ejecuciones activas para la vista de evaluación. */
    public function obtenerParaEvaluacion(int $id): Model
    {
        return $this->repository->findWithEvidencias($id);
    }

    public function crear(array $datos): Model
    {
        // S: auditoría delegada a AuditoriaService
        $datos = $this->auditoria->alCrear($datos);

        $caracteristica = $this->repository->create($datos);

        // S: lógica de responsable delegada a ResponsableService
        $this->responsable->asignar($datos['responsable'] ?? null);

        return $caracteristica;
    }

    public function actualizar(int $id, array $datos): Model
    {
        // S: auditoría delegada
        $datos = $this->auditoria->alActualizar($datos);

        // Captura el responsable anterior ANTES de actualizar
        $caracteristica       = $this->repository->findById($id);
        $responsableAnterior  = $caracteristica->responsable;

        $actualizada = $this->repository->update($id, $datos);

        // S: lógica de cambio de responsable delegada
        $this->responsable->manejarCambio(
            $responsableAnterior,
            $datos['responsable'] ?? null
        );

        return $actualizada;
    }

    /**
     * Soft-delete: cambia estado a "Suprimido" en lugar de eliminar.
     * D: usa StatusResolverInterface, no StatusCna directamente.
     */
    public function eliminar(int $id): Model
    {
        $caracteristica = $this->repository->findById($id);
        $responsableId  = $caracteristica->responsable;

        $datos = $this->auditoria->alActualizar([
            'status_id' => $this->statusResolver->suprimido(),
        ]);

        $actualizada = $this->repository->update($id, $datos);

        // S: liberar rol del responsable delegado
        $this->responsable->liberar($responsableId);

        return $actualizada;
    }
}
