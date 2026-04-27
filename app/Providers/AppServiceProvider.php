<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Repositorios
use App\Repositories\FactorRepository;
use App\Repositories\CaracteristicaRepository;
use App\Repositories\AspectoRepository;
use App\Repositories\EvidenciaRepository;
use App\Repositories\ResultadoRepository;
use App\Repositories\UserRepository;
use App\Repositories\Contracts\FactorRepositoryInterface;
use App\Repositories\Contracts\CaracteristicaRepositoryInterface;
use App\Repositories\Contracts\AspectoRepositoryInterface;
use App\Repositories\Contracts\EvidenciaRepositoryInterface;
use App\Repositories\Contracts\ResultadoRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

// Servicios
use App\Services\UserRoleService;
use App\Services\StatusResolver;
use App\Services\Contracts\UserRoleServiceInterface;
use App\Services\Contracts\StatusResolverInterface;

// Modelos
use App\Models\Factor;
use App\Models\Caracteristica;
use App\Models\Aspecto;
use App\Models\Evidencia;
use App\Models\Resultado;
use App\Models\User;

// Policies
use App\Policies\FactorPolicy;
use App\Policies\CaracteristicaPolicy;
use App\Policies\AspectoPolicy;
use App\Policies\EvidenciaPolicy;
use App\Policies\ResultadoPolicy;
use App\Policies\UserPolicy;

// Observers
use App\Observers\FactorObserver;
use App\Observers\CaracteristicaObserver;
use App\Observers\AspectoObserver;
use App\Observers\EvidenciaObserver;
use App\Observers\ResultadoObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repositorios
        $this->app->bind(FactorRepositoryInterface::class, FactorRepository::class);
        $this->app->bind(CaracteristicaRepositoryInterface::class, CaracteristicaRepository::class);
        $this->app->bind(AspectoRepositoryInterface::class, AspectoRepository::class);
        $this->app->bind(EvidenciaRepositoryInterface::class, EvidenciaRepository::class);
        $this->app->bind(ResultadoRepositoryInterface::class, ResultadoRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Servicios
        $this->app->bind(UserRoleServiceInterface::class, UserRoleService::class);
        $this->app->singleton(StatusResolverInterface::class, StatusResolver::class);
    }

    public function boot(): void
    {
        // Policies — equivalente al $policies[] del AuthServiceProvider
        Gate::policy(Factor::class,        FactorPolicy::class);
        Gate::policy(Caracteristica::class, CaracteristicaPolicy::class);
        Gate::policy(Aspecto::class,        AspectoPolicy::class);
        Gate::policy(Evidencia::class,      EvidenciaPolicy::class);
        Gate::policy(Resultado::class,      ResultadoPolicy::class);
        Gate::policy(User::class,           UserPolicy::class);

        // Gates de dashboard (sin modelo asociado)
        Gate::define('dashboard.view', fn($user) => true);
        Gate::define('dashboard.view-global', fn($user) => $user->isAdmin() || $user->isDirector());

        // Observers de auditoría — se disparan automáticamente en created/updated/deleted
        Factor::observe(FactorObserver::class);
        Caracteristica::observe(CaracteristicaObserver::class);
        Aspecto::observe(AspectoObserver::class);
        Evidencia::observe(EvidenciaObserver::class);
        Resultado::observe(ResultadoObserver::class);
    }
}