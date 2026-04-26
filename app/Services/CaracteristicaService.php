<?php

namespace App\Services;

use App\Models\Flujo;
use App\Models\FlujoPaso;
use App\Repositories\Contracts\CaracteristicaRepositoryInterface;
use App\Services\Contracts\StatusResolverInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        $datos['status_id'] = $this->statusResolver->activo();

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
     * Crea o reemplaza el flujo de aprobación default de la característica.
     * Si ya existe un flujo activo para la característica, elimina sus pasos y los recrea.
     * Requiere al menos un paso con rol_requerido para ser persistido.
     */
    public function guardarFlujo(int $caracteristicaId, array $flujoData): void
    {
        $pasos = array_filter($flujoData['pasos'] ?? [], fn ($p) => !empty($p['rol_requerido']));

        if (empty($pasos)) {
            return;
        }

        DB::transaction(function () use ($caracteristicaId, $flujoData, $pasos) {
            $flujo = Flujo::where('id_caracteristica', $caracteristicaId)
                          ->whereNull('id_aspecto')
                          ->first();

            if ($flujo) {
                $flujo->update(['nombre' => $flujoData['nombre'] ?? $flujo->nombre, 'activo' => true]);
                $flujo->pasos()->delete();
            } else {
                $flujo = Flujo::create([
                    'nombre'            => $flujoData['nombre'] ?? 'Flujo de aprobación',
                    'id_caracteristica' => $caracteristicaId,
                    'id_aspecto'        => null,
                    'activo'            => true,
                ]);
            }

            foreach (array_values($pasos) as $i => $paso) {
                FlujoPaso::create([
                    'id_flujo'             => $flujo->id_flujo,
                    'orden'                => $i + 1,
                    'rol_requerido'        => $paso['rol_requerido'],
                    'cantidad_aprobadores' => 1,
                ]);
            }
        });
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
