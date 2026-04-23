<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;

use App\Services\Contracts\UserRoleServiceInterface;

/**
 * Principio S — única responsabilidad: gestionar el cambio de responsable
 *               y sus efectos en roles.
 *
 * Principio O — si mañana hay historial de responsables o notificaciones,
 *               se extiende esta clase sin tocar CaracteristicaService.
 *
 * Principio D — depende de interfaces, no de clases concretas.
 *
 * Este servicio encapsula el patrón repetido:
 *   User::find(...) + roleService->actualizarRolPorAsignacion(...)
 * que antes estaba duplicado en CaracteristicaService.
 */
class ResponsableService
{
    public function __construct(
        private readonly UserRepositoryInterface  $userRepository,
        private readonly UserRoleServiceInterface $roleService
    ) {}

    /**
     * Maneja el cambio de responsable entre dos asignaciones.
     * Actualiza el rol del nuevo responsable y libera el del anterior.
     *
     * @param int|null $responsableAnteriorId  ID del responsable antes del cambio
     * @param int|null $responsableNuevoId     ID del nuevo responsable
     */
    public function manejarCambio(?int $responsableAnteriorId, ?int $responsableNuevoId): void
    {
        // Sin cambio — nada que hacer
        if ($responsableAnteriorId === $responsableNuevoId) {
            return;
        }

        // Activar rol del nuevo responsable
        if ($responsableNuevoId) {
            $nuevo = $this->userRepository->findById($responsableNuevoId);
            if ($nuevo) {
                $this->roleService->actualizarRolPorAsignacion($nuevo);
            }
        }

        // Liberar rol del responsable anterior
        if ($responsableAnteriorId) {
            $anterior = $this->userRepository->findById($responsableAnteriorId);
            if ($anterior) {
                $this->roleService->actualizarRolPorAsignacion($anterior);
            }
        }
    }

    /**
     * Asigna responsable por primera vez (sin anterior).
     */
    public function asignar(?int $responsableId): void
    {
        if (!$responsableId) return;

        $user = $this->userRepository->findById($responsableId);
        if ($user) {
            $this->roleService->actualizarRolPorAsignacion($user);
        }
    }

    /**
     * Libera el responsable (ej: cuando se suprime una característica).
     */
    public function liberar(?int $responsableId): void
    {
        if (!$responsableId) return;

        $user = $this->userRepository->findById($responsableId);
        if ($user) {
            $this->roleService->actualizarRolPorAsignacion($user);
        }
    }
}