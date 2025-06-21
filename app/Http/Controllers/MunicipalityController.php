<?php

namespace App\Http\Controllers;

use App\Facades\Municipality;
use App\Http\Resources\MunicipalityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MunicipalityController extends Controller
{
    public function index(Request $request, string $uf): AnonymousResourceCollection
    {
        $data = Municipality::listByUf($uf);

        return MunicipalityResource::collection($data);
    }
}
