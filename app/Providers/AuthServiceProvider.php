<?php

namespace App\Providers;

use App\Models\Aspecto;
use App\Models\Caracteristica;
use App\Models\Evidencia;
use App\Models\Factor;
use App\Models\Resultado;
use App\Policies\AspectoPolicy;
use App\Policies\CaracteristicaPolicy;
use App\Policies\EvidenciaPolicy;
use App\Policies\FactorPolicy;
use App\Policies\ResultadoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Factor::class         => FactorPolicy::class,
        Caracteristica::class => CaracteristicaPolicy::class,
        Aspecto::class        => AspectoPolicy::class,
        Evidencia::class      => EvidenciaPolicy::class,
        Resultado::class      => ResultadoPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}