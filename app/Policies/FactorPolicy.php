<?php

namespace App\Policies;

use App\Models\Factor;
use App\Models\User;

/**
 * Policy para Factor.
 * Centraliza todas las reglas de autorización de este recurso.
 * Si las reglas cambian, solo se modifica aquí.
 *
 * Roles del sistema:
 *   Administrador      → todo
 *   Director           → solo ver
 *   LiderCaracteristica → solo ver
 *   Enlace             → solo ver
 */
class FactorPolicy
{
    /**
     * Atajo global: el Admin siempre puede todo.
     * Si retorna true, los métodos de abajo no se evalúan.
     * Si retorna null, se evalúa el método específico.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) return true;
        return null;
    }

    /** Ver listado */
    public function viewAny(User $user): bool
    {
        return true; // todos los roles pueden ver
    }

    /** Ver un factor individual */
    public function view(User $user, Factor $factor): bool
    {
        return true;
    }

    /** Crear nuevo factor */
    public function create(User $user): bool
    {
        return false; // solo Admin (ya resuelto en before())
    }

    /** Editar factor */
    public function update(User $user, Factor $factor): bool
    {
        return false; // solo Admin
    }

    /** Eliminar / suprimir factor */
    public function delete(User $user, Factor $factor): bool
    {
        return false; // solo Admin
    }
}