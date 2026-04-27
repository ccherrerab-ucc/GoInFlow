<?php

namespace App\Policies;

use App\Models\Caracteristica;
use App\Models\User;

/**
 * Roles:
 *   Admin      → todo
 *   Director   → ver + crear + editar (no eliminar)
 *   Líder      → solo ver
 *   Enlace     → solo ver
 */
class CaracteristicaPolicy
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

    public function view(User $user, Caracteristica $caracteristica): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isDirector();
    }

    public function update(User $user, Caracteristica $caracteristica): bool
    {
        return $user->isDirector();
    }

    public function delete(User $user, Caracteristica $caracteristica): bool
    {
        return false; // solo Admin
    }
}