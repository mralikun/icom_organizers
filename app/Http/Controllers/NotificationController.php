<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class NotificationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$messages = Notification::all();
		return $messages;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	/* return the total of unseen messages */

	public function count_of_unseen_messages(){
		$num_of_messages = Notification::where('seen', '=', 0)->count();
		return $num_of_messages;
	}

	/*return all unseen messages */

	public function unseen_messages(){

		$messages = Notification::where('seen', '=', 0)->get();
		return $messages;
	}

	/* update the unseen message to seen when admin show it */

	public function update_msg_status($id){
		return Notification::update_unseen_field($id);

	}

}
