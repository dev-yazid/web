<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Hash;
use DB;

class Message extends Model
{    
    public static function getChatNotification($request)
    {  
        $chatNoti = DB::table('messages')->count();

       return $chatNoti;
    }
}
