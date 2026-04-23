<?php
 
namespace App\Repositories\Contracts;
 
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
 
/**
 * Contrato base que todo repositorio CNA debe implementar.
 * Principio: Inversión de Dependencias (D de SOLID).
 * Los servicios dependen de esta abstracción, no de implementaciones concretas.
 */
interface EvidenciaRepositoryInterface
{
    public function all(): Collection;
 
    public function findById(int $id): ?Model;
 
    public function create(array $data): Model;
 
    public function update(int $id, array $data): Model;
 
    public function delete(int $id): bool;

    public function allByAspecto(int $id): Collection;
}