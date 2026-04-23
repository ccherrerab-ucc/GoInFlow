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

        // 🚫 No tocar roles altos
        if (in_array($user->id_rol, [1, 2])) {
            return;
        }

        $tieneAsignaciones = $this->tieneAsignaciones($user);

        // 🔼 PROMOVER
        if ($tieneAsignaciones && $user->id_rol == 4) {
            $user->update(['id_rol' => 3]); // Enlace → Líder
        }

        // 🔽 OPCIONAL (solo si quieres permitir regreso a Enlace)
        if (!$tieneAsignaciones && $user->id_rol == 3) {
            $user->update(['id_rol' => 4]); // Líder → Enlace
        }
    }

    private function tieneAsignaciones(User $user): bool
    {
        return $user->caracteristicas()->exists()
            || $user->factores()->exists()
            || $user->aspectos()->exists();
    }
}