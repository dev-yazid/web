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
}
