<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Email_token extends Model {

    protected $table = 'email_token';

    /* save new email token */

    public static function save_token($token,$organizer_id,$task_id){

        $Email_token = new Email_token;
        $Email_token->token = $token;
        $Email_token->organizer_id=$organizer_id;
        $Email_token->task_id = $task_id;
        $Email_token->save();
    }

}
