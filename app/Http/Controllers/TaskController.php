<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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

	public static  $organizer_email = "";
	public static  $teamleader_email = "";
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

			$Email_token = new Email_token;
			$token_mail = str_random(32);
			$Email_token->token = $token_mail;
			$Email_token->task_id = $task->id;
			$Email_token->organizer_id=Input::get('organizer_id');
			$Email_token->save();

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

			self::$organizer_email = $organizer->email ;

			Mail::send('sendemail', $organizer_data, function ($message) {

				$organizer_email = self::$organizer_email;


				$message->subject("ICOM Organizer _ send confirm message to organizer");

                $message->from('info@tooonme.com');

				$message->to($organizer_email);

			});


			self::$teamleader_email = Input::get('teamleader_email');

			Mail::send('teamleader_mail',$teamleader_data, function ($message){

				$teamleader_email = self::$teamleader_email;

				$message->subject("ICOM Organizer _ send email to teamleader ");

				$message->from('info@tooonme.com');

				$message->to($teamleader_email);

			});
		}
	}





	public function check_email($flag,$token)
	{
		$emailtoken = Email_token::where('token', '=', $token)->get()->first();

		if (empty($emailtoken)) {

			abort(404);

		} else {
			
			$data = array(
					'flag'=>$flag
			);

			if ($flag == 'yes') {

				/*change the value of confirmed attribute from 0 to 1 */

				$task_id = $emailtoken->task_id;
				$task = Task::find($task_id);
				$task->confirmed = 1;

				$task->save();

				/* return the teamleader email */

				$organizer_id = $emailtoken->organizer_id;

				$teamleader_email = Task::select('teamleader_email')
						->where('organizer_id', '=', $organizer_id)
						->get()
						->first();

				self::$teamleader_email = $teamleader_email;

				Mail::send('teamleader_mail', $data, function ($message) {

					$teamleader_email = self::$teamleader_email;

					$message->subject("Welcome to site name");

					$message->from('info@tooonme.com');

					$message->to($teamleader_email);

				});

			} else {

				Mail::send('teamleader_mail', $data, function ($message) {

					$teamleader_email = self::$teamleader_email;

					$message->subject("Welcome to site name");

					$message->from('info@tooonme.com');

					$message->to($teamleader_email);

				});

			}
			$emailtoken->delete();

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
	 	select all conferances between two givien date.
	 */


	public function organizer_request(){

		$inputs = Input::all();
		$contents = json_encode($inputs);
		$date = date("Y_m_d g_i A" , time() + (2*60*60));
		$user = Auth::user()->name;

		Storage::put('Organizer Request\\'.$user.'_'.$date.'.json',$contents);

	}
    
    public function get_all_organizers_requests(){
        $files = Storage::files('Organizer Request/');
        return $files;
    }

	public function get_organizer_request($file_name){

		$organizer_request = Storage::get('Organizer Request/'.$file_name);
		return $organizer_request;

	}


}
