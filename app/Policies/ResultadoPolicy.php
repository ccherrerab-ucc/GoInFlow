<?php

namespace App\Policies;

use App\Models\Resultado;
use App\Models\User;

/**
 * ADMIN: full | DIR_PROGRAMA: read | DIRECTOR: read (factor asignado) |
 * LIDER: registrar (crear + editar) | ENLACE: cargar
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
        return true; // visibilidad acotada en el repositorio por rol
    }

    public function view(User $user, Resultado $resultado): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isLiderCaracteristica() || $user->isEnlace();
    }

    public function update(User $user, Resultado $resultado): bool
    {
        return $user->isLiderCaracteristica() || $user->isEnlace();
    }

    public function delete(User $user, Resultado $resultado): bool
    {
        return false; // solo Admin (resuelto en before())
    }
}
