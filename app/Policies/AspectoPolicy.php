<?php

namespace App\Policies;

use App\Models\Aspecto;
use App\Models\User;

/**
 * ADMIN: full | DIR_PROGRAMA: full | DIRECTOR: read (factor asignado) |
 * LIDER: ver + crear + editar (de sus características) | ENLACE: solo ver (asignados)
 */
class AspectoPolicy
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
            || $user->isLiderCaracteristica()
            || $user->isEnlace();
    }

    public function view(User $user, Aspecto $aspecto): bool
    {
        if ($user->isDirPrograma()) return true;
        if ($user->isDirector()) {
            return $aspecto->caracteristica?->factor?->responsable == $user->id;
        }
        if ($user->isLiderCaracteristica()) {
            return $aspecto->caracteristica?->responsable == $user->id;
        }
        if ($user->isEnlace()) {
            return $aspecto->responsable == $user->id;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isDirPrograma() || $user->isLiderCaracteristica();
    }

    public function update(User $user, Aspecto $aspecto): bool
    {
        if ($user->isDirPrograma()) return true;
        if ($user->isLiderCaracteristica()) {
            return $aspecto->caracteristica?->responsable == $user->id;
        }
        return false;
    }

    public function delete(User $user, Aspecto $aspecto): bool
    {
        return $user->isDirPrograma();
    }
}
