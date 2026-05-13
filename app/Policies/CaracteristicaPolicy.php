<?php

namespace App\Policies;

use App\Models\Caracteristica;
use App\Models\User;

/**
 * ADMIN: full | DIR_PROGRAMA: full | DIRECTOR: read (factor asignado) |
 * LIDER: ver + editar (propias) | ENLACE: ninguno
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
        return $user->isDirPrograma()
            || $user->isDirector()
            || $user->isLiderCaracteristica();
    }

    public function view(User $user, Caracteristica $caracteristica): bool
    {
        if ($user->isDirPrograma()) return true;
        if ($user->isDirector()) {
            return $caracteristica->factor?->responsable == $user->id;
        }
        if ($user->isLiderCaracteristica()) {
            return $caracteristica->responsable == $user->id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isDirPrograma();
    }

    public function update(User $user, Caracteristica $caracteristica): bool
    {
        if ($user->isDirPrograma()) return true;
        if ($user->isDirector()) {
            return $caracteristica->factor?->responsable == $user->id;
        }
        if ($user->isLiderCaracteristica()) {
            return $caracteristica->responsable == $user->id;
        }
        return false;
    }

    public function delete(User $user, Caracteristica $caracteristica): bool
    {
        return $user->isDirPrograma();
    }
}
