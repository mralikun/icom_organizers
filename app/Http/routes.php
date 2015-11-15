<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');


Route::post('/auth/login', 'AuthController@login');
Route::get('/auth/logout', 'AuthController@logout');

Route::group(['middleware' => ['auth', 'admin']], function()
{
	// these routes are accessable by the Admin Only
	Route::resource('Admin', 'AdminController');
});

Route::group(['middleware' => ['auth', 'operations']], function()
{
	// these routes are accessable by operations users or The Admin
});

Route::group(['middleware' => ['auth']], function()
{
	// these routes are accessable by operations , department , or the Admin
});