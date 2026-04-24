<?php
 
namespace App\Repositories\Contracts;
 
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
 
/**
 * Contrato base que todo repositorio CNA debe implementar.
 * Principio: Inversión de Dependencias (D de SOLID).
 * Los servicios dependen de esta abstracción, no de implementaciones concretas.
 */
interface CaracteristicaRepositoryInterface
{
    public function all(): Collection;
 
    public function findById(int $id): ?Model;
 
    public function create(array $data): Model;
 
    public function update(int $id, array $data): Model;
 
    public function delete(int $id): bool;

    public function allByFactor(int $id): Collection;

    /** Carga la característica con toda la jerarquía necesaria para la vista de evaluación. */
    public function findWithEvidencias(int $id): ?Model;
}