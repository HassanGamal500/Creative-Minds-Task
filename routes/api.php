<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['namespace' => 'App\Http\Controllers\Api'], function() {
    Route::post('sign_in', 'AuthController@login');
    Route::post('sign_up', 'AuthController@register');
    Route::post('pin_code', 'AuthController@pinCode');
});

Route::group(['namespace' => 'App\Http\Controllers\Api', 'middleware' => ['auth.jwt']], function() {
    Route::post('get_profile', 'AuthController@profile');
    Route::post('update_profile', 'AuthController@updateProfile');
    Route::post('logout', 'AuthController@logout');
});