<?php

namespace App\Services;

use App\Contracts\MunicipalityProviderInterface;

class MunicipalityService
{
    public function __construct(
        protected MunicipalityProviderInterface $provider
    ) {}

    public function listByUf(string $uf): array
    {
        return $this->provider->listByUf($uf);
    }
}
