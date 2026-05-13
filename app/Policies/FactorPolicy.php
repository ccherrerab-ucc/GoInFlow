<?php

namespace App\Policies;

use App\Models\Factor;
use App\Models\User;

/**
 * ADMIN: full | DIR_PROGRAMA: read | DIRECTOR: read (solo asignados) | LIDER/ENLACE: ninguno
 */
class FactorPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->isDirPrograma() || $user->isDirector();
    }

    public function view(User $user, Factor $factor): bool
    {
        if ($user->isDirPrograma()) return true;
        if ($user->isDirector()) return $factor->responsable == $user->id;
        return false;
    }

    public function create(User $user): bool { return $user->isDirPrograma(); }

    public function update(User $user, Factor $factor): bool { return $user->isDirPrograma(); }

    public function delete(User $user, Factor $factor): bool { return false; }
}
