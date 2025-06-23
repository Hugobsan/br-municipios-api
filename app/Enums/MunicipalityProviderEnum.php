<?php

namespace App\Enums;

enum MunicipalityProviderEnum: string
{
    case IBGE = 'ibge';
    case BRASIL_API = 'brasilapi';

    public function getProviderClass(): string
    {
        return match ($this) {
            self::IBGE => \App\Services\Providers\IbgeMunicipalityProvider::class,
            self::BRASIL_API => \App\Services\Providers\BrasilApiMunicipalityProvider::class,
        };
    }

    public function createInstance(): \App\Contracts\MunicipalityProviderInterface
    {
        $class = $this->getProviderClass();

        return new $class;
    }

    public static function getAllInstances(): array
    {
        return array_map(fn ($case) => $case->createInstance(), self::cases());
    }

    public static function fromString(string $provider): self
    {
        return match ($provider) {
            'ibge' => self::IBGE,
            'brasilapi' => self::BRASIL_API,
            default => throw new \InvalidArgumentException("Provider inválido: {$provider}"),
        };
    }
}
