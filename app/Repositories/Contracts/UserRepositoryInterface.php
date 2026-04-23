<?php

namespace App\Repositories\Contracts;

use App\Models\User;

/**
 * Contrato para acceder a usuarios.
 * Evita que los servicios llamen User::find() directamente.
 * Principio D — los servicios dependen de esta abstracción.
 */
interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
}