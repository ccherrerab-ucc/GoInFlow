<?php

namespace App\Services;

use App\Models\StatusCna;
use App\Services\Contracts\StatusResolverInterface;
use RuntimeException;

/**
 * Resuelve IDs de estado por nombre.
 * Principio S — única responsabilidad: traducir nombre → id_status.
 * Principio D — los servicios consumen la interfaz, no esta clase directa.
 *
 * Cachea los resultados en memoria para no consultar la BD
 * múltiples veces en la misma request.
 */
class StatusResolver implements StatusResolverInterface
{
    private array $cache = [];

    public function resolverPorNombre(string $nombre): int
    {
        if (isset($this->cache[$nombre])) {
            return $this->cache[$nombre];
        }

        $status = StatusCna::where('name', $nombre)->first();

        if (!$status) {
            throw new RuntimeException("Estado '{$nombre}' no encontrado en status_cna.");
        }

        return $this->cache[$nombre] = $status->id_status;
    }

    public function activo(): int
    {
        return $this->resolverPorNombre('Activo');
    }

    public function suprimido(): int
    {
        return $this->resolverPorNombre('Suprimido');
    }
}