<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\BrodResponse;
use App\BrodRequest;

class BrodRequest extends Model
{
    public static function getBrodRequestByUser($uid)
	{
		if(count($brodRequestByUser) > 0)
		{
			foreach ($brodRequestByUser as $key => $value)
			{
				$brodRequestByUser[$key]->new_response_count = DB::table('brod_responses')->where('request_id',$value->id)->where('read_status',0)->count();
			}			
		}

		return $brodRequestByUser;
	}

	/* seller */
	public static function getAllBrodRequest()
	{
		$allBrodRequest = DB::table('brod_requests')
		->orderBy('id','desc')
		->get();

		print_r($allBrodRequest);
		die;

		return $allBrodRequest;
	}

	public static function getRequestDetailsByReqId($rId)
	{
		$allBrodRequest = DB::table('brod_requests')
		->where('id',$rId)
		->first();

		return $allBrodRequest;
	}	
}
