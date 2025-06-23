<?php

namespace Tests\Unit;

use App\Services\Providers\BrasilApiMunicipalityProvider;
use App\Services\Providers\IbgeMunicipalityProvider;
use Exception;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MunicipalityProvidersTest extends TestCase
{
    public function test_ibge_provider_returns_formatted_data()
    {
        // Arrange
        $mockResponse = [
            ['id' => 3550308, 'nome' => 'São Paulo'],
            ['id' => 3509502, 'nome' => 'Campinas'],
        ];

        Http::fake([
            'servicodados.ibge.gov.br/*' => Http::response($mockResponse, 200),
        ]);

        $provider = new IbgeMunicipalityProvider;

        // Act
        $result = $provider->listByUf('SP');

        // Assert
        $this->assertCount(2, $result);
        $this->assertEquals('São Paulo', $result[0]['name']);
        $this->assertEquals('3550308', $result[0]['ibge_code']);
    }

    public function test_brasil_api_provider_returns_formatted_data()
    {
        // Arrange
        $mockResponse = [
            ['codigo_ibge' => 3550308, 'nome' => 'São Paulo'],
            ['codigo_ibge' => 3509502, 'nome' => 'Campinas'],
        ];

        Http::fake([
            'brasilapi.com.br/*' => Http::response($mockResponse, 200),
        ]);

        $provider = new BrasilApiMunicipalityProvider;

        // Act
        $result = $provider->listByUf('SP');

        // Assert
        $this->assertCount(2, $result);
        $this->assertEquals('São Paulo', $result[0]['name']);
        $this->assertEquals('3550308', $result[0]['ibge_code']);
    }

    public function test_providers_throw_exception_on_http_error()
    {
        // Arrange
        Http::fake([
            '*' => Http::response(null, 500),
        ]);

        $ibgeProvider = new IbgeMunicipalityProvider;

        // Act & Assert
        $this->expectException(Exception::class);
        $ibgeProvider->listByUf('SP');
    }
}
