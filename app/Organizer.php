<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Organizer extends Model {

    protected $table = 'organizer';

    protected $fillable = ['name', 'dob', 'email', 'cell_phone','college','id_number','language','activity','agreement'];

    public function conferences(){
    	return $this->belongsToMany('App\Conference','conference_organizer', 'organizer_id' , 'conference_id');
    }

    public function departments(){
    	return $this->belongsToMany('App\User','conference_organizer', 'organizer_id' , 'user_id');
    }

    public function tasks(){
    	return $this->hasMany('App\Task', 'organizer_id' );
    }


}
