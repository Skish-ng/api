<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SavedArtisanController;
use App\Http\Controllers\PageViewController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FeaturedController;
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

Route::get('/user', [UserController::class, 'index']);
Route::get('/user/id/{id}', [UserController::class, 'show']);
Route::get('/user/search', [UserController::class, 'search']);

Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/forgotPassword', [UserController::class, 'forgot_password']);
Route::post('/user/login', [UserController::class, 'store']);
Route::post('/pageview/new', [PageViewController::class, 'store']);

Route::put('/user/confirm/password', [UserController::class, 'confirmForgotPassword']);
Route::put('/user/confirm/email', [UserController::class, 'confirmEMail']);


#USERS

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('/user/logout', [UserController::class, 'logout']);
    Route::post('/user/update', [UserController::class, 'update']);
});

#SAVED ARTISANS

Route::group(['middleware' => ['auth:sanctum']], function()
{
    Route::post('/savedartisan/add', [SavedArtisanController::class, 'store']);
    Route::delete('/savedartisan/delete/{id}', [SavedArtisanController::class, 'delete']);
    Route::get('/savedartisan', [SavedArtisanController::class, 'show']);
    
});

#PAGE VIEWS

Route::group(['middleware' => ['auth:sanctum']], function(){

    Route::get('/pageview/count', [PageViewController::class, 'clickCounts']);
    Route::get('/pageview/count/today', [PageViewController::class, 'todayClick']);

});

#REVIEWS

Route::group(['middleware' => ['auth:sanctum']], function()
    {
        Route::post('/review/new', [ReviewController::class, 'store']);
        Route::get('/review/id/{id}', [ReviewController::class, 'show']);
    }
);

#MESSAGES

Route::group(['middleware' => ['auth:sanctum']], function()
    {

        Route::post('/message/new', [MessageController::class, 'store']);

        Route::get('/message/user/{id}', [MessageController::class, 'show']);
        Route::get('/message', [MessageController::class, 'index']);

        Route::delete('/message/delete/{id}', [MessageController::class, 'delete']);
    }
);

#FEATURED

Route::group(['middleware' => ['auth:sanctum']], function()
    {

        Route::post('/featured/new', [FeaturedController::class, 'store']);
        Route::post('/featured/update/{id}', [FeaturedController::class, 'update']);
        
        Route::get('/featured', [FeaturedController::class, 'index']);
        Route::delete('/featured/delete/{id}', [FeaturedController::class, 'delete']);
    }
);


#PRICEINFO

Route::group(['middleware' => ['auth:sanctum']], function()
    {

        Route::post('/Priceinfo/new', [FeaturedController::class, 'store']);
        Route::post('/Priceinfo/update/{id}', [FeaturedController::class, 'update']);
        
        Route::delete('/Priceinfo/delete/{id}', [FeaturedController::class, 'delete']);
    }
);


