<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\MunicipalityService;
use App\Services\Providers\IbgeMunicipalityProvider;
use App\Services\Providers\BrasilApiMunicipalityProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class MunicipalityServiceIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_fallback_mechanism_works_when_primary_provider_fails()
    {
        // Arrange
        Http::fake([
            'servicodados.ibge.gov.br/*' => Http::response(null, 500), // IBGE fails
            'brasilapi.com.br/*' => Http::response([
                ['codigo_ibge' => 3550308, 'nome' => 'São Paulo']
            ], 200) // BrasilAPI succeeds
        ]);

        $ibgeProvider = new IbgeMunicipalityProvider();
        $brasilApiProvider = new BrasilApiMunicipalityProvider();
        $providers = [$ibgeProvider, $brasilApiProvider];
        
        $service = new MunicipalityService($ibgeProvider, $providers);

        // Act
        $result = $service->listByUf('SP');

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals('São Paulo', $result[0]['name']);
        $this->assertEquals('3550308', $result[0]['ibge_code']);
    }

    public function test_throws_exception_when_all_providers_fail()
    {
        // Arrange
        Http::fake([
            '*' => Http::response(null, 500) // All providers fail
        ]);

        $ibgeProvider = new IbgeMunicipalityProvider();
        $brasilApiProvider = new BrasilApiMunicipalityProvider();
        $providers = [$ibgeProvider, $brasilApiProvider];
        
        $service = new MunicipalityService($ibgeProvider, $providers);

        // Act & Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Não foi possível obter a lista de municípios');
        
        $service->listByUf('SP');
    }
}
