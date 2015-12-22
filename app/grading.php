<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class grading extends Model
{

    protected $table = 'grading';

    protected $fillable = ['id', 'grade', 'criteria', 'task_id', 'organizer_id'];

    /*
     * check if grade less than 5 or not
     */

    public static function validate_grade($array)
    {
        foreach ($array as $value) {
            if ($value->grade <= 5) {
                return "true";
            } else {
                return "false";
            }
        }
    }

    /*
     * saving grade for organizer in special task
     */

    public static function save_grading($task_id, $organizer_id, $array)
    {
        $validate = grading::validate_grade($array);
        if ($validate == "true"){
            foreach ($array as $value) {
                $grade = new grading;
                $grade->task_id = $task_id;
                $grade->organizer_id = $organizer_id;
                $grade->criteria = $value->criteria;
                $grade->grade = $value->grade;
                $grade->save();
            }
        }else{
            return "error";
        }
    }


}
