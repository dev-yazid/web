<?php

namespace App\api;

use Illuminate\Database\Eloquent\Model;
use File;
class JobDetail extends Model
{	
	public static function projCloseNotification($request)
	{
		$status = 0;

		$jobDetail = JobDetail::find($request->projId);
		if(count($jobDetail) > 0)
		{
			if($request->usertype === "Client")
			{
				if($jobDetail->proj_close_noti_client == 0 && $jobDetail->hired_user_id != 0)
				{
					$jobDetail->proj_close_noti_client = 1;
					$jobDetail->save();
					$status = 1;
				}
				else 
				{
					$status = 0;
				}
			}
			/*else if($request->usertype === "Freelancer")
			{
				if($jobDetail->proj_close_noti_freelancer == 0 && $jobDetail->hired_user_id != 0)
				{
					$jobDetail->proj_close_noti_freelancer= 1;
					$jobDetail->save();
					$status = 1;
				}
				else 
				{
					$status = 0;
				}
			}*/
			else
			{
				$status = 0;
			}			
		}
		else 
		{
			$status = 0;
		}

		return $status;
	}


	public static function acceptCloseNotification($request)
	{
		$status = 0;
		$jobDetail = JobDetail::find($request->projId);
		if(count($jobDetail) > 0)
		{
			if($request->usertype === "Client")
			{
				if($jobDetail->is_payment_relased == 1 && $jobDetail->hired_user_id != 0)
				{
					$jobDetail->proj_close_noti_client = 2;
					$jobDetail->save();
					$status = 1;
				}
				else 
				{
					$status = 0;
				}
			}
			else if($request->usertype === "Freelancer")
			{
				if($jobDetail->is_payment_relased == 1 && $jobDetail->hired_user_id != 0)
				{
					$jobDetail->proj_close_noti_freelancer= 2;
					$jobDetail->save();
					$status = 1;
				}
				else 
				{
					$status = 0;
				}
			}
			else
			{
				$status = 0;
			}			
		}
		else 
		{
			$status = 0;
		}

		return $status;
	}	
}
