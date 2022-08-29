<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaracteristicaNegocioController;
use App\Http\Controllers\NegocioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\TipoNegocioController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group(['middleware' => 'api', 'prefix' => 'admin'], function ($router) {

    Route::post('roles/assign', [UserController::class, 'assignRole']);
    Route::post('roles/unassign', [UserController::class, 'unassignRole']);
    Route::post('roles/users_by_role', [UserController::class, 'usersByRole']);
    Route::get('roles', [RolController::class, 'index']);

    Route::apiResource('tipo_negocio', TipoNegocioController::class);
    Route::apiResource('tipo_negocio/caracteristicas', CaracteristicaNegocioController::class);
    Route::apiResource('negocios', NegocioController::class);
});
