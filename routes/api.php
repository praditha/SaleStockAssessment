<?php

use Illuminate\Http\Request;

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

Route::post('/login', 'AuthenticateController@authenticate')->name('login');
Route::get('/logout', 'AuthenticateController@logout')->name('logout');

Route::group(['middleware' => ['jwt.auth', 'jwt.refresh']], function () {
	Route::get('/products', 'ProductController@showAll')->name('get-products');
});

