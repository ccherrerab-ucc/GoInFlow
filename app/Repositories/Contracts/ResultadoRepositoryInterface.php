<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Principio: Inversión de Dependencias (D de SOLID).
 * Los servicios dependen de esta abstracción, no de implementaciones concretas.
 */
interface ResultadoRepositoryInterface
{
    public function all(): Collection;

    public function findById(int $id): ?Model;

    public function create(array $data): Model;

    public function update(int $id, array $data): Model;

    public function delete(int $id): bool;

    public function allByTipoRelacion(string $tipo, int $idReferencia): Collection;
}
