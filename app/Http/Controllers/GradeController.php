<?php namespace App\Http\Controllers;

use App\grading;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class GradeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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

	/*return grade of organizer in task if found
	*  else return false
	*/

	public function grade_of_organizer($organizer_id,$task_id){

		$grades =grading::where('task_id','=',$task_id)->where('organizer_id','=',$organizer_id)->get();

		if(!empty($grades)){
			return $grades;
		}else{
			return "false";
		}

	}

	public function update_grade(){

		$grades =Input::get('grades');
		$task_id = Input::get('task_id');
		$organizer_id = Input::get('organizer_id');
		 $grades_data = grading::where('organizer_id','=',$organizer_id)->where('task_id','=',$task_id)->get();
		foreach($grades_data as $grade){
			$grade_data = grading::find($grade->id);
			$grade_data->delete();
		}
		return grading::save_grading($task_id, $organizer_id, $grades);

	}



}
