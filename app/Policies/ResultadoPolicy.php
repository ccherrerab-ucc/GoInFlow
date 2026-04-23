<?php

namespace App\Policies;

use App\Models\Resultado;
use App\Models\User;

/**
 * Policy para Resultado.
 * Roles: Administrador → todo | Director → ver/crear/editar | Líder/Enlace → solo ver
 */
class ResultadoPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Resultado $resultado): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isDirector();
    }

    public function update(User $user, Resultado $resultado): bool
    {
        return $user->isDirector();
    }

    public function delete(User $user, Resultado $resultado): bool
    {
        return false; // solo Admin (resuelto en before())
    }
}
