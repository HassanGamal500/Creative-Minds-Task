<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers\Admin'], function (){
    // Auth Login
    Route::get('/login', 'LoginController@showLoginForm')->name('admin.login');
	Route::post('/login', 'LoginController@login')->name('admin.login.post');
	Route::get('/logout', 'LoginController@logout')->name('admin.logout');
});

Route::group(['namespace' => 'App\Http\Controllers\Admin', 'middleware' => ['admin']], function (){
    // Users
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/users', 'UserController@index')->name('users');
    Route::get('/add_user', 'UserController@create')->name('add_user');
    Route::post('/add_user', 'UserController@store')->name('store_user');
    Route::get('/edit_user/{id}', 'UserController@edit')->name('edit_user');
    Route::post('/edit_user/{id}', 'UserController@update')->name('update_user');
    Route::delete('/delete_user/{id}', 'UserController@destroy')->name('delete_user');
});
