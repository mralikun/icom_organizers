<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


class Attendance extends Model {

    protected $table = 'attendance';

    public $timestamps = false;

    protected $fillable = ['id','organizer_id','task_id'];


    /*
    * this method is checked if user make checkin and checkout or not
    */

    public static function validate_attendance($organizer_id){
        $ttendance =Attendance::where('organizer_id','=',$organizer_id)->get()->first();
        $date_time = date("Y-m-d H:i:s");
        if(empty($ttendance)){
            return "true";
        }
        elseif(is_null($ttendance->check_out)){
            return "check_out";

        }else{
           return "error";

        }


    }





}
