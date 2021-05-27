<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
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

Route::get('/user', [UserController::class, 'show']);
Route::get('/user/{id}', [UserController::class, 'show']);
Route::post('/user/login', [UserController::class, 'store']);
Route::post('/user/register', [UserController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/user/search', [UserController::class, 'search']);
    Route::post('/user/logout', [UserController::class, 'logout']);
    Route::put('/user/update/{id}', [UserController::class, 'update']);
});
