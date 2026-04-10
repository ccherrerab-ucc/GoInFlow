<?php
 
namespace App\Repositories;
 
use App\Models\Factor;
use App\Repositories\Contracts\CnaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
 
/**
 * Repositorio Factor.
 * Principio: Responsabilidad Única (S de SOLID).
 * Solo se encarga de la persistencia de Factor.
 */
class FactorRepository implements CnaRepositoryInterface
{
    public function __construct(private readonly Factor $model) {}
 
    public function all(): Collection
    {
        return $this->model
            ->with(['status', 'responsableUser', 'caracteristicas'])
            ->orderBy('id_factor', 'desc')
            ->get();
    }
 
    public function findById(int $id): ?Model
    {
        return $this->model
            ->with(['status', 'responsableUser', 'caracteristicas'])
            ->findOrFail($id);
    }
 
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }
 
    public function update(int $id, array $data): Model
    {
        $factor = $this->model->findOrFail($id);
        $factor->update($data);
        return $factor->fresh();
    }
 
    public function delete(int $id): bool
    {
        $factor = $this->model->findOrFail($id);
        return $factor->delete();
    }
}