<?php

namespace App\Policies;

use App\Models\Evidencia;
use App\Models\User;

/**
 * ADMIN: full | DIR_PROGRAMA: read | DIRECTOR: read (factor asignado) |
 * LIDER: aprobar + registrar (de sus características) | ENLACE: cargar (aspecto asignado)
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
        return true; // visibilidad acotada en el repositorio por rol
    }

    public function view(User $user, Evidencia $evidencia): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isLiderCaracteristica() || $user->isEnlace();
    }

    public function update(User $user, Evidencia $evidencia): bool
    {
        if ($user->isLiderCaracteristica()) return true; // cualquiera de sus características
        if ($user->isEnlace()) return $evidencia->created_by == $user->id;
        return false;
    }

    public function delete(User $user, Evidencia $evidencia): bool
    {
        return false; // solo Admin (resuelto en before())
    }

    /** Enviar al flujo de aprobación (iniciar / reiniciar) */
    public function iniciar(User $user, Evidencia $evidencia): bool
    {
        if ($user->isLiderCaracteristica()) return true;
        if ($user->isEnlace()) return $evidencia->created_by == $user->id;
        return false;
    }

    /** Aprobar o rechazar en el flujo */
    public function aprobar(User $user, Evidencia $evidencia): bool
    {
        return $user->isLiderCaracteristica();
    }

    /** Descargar archivo adjunto */
    public function descargar(User $user, Evidencia $evidencia): bool
    {
        return true;
    }
}
