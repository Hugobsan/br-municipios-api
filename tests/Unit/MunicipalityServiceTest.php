<?php

namespace Tests\Unit;

use App\Contracts\MunicipalityProviderInterface;
use App\Services\MunicipalityService;
use Exception;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class MunicipalityServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_returns_cached_data_when_available()
    {
        // Arrange
        $mockProvider = $this->createMock(MunicipalityProviderInterface::class);
        $service = new MunicipalityService($mockProvider, [$mockProvider]);
        $uf = 'sp';
        $cachedData = [['name' => 'São Paulo', 'ibge_code' => '3550308']];

        Cache::put("municipios_{$uf}", $cachedData, 3600);

        // Act
        $result = $service->listByUf($uf);

        // Assert
        $this->assertEquals($cachedData, $result);
    }

    public function test_fallback_to_alternative_provider_on_primary_failure()
    {
        // Arrange
        $mockPrimaryProvider = $this->createMock(MunicipalityProviderInterface::class);
        $mockAlternativeProvider = $this->createMock(MunicipalityProviderInterface::class);

        $expectedData = [['name' => 'São Paulo', 'ibge_code' => '3550308']];
        $uf = 'sp';

        // Primary provider fails after retries
        $mockPrimaryProvider->method('listByUf')
            ->with($uf)
            ->willThrowException(new Exception('Primary provider failed'));

        // Alternative provider succeeds
        $mockAlternativeProvider->expects($this->once())
            ->method('listByUf')
            ->with($uf)
            ->willReturn($expectedData);

        $providers = [$mockPrimaryProvider, $mockAlternativeProvider];
        $service = new MunicipalityService($mockPrimaryProvider, $providers);

        // Act
        $result = $service->listByUf($uf);

        // Assert
        $this->assertEquals($expectedData, $result);
    }

    public function test_throws_exception_when_all_providers_fail()
    {
        // Arrange
        $mockPrimaryProvider = $this->createMock(MunicipalityProviderInterface::class);
        $mockAlternativeProvider = $this->createMock(MunicipalityProviderInterface::class);

        $uf = 'sp';

        // Both providers fail
        $mockPrimaryProvider->method('listByUf')
            ->with($uf)
            ->willThrowException(new Exception('Primary provider failed'));

        $mockAlternativeProvider->method('listByUf')
            ->with($uf)
            ->willThrowException(new Exception('Alternative provider failed'));

        $providers = [$mockPrimaryProvider, $mockAlternativeProvider];
        $service = new MunicipalityService($mockPrimaryProvider, $providers);

        // Act & Assert
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Não foi possível obter a lista de municípios');

        $service->listByUf($uf);
    }
}
