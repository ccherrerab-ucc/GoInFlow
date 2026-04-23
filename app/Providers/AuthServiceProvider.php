<?php

namespace App\Providers;

use App\Models\Aspecto;
use App\Models\Caracteristica;
use App\Models\Evidencia;
use App\Models\Factor;
use App\Policies\AspectoPolicy;
use App\Policies\CaracteristicaPolicy;
use App\Policies\EvidenciaPolicy;
use App\Policies\FactorPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapeo Modelo → Policy.
     * Laravel resuelve automáticamente qué policy usar
     * cuando llamas $this->authorize() en el controlador.
     */
    protected $policies = [
        Factor::class         => FactorPolicy::class,
        Caracteristica::class => CaracteristicaPolicy::class,
        Aspecto::class        => AspectoPolicy::class,
        Evidencia::class      => EvidenciaPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}