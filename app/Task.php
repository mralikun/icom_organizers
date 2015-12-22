<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\grading;


class Task extends Model {

    public static  $email = "";
    public static  $subject = "";


    protected $table = 'task';

    protected $fillable = ['title', 'description', 'from','to', 'type','teamleader_email','confirmed','organizer_id'
                            ,'conference_id','working_fields_id'];

    public function conference(){
    	$this->hasOne('App\Conference', 'p');
    }

    /*
     * return workingfield related to task
     * */

    public function working_field()
    {
        return $this->belongsTo('App\WorkingFields');
    }

    /*
     * return the average of grade for organizer in special task
     * */

    public static function grading_average($task_id,$organizer_id){
        $grads = grading::where('task_id','=',$task_id)
            ->where('organizer_id','=',$organizer_id)
            ->get();
        $number_of_criteria = count($grads);
        if($number_of_criteria == 0){
            return " not set ";
        }else{
            $total =  $grads->sum('grade');

            return $average = $total/($number_of_criteria * 5);

        }

    }

    /*
     * return all tasks have been confirmed from now
     */

    public static function confirmed_tasks(){

         $date = Carbon::today()->format('Y-m-d');
         $tasks =Task::where('confirmed','=','1')
                        ->where('from','<=',$date)
                        ->where('to','>=',$date)
                        ->get();

        return $tasks;

    }
    /*
     * send email to someone
     */
    public static function sendemail($view,$data,$email_to,$subject_email){

        self::$email = $email_to;
        self::$subject = $subject_email;

        Mail::send($view,$data,function ($message) {

            $email = self::$email;
            $subject_email = self::$subject;


            $message->subject($subject_email);

            $message->from('info@tooonme.com');

            $message->to($email);

        });
    }

    /* update confirmed column if accepted task */

    public static function update_confirmed($task_id){
        $task = Task::find($task_id);
        $task->confirmed = 1;
        $task->save();
    }

    /*generate message and send it to admin */

    public static function generate_message($data,$task_id,$teamleader){
        switch($data['flag']){

            case "yes":
               return $msg = "the". $data['organizer_name']." who choice to the".$task_id." accepted ,the task belongs to". $teamleader;
                break;

            case "no":
                return $msg = "the". $data['organizer_name']." who choice to the".$task_id." refused ,the task belongs to". $teamleader;
                break;

        }


    }



}
