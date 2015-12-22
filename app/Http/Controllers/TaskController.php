<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Notification;
use App\Task;
use App\grading;
use App\WorkingFields;
use App\Organizer;
use App\Email_token;
use App\Conference;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller {

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
		return View::make('task');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */

	/*
		assign task to organizer
	 */
	public function store()
	{
		 $inputs=Input::all();

		/*make validation for all inputs */

		$validator = Validator::make($inputs,[  'title' => "required" ,
												'description' => "required" ,
												'from'	=>	"required",
												'to'	=>	"required",
												'type'	=>	"required",
												'teamleader_email'	=>	"required"
											]
		);
		if($validator->fails()){

			return $validator->messages();
		}else {


			/* create new task */
			$task = Task::create($inputs);

			/*insert new token related to task */

			$token_mail = str_random(32);
			$organizer_id = Input::get('organizer_id');
			Email_token::save_token($token_mail,$organizer_id,$task->id);

			/*return data of conference to send them in email */

			$conference_id =Input::get('conference_id');
			$conferances = Conference::where('id', '=', $conference_id)->get()->first();

			/* return the name of workingfield related to this task*/

			$workingfield_name = WorkingFields::select('name')
												->where('id','=',Input::get('working_fields_id'))
												->get()->first();

			/* return the data of Organizer related to this task*/

			$organizer = Organizer::where('id','=',Input::get('organizer_id'))->get()->first();

			$organizer_data=array();

				if($task->type === 'conference'){
					$organizer_data =[
							'title' => Input::get('title'),
							'description' => Input::get('description'),
							'task_from' => Input::get('from'),
							'task_to' => Input::get('to'),
							'workingfield' => $workingfield_name,
							'conference_name'=>$conferances->name,
							'conference_from'=>$conferances->from,
							'conference_to'=>$conferances->to,
							'conference_venue'=>$conferances->venue,
							'organizer_name'=>$organizer->name,
							'token_mail'=>$token_mail
					];


				}else{
					$organizer_data =[
							'title' => Input::get('title'),
							'description' => Input::get('description'),
							'task_from' => Input::get('from'),
							'task_to' => Input::get('to'),
							'organizer_name'=>$organizer->name,
							'workingfield' => $workingfield_name,
							'token_mail'=>$token_mail
					];
				}

			$teamleader_data = array(
					'organizer_name'=>$organizer->name,
					'organizer_id'=>$organizer->id_number,
					'organizer_email'=>$organizer->email,
					'organizer_phone'=>$organizer->cell_phone
			);

			/*send email to organizer */

			$subject = "ICOM Organizer _ send confirm message to organizer";
			Task::sendemail('sendemail',$organizer_data,$organizer->email,$subject);

			/*send email to teamleader */

			$subject = "ICOM Organizer _ send email to teamleader ";
			$teamleader_email = Input::get('teamleader_email');
			Task::sendemail('teamleader_mail',$teamleader_data,$teamleader_email,$subject);

		}
	}

	public function check_email($flag,$token)
	{
		$emailtoken = Email_token::where('token', '=', $token)->get()->first();


		if (empty($emailtoken)) {

			abort(404);

		} else {

			$organizer_id = $emailtoken->organizer_id;
			$task_id = $emailtoken->task_id;

			/* return the data of organizer */

			$organizer = Organizer::where('id', '=', $organizer_id)->get()->first();

			$organizer_name = $organizer->name;
			$organizer_id_number =$organizer->id_number;
			$organizer_phone =$organizer->cell_phone;
			$organizer_email =$organizer->email;

			/* data pass to view in send email */

			$data =array('organizer_name' => $organizer_name,
					'flag' => $flag,
					'organizer_id' => $organizer_id_number,
					'organizer_phone' => $organizer_phone,
					'organizer_email'=>$organizer_email
			);

			/* return the teamleader email */

			$task = Task::find($task_id);
			$teamleader_email = $task->teamleader_email;

			if ($flag == 'yes') {
			/*change the value of confirmed attribute from 0 to 1 */

				Task::update_confirmed($emailtoken->task_id);

			/* send email to teamleader if organizer accept task */

				$subject = "ICOM Organizer _ confirmid message from organizer";
				Task::sendemail('teamleader_mail',$data,$teamleader_email,$subject);

				if($task->type == "conference"){
					$organizer = Organizer::find($data['organizer_id']);
					$organizer->conferences()->attach($task->conference_id);

				}


			/* generate the message concept */

				$message = Task::generate_message($data,$task_id,$teamleader_email);

			/* save the message */
				Notification::save_message($message,$organizer_id,$task_id);


			} else {

				/* send email to teamleader if organizer refuse task */

				$subject = "ICOM Organizer _ confirmid message from organizer";
				Task::sendemail('teamleader_mail',$data,$teamleader_email,$subject);

				/* generate the message concept */

				$message = Task::generate_message($data,$task_id,$teamleader_email);

				/* save the message */

				Notification::save_message($message,$organizer_id,$task_id);
			}
			$emailtoken->delete();
			return View::make('task_confirmation');

		}
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


	public function organizer_request(){

		$inputs = Input::all();
		$contents = json_encode($inputs);
		$date = date("Y_m_d g_i A" , time() + (2*60*60));
		$user = Auth::user()->name;

		Storage::put('Organizer Request/'.$user.'_'.$date.'.json',$contents);

	}
    
    public function get_all_organizers_requests(){

        $files = Storage::files('Organizer Request/');
        return $files;
    }

	public function get_organizer_request($file_name){

		$organizer_request = Storage::get('Organizer Request/'.$file_name);
		return $organizer_request;

	}

	public function update_task($id){

		$task = Task::find(1);
		$inputs=array(
				'title'=>"title",
				'description'=>"description",
				'from'=>"15/12/2015",
				'to'=>"17/15/2012",
				'type'=>"conference",
				'teamleader_email'=>"hend@yahoo.com",
				'organizer_id'=>6,
				'conference_id'=>15,
				'working_fields_id'=>3
		);
		/*check type if conference delete it and organizers working */
		if(($task->type=="conference") && ($inputs['type']=="conference")) {
			$organizer = Organizer::find($task->organizer_id);
			$organizer->conferences()->detach();
		}
		/*check if update organizer delete email token  */
		if($inputs['organizer_id']!==$task->organizer_id){
			$emailtoken = Email_token::where('organizer_id','=',$task->organizer_id)->where('task_id','=',$task->id)->get()->first();
			$emailtoken->delete();
		}

		/*update task */
		$task->update($inputs);

		/* save new email token */
		$token_mail = str_random(32);
		$organizer_id = $inputs['organizer_id'];
		Email_token::save_token($token_mail,$organizer_id,$task->id);

		/*return data of conference to send them in email */

		$conference_id =$inputs['conference_id'];
		 $conferances = Conference::where('id', '=', $conference_id)->get()->first();

		/* return the name of workingfield related to this task*/

		$workingfield_name = WorkingFields::select('name')
				->where('id','=',$inputs['working_fields_id'])
				->get()->first();

		/* return the data of Organizer related to this task*/

		$organizer = Organizer::where('id','=',$inputs['organizer_id'])->get()->first();

		$organizer_data=array();

		if($task->type === 'conference'){
			$organizer_data =[
					'title' => $inputs['title'],
					'description' => $inputs['description'],
					'task_from' => $inputs['from'],
					'task_to' => $inputs['to'],
					'workingfield' => $workingfield_name,
					'conference_name'=>$conferances->name,
					'conference_from'=>$conferances->from,
					'conference_to'=>$conferances->to,
					'conference_venue'=>$conferances->venue,
					'organizer_name'=>$organizer->name,
					'token_mail'=>$token_mail
			];


		}else{
			$organizer_data =[
					'title' => $inputs['title'],
					'description' => $inputs['description'],
					'task_from' => $inputs['from'],
					'task_to' => $inputs['to'],
					'organizer_name'=>$organizer->name,
					'workingfield' => $workingfield_name,
					'token_mail'=>$token_mail
			];
		}

		$teamleader_data = array(
				'organizer_name'=>$organizer->name,
				'organizer_id'=>$organizer->id_number,
				'organizer_email'=>$organizer->email,
				'organizer_phone'=>$organizer->cell_phone
		);
		/*send email to organizer */

		$subject = "ICOM Organizer _ send confirm message to organizer";
		Task::sendemail('sendemail',$organizer_data,"sabryhend170@gmail.com",$subject);

		/*send email to teamleader */

		$subject = "ICOM Organizer _ send email to teamleader ";
		$teamleader_email = Input::get('teamleader_email');
		Task::sendemail('teamleader_mail',$teamleader_data,$teamleader_email,$subject);


	}





	public function uploaded()
	{
		//return View::make('uploadimg');
		//$dir = "uploads";
//		if (is_dir($dir)) {
//			if ($dh = opendir($dir)) {
//				while (($file = readdir($dh)) !== false) {
//					echo $file . "<br>";
//				}
//				closedir($dh);
//			}
//		}
	// $files = scandir($dir);
		return View::make('pop');

	}


	public function multiple_upload()
	{
		$old_image = Input::get('oldimage');

		$new_image = Input::file('myfile');

		$fileOriginalName = $new_image->getClientOriginalName();

		$destinationPath = public_path() . "/uploads";

		unlink($destinationPath."/".$old_image);
		$new_image->move($destinationPath,$fileOriginalName);

	}

}
