<?php

namespace App\Http\Controllers;

use App\Facades\Municipality;
use App\Http\Resources\MunicipalityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use Exception;

class MunicipalityController extends Controller
{
    public function index(Request $request, string $uf): AnonymousResourceCollection|JsonResponse
    {
        try {
            $data = Municipality::listByUf($uf);
            return MunicipalityResource::collection($data);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Não foi possível obter a lista de municípios',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
