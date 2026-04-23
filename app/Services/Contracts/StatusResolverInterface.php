<?php

namespace App\Services\Contracts;

/**
 * Contrato para resolver IDs de estado por nombre.
 * Evita que los servicios dependan directamente de StatusCna (Eloquent).
 * Principio D — los servicios dependen de esta abstracción.
 */
interface StatusResolverInterface
{
    /**
     * Devuelve el id_status dado un nombre exacto.
     * Lanza excepción si no existe.
     */
    public function resolverPorNombre(string $nombre): int;

    public function activo(): int;

    public function suprimido(): int;
}