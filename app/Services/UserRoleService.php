<?php

namespace App\Services;

use App\Models\Rol;
use App\Models\User;
use App\Services\Contracts\UserRoleServiceInterface;

class UserRoleService implements UserRoleServiceInterface
{
    public function actualizarRolPorAsignacion(User $user): void
    {
        if (!$user) return;

        // Roles inmutables por asignación automática
        if ($user->isAdmin() || $user->isDirPrograma() || $user->isDirector()) {
            return;
        }

        $tieneCaracteristicas = $user->caracteristicas()->exists();

        // Demote LiderCaracteristica → Enlace si ya no tiene características
        if ($user->isLiderCaracteristica() && !$tieneCaracteristicas) {
            $enlaceId = Rol::where('name', 'Enlace')->value('id_rol');
            if ($enlaceId) {
                $user->update(['id_rol' => $enlaceId]);
            }
            return;
        }

        // Promote Enlace → LiderCaracteristica si tiene al menos una característica
        if ($user->isEnlace() && $tieneCaracteristicas) {
            $liderId = Rol::where('name', 'LiderCaracteristica')->value('id_rol');
            if ($liderId) {
                $user->update(['id_rol' => $liderId]);
            }
        }
    }
}
