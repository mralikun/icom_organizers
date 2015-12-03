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

Route::group(['middleware' => ['auth', 'admin']], function()
{
	// these routes are accessable by the Admin Only

Route::resource('Admin', 'AdminController');
});

Route::group(['middleware' => ['auth', 'operation']], function()
{
	// these routes are accessable by operations users or The Admin

	Route::resource('organizers', 'OrganizerController',array('except' => array('update', 'show')));

	/**
	 * 	I detached This Route From Organizers Resource Route Because of some issues in
	 *	Sending Data With Ajax Through Put/Patch Method So I Had to detach it from the
	 *	Resource by adding  array('except' => array('update') and Register another Post
	 *	Route Below .
	 */

	Route::resource('/task', 'TaskController');

	Route::resource('/workingfields', 'WorkingFieldsController');

	Route::resource('/conferences', 'ConferanceController');

	Route::get('/tasks/json', 'TaskController@testjson');

	Route::get('/workingfields/organizers/{id}', 'WorkingFieldsController@organizers_work_in_workfields');

	Route::get('/task/mailresponse/{flag}/{token}', 'TaskController@check_email');

	Route::post('/organizer/update/{id}', 'OrganizerController@update');

});
Route::group(['middleware' => ['auth']], function()
{
	//these routes are accessable by any user (operations , department , or the Admin)

	Route::resource('users', 'UsersController');

	Route::get('/user/{username}', 'UsersController@Home');

	Route::get('/auth/logout', 'AuthController@logout');

	Route::get('/auth/onlineUser', 'AuthController@onlineUser');

});
Route::get('/sheet','ExcelController@organize_sheet');
Route::get('/getsheet','ExcelController@sheet');
