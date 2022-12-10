<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

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

//Route::post('/tokens/create', function (Request $request) {
//$token = $request->user()->createToken($request->token_name);

Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/ping', function (Request $request) {
    return ['response' => 'pong'];
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', function (Request $request) {
        return auth()->user();
    });

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // only admin
    Route::group(['middleware' => ['isAdmin']], function () {
      Route::get('/isAdmin', function (Request $request) {
        return response()->json(['admin' => true]);
      });

      Route::post('/user', [UserController::class, 'create']);
      Route::get('/user/{id}', [UserController::class, 'read']);
      Route::delete('/user/{id}', [UserController::class, 'delete']);

      Route::post('/report', [ReportController::class, 'create']);
      Route::delete('/report/{id}', [ReportController::class, 'delete']);
    });
    Route::put('/report/{id}', [ReportController::class, 'update']);
    Route::put('/user/{id}', [UserController::class, 'update']);
});
