<?php

namespace App\Services\Providers;

use App\Contracts\MunicipalityProviderInterface;
use Illuminate\Support\Facades\Http;

class BrasilApiMunicipalityProvider implements MunicipalityProviderInterface
{
    public function listByUf(string $uf): array
    {
        $url = config('services.municipality_providers.brasilapi') . strtoupper($uf);

        $response = Http::get($url);

        return collect($response->json())->map(fn($item) => [
            'name' => $item['nome'],
            'ibge_code' => $item['codigo_ibge'],
        ])->toArray();
    }
}
