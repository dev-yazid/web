<?php

namespace App\api;

use Illuminate\Database\Eloquent\Model;
use File;
class JobMessage extends Model
{
	
	public static function sendJobMessage($sdrId, $recId, $projId, $msgDes, $msgDoc, $msgType, $radStatus)
	{
		$msgStatus = 0;
		
		$JobMessages = new JobMessage; 
        $JobMessages->sender_id             = $sdrId;
        $JobMessages->reeciver_id           = $recId;
        $JobMessages->project_id            = $projId;
        $JobMessages->messsge_description   = $msgDes;
        $JobMessages->messsge_documents     = $msgDoc;
        $JobMessages->messsge_type          = $msgType; 
        $JobMessages->read_status           = $radStatus;                                   
        $JobMessages->save();

   		return $msgStatus;		
	}
}
