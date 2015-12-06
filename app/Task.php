<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\grading;


class Task extends Model {

    protected $table = 'task';

    protected $fillable = ['title', 'description', 'from','to', 'type','confirmed','orgainzer_id','conference_id'];

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
        $total =  $grads->sum('grade');

        return $average = $total/($number_of_criteria * 5);

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






}
