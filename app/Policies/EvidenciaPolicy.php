<?php

namespace App\Policies;

use App\Models\Evidencia;
use App\Models\User;

/**
 * Roles:
 *   Admin      → todo
 *   Director   → ver + aprobar + rechazar (no cargar)
 *   Líder      → ver + aprobar + rechazar (de sus características)
 *   Enlace     → ver + crear + editar (solo las propias)
 */
class EvidenciaPolicy
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

    public function view(User $user, Evidencia $evidencia): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Director y Líder también pueden crear en este proyecto
        return $user->isDirector()
            || $user->isLiderCaracteristica()
            || $user->isEnlace();
    }

    public function update(User $user, Evidencia $evidencia): bool
    {
        // Enlace solo puede editar las evidencias que él mismo creó.
        // Usar == (no ===) porque SQL Server puede devolver created_by como string.
        if ($user->isEnlace()) {
            return $evidencia->created_by == $user->id;
        }

        return $user->isDirector() || $user->isLiderCaracteristica();
    }

    /**
     * Enviar al flujo de aprobación o reenviar tras rechazo.
     * Mismas reglas que update: el creador o roles superiores.
     */
    public function iniciar(User $user, Evidencia $evidencia): bool
    {
        if ($user->isEnlace()) {
            return $evidencia->created_by == $user->id;
        }

        return $user->isDirector() || $user->isLiderCaracteristica();
    }

    public function delete(User $user, Evidencia $evidencia): bool
    {
        return false; // solo Admin
    }

    /** Acción personalizada: aprobar o rechazar en el flujo */
    public function aprobar(User $user, Evidencia $evidencia): bool
    {
        return $user->isDirector() || $user->isLiderCaracteristica();
    }

    /** Acción personalizada: descargar archivo */
    public function descargar(User $user, Evidencia $evidencia): bool
    {
        return true; // cualquier rol autenticado puede descargar
    }
}