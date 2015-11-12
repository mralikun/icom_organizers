<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model {

    protected $table = 'conference';

    protected $fillable = ['id', 'name', 'from', 'to', 'venue'];

    public function organizers(){
    	return $this->belongsToMany('App\Organizer','conference_organizer', 'conference_id', 'organizer_id');
    }
    public static function last_id(){
    	$last_id = Conference::all()->last();
		return empty($last_id) ? "0" : $last_id->id; 
    }


}
