<?php

namespace App\Services;

use App\Contracts\MunicipalityProviderInterface;
use Illuminate\Support\Facades\Cache;

class MunicipalityService
{
    public function __construct(
        protected MunicipalityProviderInterface $provider
    ) {}

    public function listByUf(string $uf): array
    {
        $uf = strtolower($uf);
        $key = "municipios_{$uf}";
        $ttl = config('services.municipality_providers.cache_ttl', 86400);

        return Cache::remember($key, $ttl, fn() => $this->provider->listByUf($uf));
    }
}
