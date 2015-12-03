<?php namespace App;

use Illuminate\Database\Eloquent\Model;


class Task extends Model {

    protected $table = 'task';

    protected $fillable = ['title', 'description', 'from','to', 'type','confirmed','orgainzer_id','conference_id'];

    public function conference(){
    	$this->hasOne('App\Conference', 'p');
    }
    /*return workingfield related to task */
    public function working_field()
    {
        return $this->belongsTo('App\WorkingFields');
    }


}
