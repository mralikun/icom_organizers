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

	Route::resource('users', 'UsersController',array('except' => array('index', 'show')));
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
	Route::post('/organizer/update/{id}', 'OrganizerController@update');

	Route::resource('/task', 'TaskController');

	Route::get('/getOrganizerrequest/{file_name}', 'TaskController@get_organizer_request');

    Route::get("/getRequests" , "TaskController@get_all_organizers_requests");

	Route::get('/workingfields/organizers/{id}', 'WorkingFieldsController@organizers_work_in_workfields');


});


Route::group(['middleware' => ['auth']], function()
{
	//these routes are accessable by any user (operations , department , or the Admin)

	Route::post('/organizerrequest', 'TaskController@organizer_request');

	Route::resource('/conferences', 'ConferanceController');

	Route::get('/user/{username}', 'UsersController@Home');

	Route::get('/auth/logout', 'AuthController@logout');

	Route::get('/auth/onlineUser', 'AuthController@onlineUser');

	Route::resource('/workingfields', 'WorkingFieldsController');

});
Route::get('/task/mailresponse/{flag}/{token}', 'TaskController@check_email');
Route::get('/upload', 'TaskController@uploaded');
Route::post('apply/upload', 'TaskController@multiple_upload');

Route::get('/checkin','OrganizerController@check_in');
Route::post('/organizer_grade ','OrganizerController@organizer_grade');
Route::get('/conferance/organizers/{conferance_id} ','OrganizerController@organizers');
Route::get('/check/grade/{organizer_id}/{conference_id}','GradeController@grade_of_organizer');

Route::get('/checkout','OrganizerController@check_out');







