<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\ContactController;


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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function (){
   Route::post('register', [\App\Http\Controllers\Admin\AuthController::class, 'register']);
   Route::post('login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);
   Route::apiResource('users', \App\Http\Controllers\Admin\UserController::class);
   Route::apiResource('contacts', \App\Http\Controllers\Admin\ContactController::class);
});

Route::group(['prefix' => 'auth'], function (){
    Route::post('register', [\App\Http\Controllers\User\AuthController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\User\AuthController::class, 'login']);
});

Route::group(['prefix' => 'client'], function () {
    Route::apiResource('contacts', ContactController::class);
});
