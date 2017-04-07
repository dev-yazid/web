<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;

use App\UserProfiles;

use App\Cities;
use App\BrodResponse;

class BrodResponse extends Model
{
   	public static function getBrodResponseByReqId($request)
    {  
        $resDetails = DB::table('brod_responses')
        ->where('request_id',$request->request_id)
        ->where('removed_by_user',0)
        ->select('id','seller_id','request_id','price','price_updated','read_status','removed_by_user')
        ->orderBy('id','desc')
        ->get(); 

        if(count($resDetails) > 0)
        {
           foreach ($resDetails as $key => $value)
			{   
		        $resDetails[$key]->response_type     = $value->read_status; /* new and old response 0 is new and 1 is old */ 
		        $resDetails[$key]->chat_noti_count   = Message::getChatNotification($request);
		        $resDetails[$key]->seller_details    = User::getSellerDetails($value->seller_id);
		        $resDetails[$key]->read_status       = User::markResViewedByCustomer($value->id);			       	
        	}
        }

        return $resDetails;
    }

    public static function removeResponse($res_id)
    {  
    	$resUpdated = 0;

        $resDetails = BrodResponse::find($res_id);

        if(count($resDetails) > 0)
        {
        	$resDetails->removed_by_user = 1;
        	$resDetails->read_status 	 = 1;
        	$resDetails->save();
        	$resUpdated = 1;
        }
        return $resUpdated;
    }

    public static function priceUpdateNotiRead($res_id)
    {
    	$resUpdated = 0;
    	
        $resDetails = BrodResponse::find($res_id);

        if(count($resDetails) > 0)
        {
        	$resDetails->price_updated = 1;
        	$resDetails->read_status   = 1;
        	$resDetails->save();
        	$resUpdated = 1;
        }

        return $resUpdated;
    }     
}
