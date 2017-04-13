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
	public static function getAllBrodRequest($uid)
	{	
		$allBrodRequest = DB::table('brod_requests')
		->leftJoin('brands', 'brod_requests.brand_id', '=', 'brands.id')
		->leftJoin('products', 'brod_requests.brand_id', '=', 'products.id')
		->leftJoin('users', 'brod_requests.user_id', '=', 'users.id')
		->leftJoin('user_profiles', 'brod_requests.user_id', '=', 'user_profiles.user_id')		
		->select('brod_requests.id as request_id','brod_requests.is_details_updated','brod_requests.created_at','brod_requests.description','brod_requests.status','brands.brand','brands.image','users.id as customer_id','users.name','products.pname','user_profiles.shop_city')
		->orderBy('brod_requests.id','desc')
		->get();

		$bserUrlImg = "";
		if(count($allBrodRequest) > 0)
		{
			$bserUrlImg = asset('/public/asset/brand/');			
			foreach ($allBrodRequest as $key => $value) {
				$allBrodRequest[$key]->brand_img_path = $bserUrlImg;

				$brodRespDetails = DB::table('brod_responses')
		        ->where('request_id',$value->customer_id)
		        ->select('price','removed_by_user','is_prod_confirm_by_buyer','is_prod_confirm_by_seller')
		        ->where('seller_id',$uid)      
		        ->first();

		        if(count($brodRespDetails) > 0)
		        {
		        	$allBrodRequest[$key]->price = $brodRespDetails->price;
		        	$allBrodRequest[$key]->removed_by_user = $brodRespDetails->removed_by_user;
		        	$allBrodRequest[$key]->is_prod_confirm_by_buyer = $brodRespDetails->is_prod_confirm_by_buyer;
		        	$allBrodRequest[$key]->is_prod_confirm_by_seller = $brodRespDetails->is_prod_confirm_by_seller;
		    	}
		    	else
		    	{
		    		$allBrodRequest[$key]->price = 0;
		        	$allBrodRequest[$key]->removed_by_user = 0;
		        	$allBrodRequest[$key]->is_prod_confirm_by_buyer  = 0;
		        	$allBrodRequest[$key]->is_prod_confirm_by_seller = 0;
		    	}
			}
		}

		//print_r($allBrodRequest);

		return $allBrodRequest;
	}
}
