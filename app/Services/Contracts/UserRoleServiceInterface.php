<?php

namespace App\Services\Contracts;

use App\Models\User;

/**
 * Contrato para el servicio de roles.
 * Principio D — CaracteristicaService depende de esta abstracción,
 * no de la implementación concreta UserRoleService.
 */
interface UserRoleServiceInterface
{
    public function actualizarRolPorAsignacion(User $user): void;
}