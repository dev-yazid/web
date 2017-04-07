<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class BrodRequest extends Model
{
    public static function getAllBrodRequest()
	{
		$allBrodRequest =  DB::table('brod_requests')
		//->select('id','description','created_at','req_image','status',)
		//->where('user_id',$uid)
		->get();

		return $allBrodRequest;
	}

	public static function getBrodRequestByUser($uid)
	{
		if(count($brodRequestByUser) > 0)
		{
			foreach ($brodRequestByUser as $key => $value)
			{
				$brodRequestByUser[$key]->new_response_count = DB::table('brod_responses')->where('request_id',$value->id)->where('read_status',0)->count();
			}			
		}

		print_r($brodRequestByUser);

		return $brodRequestByUser;
	}
}
