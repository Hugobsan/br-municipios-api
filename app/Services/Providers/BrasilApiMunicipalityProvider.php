<?php

namespace App\Services\Providers;

use App\Contracts\MunicipalityProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Exception;

class BrasilApiMunicipalityProvider implements MunicipalityProviderInterface
{
    protected const BASE_URL = 'https://brasilapi.com.br/api/ibge/municipios/v1/';

    public function listByUf(string $uf): array
    {
        $url = self::BASE_URL . strtoupper($uf);
        $response = Http::timeout(30)->get($url);

        if (!$response->successful()) {
            throw new RequestException($response);
        }

        $data = $response->json();

        if (empty($data)) {
            throw new Exception('Resposta vazia do provedor BrasilAPI');
        }

        return collect($data)->map(fn($item) => [
            'name' => $item['nome'],
            'ibge_code' => $item['codigo_ibge'],
        ])->toArray();
    }
}
