<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class grading extends Model {

    protected $table = 'grading';

    protected $fillable = ['id','availability_upon_request','dress_code','commitment_to_rules',
                            'performance','hospitality','attendance','appearance','multi_tasking_abilities',
                                'task_id','organizer_id'];



}
