<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class ActivityLog extends Model
{
    
    public static function createlog($uId,$module,$msg,$userType) {

    	DB::table('activity_logs')->insert([
    		[
    			'user_id' 	=> $uId,
    			'module'  	=> $module,
    			'message' 	=> $msg,
    			'ip' 	  	=> $_SERVER['REMOTE_ADDR'],    		
    		],    		
		]);     	
    }
}
