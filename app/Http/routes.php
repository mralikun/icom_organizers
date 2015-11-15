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

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::group(['middleware' => ['auth', 'admin']], function()
{
	// these routes are accessable by the Admin Only
});

Route::group(['middleware' => ['auth', 'operations']], function()
{
	// these routes are accessable by operations users or The Admin
});

Route::group(['middleware' => ['auth']], function()
{
	// these routes are accessable by operations , department , or the Admin
});