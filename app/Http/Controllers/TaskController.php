<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\grading;
use App\WorkingFields;
use App\Organizer;
use App\Email_token;
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

	public static  $email = "";
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

		$validator = Validator::make($inputs,[  'title' => "required" ,
												'description' => "required" ,
												'type'	=>	"required"
											]
		);
		if($validator->fails()){

			return $validator->messages();
		}else {
			$task = new Task;

			$task->title = Input::get('title');
			$task->description = Input::get('description');
			$task->from = Input::get('from');
			$task->to = Input::get('to');
			$task->type = Input::get('type');
			$task->confirmed = 0;
			$task->organizer_id =Input::get('organizer_id');
			$task->working_fields_id =Input::get('working_fields_id');
			if ($task->type === 'conferance') {
				$task->conference_id =Input::get('conference_id');
				$conferances = Conference::where('id', '=', $task->conference_id)->get()->first();
			}

			$task->save();
			$Email_token = new Email_token;
			$token_mail =csrf_token();
			$Email_token->token = $token_mail;
			$Email_token->task_id = $task->id;
			$Email_token->organizer_id=$task->organizer_id;
			$Email_token->save();

			$workingfields = WorkingFields::where('id','=',$task->working_fields_id)->get()->first();
			$organizer = Organizer::where('id','=',$task->organizer_id)->get()->first();

                        $organizer_data=array();

                            if($task->type === 'conferance'){
                                $organizer_data =[
                                        'title' => $task->title,
                                        'description' => $task->description,
                                        'task_from' => $task->from,
                                        'task_to' => $task->to,
                                        'teamleader' =>$workingfields->teamleader,
                                        'workingfield' => $workingfields->name,
                                        'conference_name'=>$conferances->name,
                                        'conference_from'=>$conferances->from,
                                        'conference_to'=>$conferances->to,
                                        'conference_venue'=>$conferances->venue,
										'token_mail'=>$token_mail
                                ];

                            }else{
                                $organizer_data =[
                                        'title' => $task->title,
                                        'description' => $task->description,
                                        'task_from' => $task->from,
                                        'task_to' => $task->to,
                                        'teamleader' =>$workingfields->teamleader,
                                        'workingfield' => $workingfields->name,
										'token_mail'=>$token_mail
                                ];
                            }

                            $teamleader_data = array(
                                'organizer_name'=>$organizer->name
                            );

                           self::$email = $organizer->email ;

                                Mail::send('sendemail', $organizer_data, function ($message) {

                                    $organizer_email = self::$email;

                                    $message->subject("Welcome to site name");

                                    $message->to($organizer_email);

                                });


                            self::$teamleader_email = $workingfields->teamleader_email ;

                            Mail::send('sendemail',$teamleader_data, function ($message){

                                $teamleader_email = self::$teamleader_email;

                                $message->subject("Welcome to site name");

                                $message->to($teamleader_email);

                            });
                        }
                }

	public function check_email($flag,$token)
	{
		$emailtoken = Email_token::where('token', '=', $token)->get()->first();


		if (isEmpty($emailtoken)) {
			abort(404);
		} else {
			if ($flag === 'yes') {
				$task_id = $emailtoken->task_id;

				$task = Task::find($task_id);
				$task->confirmed = 1;
				$task->save();
				$data = "you said yes";

			}else{
				$data = "you said no";
			}
			$emailtoken->delete();

		}
		return $data;

	}

/*

*/
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
		$date = Carbon::now();
		$user = Auth::user()->name;

		Storage::put('Organizer Request/'.$user.'_'.$date.'.txt',$contents);

	}

	public function get_organizer_request($file_name){

		$organizer_request = Storage::get('Organizer Request/'.$file_name);
		return json_decode($organizer_request);

	}


}
