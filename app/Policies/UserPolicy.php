<?php

namespace App\Policies;

use App\Models\User;

/**
 * Policy para gestión de Usuarios.
 * Solo el Administrador puede crear, editar y desactivar usuarios.
 */
class UserPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) return true;
        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, User $model): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, User $model): bool
    {
        return false;
    }

    public function delete(User $user, User $model): bool
    {
        return false;
    }
}
