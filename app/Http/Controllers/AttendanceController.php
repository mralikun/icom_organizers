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

class AttendanceController extends Controller {

    /* make checkin for organizer in specifed task */

    public function check_in(){

        $organizer_id = Input::get('organizer_id');
        $date_time = date("Y-m-d H:i:s");
        $date = date("Y-m-d");
        $validate = Attendance::validate_attendance($organizer_id);
        if($validate == "true"){

           $task = Task::where('organizer_id','=',$organizer_id)
                ->where('from','<=',$date)
                ->where('to','>=',$date)
                ->get()->first();

            $attendance = new Attendance;
            $attendance->task_id =$task->id ;
            $attendance->check_in =$date_time ;
            $attendance->organizer_id =$organizer_id ;
            $attendance->save();

        }

    }

    /* make ckeckout for organizer in specifed task  */

    public function check_out(){

        $organizer_id = Input::get('organizer_id');

        $date = date("Y-m-d");
        $date_time = date("Y-m-d H:i:s");

        $validate = Attendance::validate_attendance($organizer_id);
        if($validate == "check_out"){
            $attendances = Attendance::where('organizer_id','=',$organizer_id)
                ->get()
                ->first();

            $checkin = $attendances->check_in;

            if(!empty($checkin)){
                $attendance_id = $attendances->id;
                $attendance = Attendance::find($attendance_id);
                $attendance->check_out = $date_time;
                $attendance->save();
            }
        }


    }

    /* check the status of organizer make checkin or not & make checkout or not */

    public function status(){
        $organizer_id = Input::get('organizer_id');
        $date_time = date("Y-m-d H:i:s");
        $date = date("Y-m-d");
        $task = Task::where('organizer_id','=',$organizer_id)
            ->where('from','<=',$date)
            ->where('to','>=',$date)
            ->get()->first();
        $attendance = Attendance::where('organizer_id','=',$organizer_id)->where('task_id','=',$task->id)->get()->first();
        $value =array();
        if(empty($attendance)) {
            $value = ['checkin' => 'false',
                'checkout'=>'false'];
        }elseif(!empty($attendance->check_in) && empty($attendance->check_out)){

            $value = ['checkin' => 'true',
                'checkout'=>'false'];
        }elseif(!empty($attendance->check_in) && !empty($attendance->check_out)){
            $value = ['checkin' => 'true',
                'checkout'=>'true'];
        }
        else{
            $value = [];
        }

        return $value;
    }

}