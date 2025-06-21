<?php

use App\Http\Controllers\MunicipalityController;
use Illuminate\Support\Facades\Route;

Route::get('/municipios/{uf}', [MunicipalityController::class, 'index'])
    ->where('uf', '[A-Za-z]{2}');
