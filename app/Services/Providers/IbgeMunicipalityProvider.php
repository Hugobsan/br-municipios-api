<?php

namespace App\Services\Providers;

use App\Contracts\MunicipalityProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Exception;

class IbgeMunicipalityProvider implements MunicipalityProviderInterface
{
    protected const BASE_URL = 'https://servicodados.ibge.gov.br/api/v1/localidades/estados/';

    public function listByUf(string $uf): array
    {
        $url = self::BASE_URL . strtolower($uf) . '/municipios';
        $response = Http::timeout(30)->get($url);

        if (!$response->successful()) {
            throw new RequestException($response);
        }

        $data = $response->json();

        if (empty($data)) {
            throw new Exception('Resposta vazia do provedor IBGE');
        }

        return collect($data)->map(fn($item) => [
            'name' => $item['nome'],
            'ibge_code' => $item['id'],
        ])->toArray();
    }
}
