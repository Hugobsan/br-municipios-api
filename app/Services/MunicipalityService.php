<?php

namespace App\Services;

use App\Contracts\MunicipalityProviderInterface;
use Exception;
use Illuminate\Support\Facades\Cache;

class MunicipalityService
{
    protected array $providers;

    protected int $currentProviderIndex = 0;

    public function __construct(
        protected MunicipalityProviderInterface $provider,
        array $providers
    ) {
        $this->providers = $providers;
        $this->currentProviderIndex = $this->getCurrentProviderIndex();
    }

    public function listByUf(string $uf): array
    {
        $uf = strtolower($uf);
        $key = "municipios_{$uf}";
        $ttl = config('services.municipality_providers.cache_ttl', 86400);

        return Cache::remember($key, $ttl, fn () => $this->fetchMunicipalities($uf));
    }

    protected function fetchMunicipalities(string $uf): array
    {
        $maxRetries = config('services.municipality_providers.max_retries', 3);

        // Primeiro tenta com o provider atual múltiplas vezes
        $result = $this->tryProviderWithRetries($this->provider, $uf, $maxRetries);
        if ($result !== null) {
            return $result;
        }

        // Se falhou, tenta com os outros providers
        $result = $this->tryOtherProviders($uf);
        if ($result !== null) {
            return $result;
        }

        throw new Exception("Não foi possível obter a lista de municípios para a UF {$uf}.");
    }

    protected function tryProviderWithRetries(MunicipalityProviderInterface $provider, string $uf, int $maxRetries): ?array
    {
        for ($retry = 0; $retry < $maxRetries; $retry++) {
            try {
                $result = $provider->listByUf($uf);

                if (! empty($result)) {
                    return $result;
                }
            } catch (Exception $e) {
                if ($retry < $maxRetries - 1) {
                    $this->waitBeforeRetry();
                }
            }
        }

        return null;
    }

    protected function tryOtherProviders(string $uf): ?array
    {
        foreach ($this->providers as $provider) {
            // Pula o provider atual já testado
            if ($provider === $this->provider) {
                continue;
            }

            try {
                $result = $provider->listByUf($uf);

                if (! empty($result)) {
                    return $result;
                }
            } catch (Exception $e) {
                continue;
            }
        }

        return null;
    }

    protected function waitBeforeRetry(): void
    {
        $delayMs = config('services.municipality_providers.retry_delay_ms', 200);
        usleep($delayMs * 1000);
    }

    protected function getCurrentProviderIndex(): int
    {
        $currentProviderClass = get_class($this->provider);

        foreach ($this->providers as $index => $provider) {
            if (get_class($provider) === $currentProviderClass) {
                return $index;
            }
        }

        return 0;
    }
}
