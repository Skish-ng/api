<?php
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    //dd("Starting the API authentication");
    dd(User::insert('insert into users (id, name) values (integer, integer)', [1, 'Dayle']));
    dd(end(User::find(1)));
});
