<?php

namespace App\Policies;

use App\Models\Aspecto;
use App\Models\User;

/**
 * Roles:
 *   Admin      → todo
 *   Director   → ver + crear + editar
 *   Líder      → ver + crear + editar (aspectos de sus características)
 *   Enlace     → solo ver
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
        return true;
    }

    public function view(User $user, Aspecto $aspecto): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isDirector() || $user->isLiderCaracteristica();
    }

    public function update(User $user, Aspecto $aspecto): bool
    {
        return $user->isDirector() || $user->isLiderCaracteristica();
    }

    public function delete(User $user, Aspecto $aspecto): bool
    {
        return false; // solo Admin
    }
}