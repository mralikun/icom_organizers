<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model {

    protected $table = 'task';

    protected $fillable = ['title', 'description', 'date', 'type','confirmed','orgainzer_id'];

    public function conference(){
    	$this->hasOne('App\Conference', 'conference_id');
    }

}
