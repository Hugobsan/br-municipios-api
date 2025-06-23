<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\MunicipalityService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MunicipalityControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_returns_municipalities_for_valid_uf()
    {
        // Arrange
        $uf = 'sp';
        $expectedData = [
            ['name' => 'São Paulo', 'ibge_code' => '3550308'],
            ['name' => 'Campinas', 'ibge_code' => '3509502']
        ];

        // Mock the service to return expected data
        $this->mock(MunicipalityService::class, function ($mock) use ($uf, $expectedData) {
            $mock->shouldReceive('listByUf')
                ->with($uf)
                ->once()
                ->andReturn($expectedData);
        });

        // Act
        $response = $this->getJson("/api/municipios/{$uf}");

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'name',
                        'ibge_code'
                    ]
                ]
            ])
            ->assertJsonCount(2, 'data');
    }

    public function test_validates_uf_parameter_format()
    {
        // Act & Assert - Invalid UF with numbers
        $response = $this->getJson('/api/municipios/sp1');
        $response->assertStatus(404);

        // Act & Assert - Invalid UF too long
        $response = $this->getJson('/api/municipios/spa');
        $response->assertStatus(404);

        // Act & Assert - Valid UF format
        $this->mock(MunicipalityService::class, function ($mock) {
            $mock->shouldReceive('listByUf')->andReturn([]);
        });
        
        $response = $this->getJson('/api/municipios/sp');
        $response->assertStatus(200);
    }

    public function test_handles_service_exceptions_gracefully()
    {
        // Arrange
        $this->mock(MunicipalityService::class, function ($mock) {
            $mock->shouldReceive('listByUf')
                ->andThrow(new \Exception('Service unavailable'));
        });

        // Act
        $response = $this->getJson('/api/municipios/sp');

        // Assert
        $response->assertStatus(500);
    }

    public function test_returns_empty_array_when_no_municipalities_found()
    {
        // Arrange
        $this->mock(MunicipalityService::class, function ($mock) {
            $mock->shouldReceive('listByUf')
                ->andReturn([]);
        });

        // Act
        $response = $this->getJson('/api/municipios/xx');

        // Assert
        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }
}
