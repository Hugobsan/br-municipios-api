<?php

namespace App\Contracts;

interface MunicipalityProviderInterface
{
    public function listByUf(string $uf): array;
}
