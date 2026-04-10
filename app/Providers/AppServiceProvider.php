<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\CnaRepositoryInterface;
use App\Repositories\FactorRepository;
use App\Repositories\CaracteristicaRepository;
use App\Repositories\AspectoRepository;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        $this->app->bind(FactorRepository::class, FactorRepository::class);
        $this->app->bind(CaracteristicaRepository::class, CaracteristicaRepository::class);
        $this->app->bind(AspectoRepository::class, AspectoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
