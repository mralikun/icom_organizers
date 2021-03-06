<?php namespace App\Http\Controllers;

use App\Conference;
use App\grading;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Organizer;
use App\User;

class OrganizerController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$organizers = Organizer::all();

		return $organizers;
	}

	/**
	 * Validate Organizer's Data
	 *
	 * @return Void
	 */
	private function validateOrganizer($inputs){
		$validator = Validator::make(
				$inputs,
				[
						'name' 				=> 	"required",
						'email' 			=> 	"required|email|unique:organizer",
						'cell_phone' 		=> 	"required",
						'id_number' 		=> 	"required",
						'working_fields' 	=> 	"required"

				]
		);
		if($validator->fails()){

			return $validator->messages();

		}

		return 'true';

	}

	/**
	 * upload and Validate Organizer's agreement img
	 *
	 * @return Void
	 */
	private function base64_to_jpeg($base64_string, $output_file, $filename){

		$data = explode(',', $base64_string);

		$extension = explode('/',$data[0]);
		$extension = explode(';',$extension[1]);

		switch($extension[0]){
			case 'png':case 'jpg':case 'jpeg':case 'JPEG':break;
			default: return 'false';
		}

		$ifp = fopen($output_file.DIRECTORY_SEPARATOR.$filename.".".$extension[0], "wb");

		fwrite($ifp, base64_decode($data[1]));
		fclose($ifp);

		return $extension[0];
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$inputs = Input::except("agreement","working_field");

		$validator = $this->validateOrganizer($inputs);

		if($validator != 'true'){

			return $validator;

		}


		if (Input::has('agreement'))
		{

			$agreement = Input::get('agreement');

			$filename = str_random(32).date('Y-m-d');

			$destinationPath = public_path().DIRECTORY_SEPARATOR."agreements";

			$file = $this->base64_to_jpeg($agreement, $destinationPath, $filename);

			if($file == 'false'){
				return array('agreement' => array('the agreement image must be jpeg ,JPEG ,jpg or png') );
			}

		}


		if(isset($filename)){
			$inputs['agreement'] = $filename.'.'.$file;
		}

		$inputs['gender'] = (int)$inputs['gender'];

		$organizer = Organizer::create($inputs);

		$working_fields = Input::get('working_fields');

		$organizer->workingfields()->attach($working_fields);

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$organizer = Organizer::findByEmailOrFail($id);
		$organizer->workingfields;

		return $organizer;

	}


	/**
	 *
	 * @param  int  $id
	 * @return Array
	 */
	public function update($id)
	{
		$organizer = Organizer::find($id);

		$inputEmail = Input::get('email');

		if($inputEmail == $organizer->email){
			$inputs = Input::except("agreement","departments");
			$inputs['email'] = 'faker123@faker.com';
		}else{
			$inputs = Input::except("agreement","departments");
		}

		//  validating Inputs
		$validator = $this->validateOrganizer($inputs);

		if($validator != 'true'){

			return $validator;

		}

		$inputs = Input::except("agreement","departments");

		// upload and validating img if Exists in Inputs
		if (Input::has('agreement'))
		{

			$agreement = Input::get('agreement');
			$filename = str_random(32).date('Y-m-d');

			$destinationPath = public_path().DIRECTORY_SEPARATOR."agreements";

			$file = $this->base64_to_jpeg($agreement, $destinationPath, $filename);

			// Delete The Old Agreement Img If Exists Or Uploaded Before
			if(!is_null($organizer->agreement)){
				\File::Delete(public_path().$destinationPath.DIRECTORY_SEPARATOR.$organizer->agreement);
			}

			if($file == 'false'){
				return array('agreement' => array('the agreement image must be jpeg ,JPEG ,jpg or png') );
			}

		}

		// start updating data

		if(isset($filename)){
			$inputs['agreement'] = $filename.'.'.$file;
		}

		$inputs['gender'] = (int)$inputs['gender'];

		$organizer->update($inputs);

		$working_fields = Input::get('working_fields');

		$organizer->workingfields()->sync($working_fields);

	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$organizer = Organizer::findByEmailOrFail($id);

		$destinationPath = public_path().DIRECTORY_SEPARATOR."agreements";

		// Delete The Old Agreement Img If Exists Or Uploaded Before
		if(!is_null($organizer->agreement)){
			\File::Delete(public_path().$destinationPath.DIRECTORY_SEPARATOR.$organizer->agreement);
		}

		$organizer->delete();
	}


	/*return all organizers in specified conference */

	public function organizers($conferance_id){
		$organizers = Conference::find($conferance_id)->organizers;
		return $organizers;
	}


}
