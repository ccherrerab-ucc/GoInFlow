<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

/**
 * Principio S — única responsabilidad: inyectar campos de auditoría.
 * Centraliza created_by / updated_by en un solo lugar.
 * Si mañana cambia la lógica de auditoría (ej: se agrega ip, timestamp extra),
 * solo se modifica aquí — Principio O.
 */
class AuditoriaService
{
    /**
     * Agrega created_by y updated_by al array de datos.
     */
    public function alCrear(array $datos): array
    {
        $datos['created_by'] = Auth::id();
        $datos['updated_by'] = Auth::id();
        return $datos;
    }

    /**
     * Agrega solo updated_by al array de datos.
     */
    public function alActualizar(array $datos): array
    {
        $datos['updated_by'] = Auth::id();
        return $datos;
    }
}