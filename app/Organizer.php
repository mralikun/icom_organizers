<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model {

    protected $table = 'organizer';

    protected $fillable = ['name','gender' ,'address' ,'dob', 'email', 'cell_phone','college','id_number','language','activity','agreement'];

    public function conferences(){
    	return $this->belongsToMany('App\Conference','conference_organizer', 'organizer_id' , 'conference_id');
    }

//    public function departments(){
//        return $this->belongsToMany('App\User','department_organizer', 'organizer_id' , 'user_id');
//    }
//
//    public function departmentsIds(){
//        return $this->belongsToMany('App\User','department_organizer', 'organizer_id' , 'user_id')->select('users.id');
//    }

    public function working_fields(){

        $workingFieldsString = $this->working_fields;

        $workingFieldsArray = explode(',', $workingFieldsString);

        return $workingFieldsArray;

    }

    public function tasks(){
    	return $this->hasMany('App\Task', 'organizer_id' );
    }

    public function grades(){
        return $this->belongsToMany('App\Conference','conference_grade_organizer', 'organizer_id', 'conference_id' )->withPivot('grade','criteria');
    }


    public function setGenderAttribute($value){
        $this->attributes['gender'] = (int)$value;
    }

    public function setActivityAttribute($value){
        $this->attributes['activity'] = (int)$value;
    }

    public static function findByEmailOrFail($email,$columns = array('*')){

        if ( ! is_null($organizer = static::whereEmail($email)->first($columns))) {
            return $organizer;
        }

        abort(404);
    }
    public function workingfields(){
        return $this->belongsToMany('App\WorkingFields','organizer_workingfields', 'organizer_id', 'workingfields_id' );
    }

    /*
     * return average for organizer in all tasks§
     * */

    public static function organizer_grade($organizer_id){

        $tasks = Task::where('organizer_id','=',$organizer_id)->get();

        $number_of_task = count($tasks);

        $total_of_tasks = 0;

        foreach($tasks as $task){

            $task_id = $task->id;
            $average_for_task = grading::grading_average($task_id,$organizer_id);

            $total_of_tasks += $average_for_task;
        }
        return $average_for_tasks = $total_of_tasks/$number_of_task;

    }

}
