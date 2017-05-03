<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\BrodResponse;
use App\BrodRequest;
use Twilio\Rest\Client;

class BrodRequest extends Model
{
    public static function getBrodRequestByUser($uid,$status,$per_page)
	{
		$brodRequestByUser = DB::table('brod_requests')
		->leftJoin('brands', 'brod_requests.brand_id', '=', 'brands.id')
		->leftJoin('products', 'brod_requests.prod_id', '=', 'products.id')
		->where('brod_requests.user_id',$uid)
		->where('brod_requests.status',$status) 
		->where('removed_by_user',0)
		->select('brod_requests.id','brod_requests.description','brod_requests.created_at','brod_requests.req_image','brod_requests.status','brands.brand','brod_requests.brand_id','brod_requests.prod_year','brod_requests.prod_id','products.pname')
		->orderBy('brod_requests.id','desc')
		->paginate($per_page);

		if($brodRequestByUser->count() > 0)
		{
			$response = [
	        	'events' => $brodRequestByUser->items(),
	        	'pagination' => [
	            	'total' => $brodRequestByUser->count(),
	            	'per_page' => $brodRequestByUser->currentPage(),
	            	'page' => $brodRequestByUser->currentPage() + 1,
	            	'hasMorePages' => $brodRequestByUser->hasMorePages()
	         	]
	       	];

			if(count($brodRequestByUser) > 0)
			{
				foreach ($brodRequestByUser as $key => $value)
				{
					$brodRequestByUser[$key]->new_response_count = DB::table('brod_responses')->where('request_id',$value->id)->where('read_status',0)->count();
				}		
			}
		} 
		else
		{
			$response = array();
		}

		return $response;
	}

	public static function getAllBrodRequest($uid,$per_page,$status)
	{	
		$allBrodRequest = DB::table('brod_requests')
		->leftJoin('brands', 'brod_requests.brand_id', '=', 'brands.id')
		->leftJoin('products', 'brod_requests.brand_id', '=', 'products.id')
		->leftJoin('users', 'brod_requests.user_id', '=', 'users.id')
		->leftJoin('user_profiles', 'brod_requests.user_id', '=', 'user_profiles.user_id')
		->leftJoin('cities', 'user_profiles.customer_city', '=', 'cities.id')		
		->select('brod_requests.id as req_id','cities.name as city_name','brod_requests.prod_year','brands.brand','brod_requests.req_image','brod_requests.is_details_updated','brod_requests.created_at','brod_requests.description','brod_requests.status','brands.id as brandid','brands.image','users.id as customer_id','users.name','products.pname','users.id as customer_id','users.name','products.id as productid','user_profiles.shop_city')
		->where('brod_requests.removed_by_user',0)
		->where('brod_requests.status',$status)
		->where('brod_requests.user_id','!=',$uid)	
		->orderBy('brod_requests.id','desc')
		->paginate($per_page);

		$bserUrlImg = "";
		if(count($allBrodRequest) > 0)
		{
			$bserUrlImg = asset('/public/asset/brodcastImg/thumb');			
			foreach ($allBrodRequest as $key => $value) {

				$allBrodRequest[$key]->brand_img_path = $bserUrlImg;

				$brodRespDetails = DB::table('brod_responses')
		        ->where('request_id',$value->req_id)
		        ->where('seller_id',$uid) 
		        ->select('price','removed_by_user','is_prod_confirm_by_buyer','is_prod_confirm_by_seller')
		        ->first();

		        if(count($brodRespDetails) > 0)
		        {
		        	$allBrodRequest[$key]->price = $brodRespDetails->price;
		        	$allBrodRequest[$key]->removed_by_user = $brodRespDetails->removed_by_user;
		        	$allBrodRequest[$key]->is_prod_confirm_by_buyer  = $brodRespDetails->is_prod_confirm_by_buyer;
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

		$response = [
        	'events' => $allBrodRequest->items(),
        	'pagination' => [
            	'total' => $allBrodRequest->count(),
            	'per_page' => $allBrodRequest->currentPage(),
            	'page' => $allBrodRequest->currentPage() + 1,
            	'hasMorePages' => $allBrodRequest->hasMorePages()
         	]
       	];

		return $response;
	}

	public static function removeRequest($req_id)
    {  
        $resUpdated = 0;

        $resDetails = BrodRequest::find($res_id);

        if(count($resDetails) > 0)
        {
            $resDetails->removed_by_user = 1;
            $resDetails->read_status     = 1;
            $resDetails->save();
            $resUpdated = 1;
        }
        return $resUpdated;
    }

    public static function sendProductConfirmation($mobile)
    {      
        $smsSend = 0;
        $sid    = 'AC4ab5b2e4a9da816dc45e5af158dc770d';
        $token  = 'c2bed0cfbdee0f4dad5db438219b995e';
        $client = new Client($sid, $token);
        
        if($client->messages->create('+91'.$mobile,array('from' => '+18588159100','body' => 'Your Product Response Confirmed By Buyer.'))){
   
            $smsSend = 1;
        }
        else
        {
            $smsSend = 0;
        }

        return $smsSend;        
    }
}
