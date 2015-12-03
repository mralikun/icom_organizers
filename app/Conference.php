<?php namespace App;

use Illuminate\Database\Eloquent\Model;



class Conference extends Model {

    protected $table = 'conference';

    protected $fillable = ['id', 'name', 'from', 'to', 'venue'];
    protected $hidden=['created_at','updated_at'];

    public function organizers(){
    	return $this->belongsToMany('App\Organizer','conference_organizer', 'conference_id', 'organizer_id');
    }
    public static function last_id(){
    	$last_id = Conference::all()->last();
		return empty($last_id) ? "0" : $last_id->id; 
    }

    public static function select_conferance_between_two_dates($from,$to){

        $conferances =Conference::where('from', '>=', $from)
            ->where('to', '<=', $to)
            ->get();
        return $conferances;
    }


}
