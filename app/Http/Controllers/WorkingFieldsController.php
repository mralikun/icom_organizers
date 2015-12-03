<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\WorkingFields;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class WorkingFieldsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	/*
	 	 select all workingfield stored in database.
	 */
	public function index()
	{

		 $workingfields = WorkingFields::select('id','name')->get();
		return $workingfields;

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
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	/*
			update workingfields data
	 */
	public function edit($id)
	{
		$workingfields = WorkingFields::find($id);

		$workingfields->teamleader = Input::get('teamleader');
		$workingfields->teamleader_email = Input::get('teamleader_email');
		$workingfields->teamleader_phone = Input::get('teamleader_phone');

		$workingfields->save();

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

/*
	select all organizer who work in workingfields
*/
	public function organizers_work_in_workfields($id)
	{
		return $organizers =WorkingFields::find($id)->organizers;
	}


}
