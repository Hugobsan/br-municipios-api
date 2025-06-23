<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Contracts\MunicipalityProviderInterface;
use App\Services\MunicipalityService;
use App\Enums\MunicipalityProviderEnum;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(
            MunicipalityProviderInterface::class,
            fn() =>
            MunicipalityProviderEnum::fromString(env('MUNICIPALITY_PROVIDER', 'brasilapi'))->createInstance()
        );

        $this->app->singleton(
            MunicipalityService::class,
            fn($app) =>
            new MunicipalityService(
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
