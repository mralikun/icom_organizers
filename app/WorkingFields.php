<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkingFields extends Model {

    protected $table = 'working_fields';
    public $timestamps = false;
    public function tasks(){
        return $this->hasMany('App\Task');
    }
    public function organizers(){
        return $this->belongsToMany('App\Organizer','organizer_workingfields','workingfields_id', 'organizer_id' );
    }


}
