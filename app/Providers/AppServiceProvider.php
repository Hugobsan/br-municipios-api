<?php

namespace App\Providers;

use App\Contracts\MunicipalityProviderInterface;
use App\Enums\MunicipalityProviderEnum;
use App\Services\MunicipalityService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            MunicipalityProviderInterface::class,
            fn () => MunicipalityProviderEnum::fromString(env('MUNICIPALITY_PROVIDER', 'brasilapi'))->createInstance()
        );

        $this->app->singleton(
            MunicipalityService::class,
            fn ($app) => new MunicipalityService(
                $app->make(MunicipalityProviderInterface::class),
                MunicipalityProviderEnum::getAllInstances()
            )
        );
    }

    public function boot(): void
    {
        //
    }
}
