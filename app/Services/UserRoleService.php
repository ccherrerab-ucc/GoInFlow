<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\UserRoleServiceInterface;
;


class UserRoleService implements UserRoleServiceInterface
{
    public function actualizarRolPorAsignacion(User $user): void
    {
        if (!$user) return;

        // No tocar Admin (1), Director (2) ni LiderCaracteristica (3).
        // Un Líder asignado manualmente por el Admin nunca debe ser
        // degradado automáticamente al quitarle una asignación.
        if (in_array($user->id_rol, [1, 2, 3])) {
            return;
        }

        // Solo promover Enlace (4) → Líder (3) si tiene asignaciones.
        // La vuelta a Enlace es siempre una decisión manual del Admin.
        if ($user->id_rol == 4 && $this->tieneAsignaciones($user)) {
            $user->update(['id_rol' => 3]);
        }
    }

    private function tieneAsignaciones(User $user): bool
    {
        return $user->caracteristicas()->exists()
            || $user->factores()->exists()
            || $user->aspectos()->exists();
    }
}