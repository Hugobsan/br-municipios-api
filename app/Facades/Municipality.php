<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Municipality extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\MunicipalityService::class;
    }
}
