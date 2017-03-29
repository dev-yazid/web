<?php
namespace App\api;

use Illuminate\Database\Eloquent\Model;
use File;
class JobInvitation extends Model
{
	protected $table='job_invitations';

	public static function rejectInvByClient($request)
	{
		$rejStatus = 0;

		$jobInvitation = JobInvitation::find($request->invId);
		if($request->invType == 2)
		{
			$jobInvitation->status = 3;
			$jobInvitation->inv_status = "Reject";
		}
		else 
		{
			$jobInvitation->inv_status = "Reject";	
		}

        if($jobInvitation->save())
        {
        	$rejStatus = 1;
    	}

		return $rejStatus;
	}
}
