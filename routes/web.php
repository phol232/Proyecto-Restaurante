<?php

use App\Http\Controllers\CargadoDatosController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\PlatilloController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaIngredienteController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\IngredienteController;
use App\Http\Controllers\MesaController;

use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\MovimientoController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login',    [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get ('me',     [AuthController::class, 'me']);

    Route::apiResource('empleados', EmpleadoController::class);
    Route::apiResource('mesas',     MesaController::class);

    Route::apiResource('proveedores', ProveedorController::class);

    Route::apiResource('movimientos', MovimientoController::class);
    Route::apiResource('platillos', PlatilloController::class);
    Route::get('form-data', [CargadoDatosController::class, 'getFormData']);
});


Route::apiResource('categorias-ingredientes', CategoriaIngredienteController::class);
Route::apiResource('ingredientes',            IngredienteController::class);
Route::apiResource('clientes', ClientesController::class);
