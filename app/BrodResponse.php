<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;
use App\UserProfiles;
use App\Cities;
use App\BrodResponse;
use App\BrodRequest;
use App\Transaction;

class BrodResponse extends Model
{
   	public static function getBrodResponseByReqId($requestId , $per_page)
    {  
        //echo $requestId;
        //die('Amit');
        $resDetails = DB::table('brod_responses')
        ->where('request_id',$requestId)
        ->where('removed_by_user',0)
        ->select('id','seller_id','request_id','price','price_updated','read_status','removed_by_user')
        ->orderBy('id','desc')
        ->where('removed_by_user',0)
        ->paginate($per_page);

        //print_r( $resDetails); 

        if(count($resDetails) > 0)
        {
           foreach ($resDetails as $key => $value)
			{   

                $resDetails[$key]->seller_details    = User::getSellerDetails($value->seller_id);
                $resDetails[$key]->response_type     = $value->read_status; 
		        $resDetails[$key]->chat_noti_count   = Message::getChatNotification($requestId);
		        $resDetails[$key]->seller_details    = User::getSellerDetails($value->seller_id);
		        $resDetails[$key]->read_status       = User::markResViewedByCustomer($value->id);		       	
        	}

            $response = [
            'events' => $resDetails->items(),
            'pagination' => [
                'total' => $resDetails->count(),
                'per_page' => $resDetails->currentPage(),
                'page' => $resDetails->currentPage() + 1,
                'hasMorePages' => $resDetails->hasMorePages()
            ]
            ];
        }
        else
        {
            $response = array();
        }

        return $response;
        
    }

    public static function removeResponse($res_id,$uid)
    {  
    	$resUpdated = 0;

        $reqDetails = BrodRequest::where('id',$res_id)->where('user_id',$uid)->first();

        if(count($reqDetails) > 0)
        {
        	$reqDetails->removed_by_user = 1;
        	$reqDetails->save();
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

    public static function productConfirmedByBuyer($res_id)
    {
    	$resUpdated = 0;
    	
        $prodConfirmation = BrodResponse::find($res_id);

        if(count($prodConfirmation) > 0)
        {	
        	if($prodConfirmation->is_prod_confirm_by_buyer == 0)
            {
                $prodConfirmation->is_prod_confirm_by_buyer = 1;
            	$prodConfirmation->price_updated = 0;
            	$prodConfirmation->read_status   = 1;
            	
                if($prodConfirmation->save())
                {
                    $reqDetails = BrodRequest::find($prodConfirmation->request_id);
                    $reqDetails->status = 3; 
                    $reqDetails->save();

                    $transaction = new Transaction;
                    $transaction->cust_id               = $prodConfirmation->customer_id;
                    $transaction->seller_id             = $prodConfirmation->seller_id;
                    $transaction->request_id            = $prodConfirmation->request_id;
                    $transaction->cust_confirmation     = 1;
                    $transaction->seller_confirmation   = 0;
                    $transaction->save();

                    $resUpdated = 1;
                }
            }
            else
            {
                $resUpdated = 2;
            }
        }

        return $resUpdated;
    }

    /* seller Section */
    public static function productConfirmedBySeller($res_id)
    {
    	$resUpdated = 0;
    	
        $prodConfirmation = BrodResponse::find($res_id);

        if(count($prodConfirmation) > 0)
        {	
        	if($prodConfirmation->is_prod_confirm_by_buyer == 1)
            {
                if($prodConfirmation->is_prod_confirm_by_seller == 0)
                {
                    $prodConfirmation->is_prod_confirm_by_seller = 1;

                	if($prodConfirmation->save())
                    {
                        $reqDetails = BrodRequest::find($prodConfirmation->request_id);
                        $reqDetails->status = 3; 
                        $reqDetails->save();

                        $transaction = Transaction::where($prodConfirmation->request_id);
                        $transaction->seller_confirmation = 1;
                        $transaction->save();

                    	$resUpdated = 1;
                    }
                }
                else
                {
                    $resUpdated = 3;
                }
            }
            else
            {
                $resUpdated = 2;
            }
        }

        return $resUpdated;
    }    
}
