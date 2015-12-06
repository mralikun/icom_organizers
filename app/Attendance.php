<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {

    protected $table = 'organizer';

    protected $fillable = ['id','check_in','check_out','organizer_id','task_id'];



}
