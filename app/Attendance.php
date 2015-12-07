<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model {

    protected $table = 'attendance';

    public $timestamps = false;

    protected $fillable = ['id','organizer_id','task_id'];




}
