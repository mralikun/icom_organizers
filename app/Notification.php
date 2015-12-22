<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    protected $table = 'notifications';
    public $timestamps = false;

    protected $fillable = ['id', 'seen','message', 'task_id', 'organizer_id'];

    /*
     * save messages
     */

    public static function save_message($message,$organizer_id,$task_id)
    {
        $notification = new Notification;
        $notification->message = $message;
        $notification->seen = 0;
        $notification->organizer_id=$organizer_id;
        $notification->task_id = $task_id;
        $notification->save();
    }

    /*
     * update unseen column when admin show message
     */
    public static function update_unseen_field($id){
        $message = Notification::find($id);
        $message->seen =1;
        $message->save();

    }

}
