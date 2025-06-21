<?php

namespace App\Services\Providers;

use App\Contracts\MunicipalityProviderInterface;
use Illuminate\Support\Facades\Http;

class IbgeMunicipalityProvider implements MunicipalityProviderInterface
{
    public function listByUf(string $uf): array
    {
        $url = config('services.municipality_providers.ibge') . strtolower($uf) . '/municipios';

        $response = Http::get($url);

        return collect($response->json())->map(fn($item) => [
            'name' => $item['nome'],
            'ibge_code' => $item['id'],
        ])->toArray();
    }
}
