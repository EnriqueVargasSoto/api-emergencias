<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\EmergenciaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('registro', [UsuarioController::class, 'registro']);
Route::post('login', [UsuarioController::class, 'login']);
Route::post('update-perfil', [UsuarioController::class, 'updateUsuario']);
Route::post('btn-emergencia', [EmergenciaController::class, 'btnEmergencia']);
Route::get('list-emergencia/{id}', [EmergenciaController::class, 'listEmergencia']);