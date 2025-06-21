<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\MunicipalityProviderInterface;
use App\Services\Providers\IbgeMunicipalityProvider;
use App\Services\Providers\BrasilApiMunicipalityProvider;
use App\Services\MunicipalityService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(MunicipalityProviderInterface::class, fn() => match (env('MUNICIPALITY_PROVIDER')) {
            'ibge' => new IbgeMunicipalityProvider(),
            'brasilapi' => new BrasilApiMunicipalityProvider(),
            default => throw new \InvalidArgumentException('Provider inválido'),
        });

        $this->app->singleton(MunicipalityService::class, fn($app) =>
            new MunicipalityService($app->make(MunicipalityProviderInterface::class))
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
