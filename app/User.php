<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;
use DB;
use App\BrodResponse;
use Twilio\Rest\Client;

 
class User extends Authenticatable
{    
    public static function getProfileDetails($userId)
    {  
        $userDetails = DB::table('users')
        ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
        //->leftJoin('cities', 'user_profiles.customer_city', '=', 'cities.id')
        ->select('users.name','users.phone_number','users.password','user_profiles.customer_address','user_profiles.customer_zipcode','user_profiles.customer_email','user_profiles.customer_city')
        ->where('users.id',$userId)
        ->first();

       return $userDetails;
    }    

    public static function updatePassword($phoneNumber, $password, $uid)
    {  
        $is_password_updated = 0;

        $userDetails = User::find($uid)
        ->where('phone_number',$phoneNumber)
        ->first();     

        if(count($userDetails) > 0)
        {
           $userDetails->password = trim(bcrypt($password));
           $userDetails->save();

           $is_password_updated = 1; 
        }

       return $is_password_updated;
    }

    public static function markResViewedByCustomer($res_id)
    {  
        $resUpdated = 0;

        $resDetails = BrodResponse::find($res_id);

        if(count($resDetails) > 0)
        {
            $resDetails->read_status = 1;
            $resDetails->save();
            $resUpdated = 1;
        }

        return $resUpdated;
    }

    /* Seller Part */
    public static function getSellerDetails($userId)
    {  
        //echo "fsdfsd";
        $userDetails = DB::table('users')
        ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
        ->leftJoin('cities', 'user_profiles.shop_city', '=', 'cities.id')
        ->select('cities.name as shop_city_name','users.id','users.name','users.email','user_profiles.customer_address','user_profiles.seller_name','user_profiles.shop_name','user_profiles.shop_mobile','user_profiles.shop_address','user_profiles.shop_zipcode','user_profiles.shop_location_map','user_profiles.shop_start_time','user_profiles.shop_close_time','user_profiles.shop_document','user_profiles.shop_city')
        ->where('users.id',$userId)
        ->first();

        if(count($userDetails) > 0)
        {
            $mapUrl ='https://www.google.com/maps?q=';
            $userDetails->map_location = $mapUrl.$userDetails->shop_location_map;           
        }
        
       return $userDetails;
    }

    public static function updatePasswordSeller($email, $password, $uid)
    {  
        $is_password_updated = 0;

        $userDetails = User::find($uid)
        ->where('email',$email)
        ->first();

        if(count($userDetails) > 0)
        {
           $userDetails->password = trim(bcrypt($password));
           $userDetails->save();

           $is_password_updated = 1; 
        }

       return $is_password_updated;
    }

    /* sms Gateway */
    public static function sendSms($mobile, $vcode)
    {      
        $smsSend = 0;
        $sid    = 'AC4ab5b2e4a9da816dc45e5af158dc770d';
        $token  = 'c2bed0cfbdee0f4dad5db438219b995e';
        $client = new Client($sid, $token);
        
        if($client->messages->create('+91'.$mobile,array('from' => '+18588159100','body' => 'Your Feeh Account Verification Code is : '.$vcode))){
   
            $smsSend = 1;
        }
        else
        {
            $smsSend = 0;
        }

        return $smsSend;        
    }

    public static function sendSmsAdmin($mobile)
    {      
        $smsSend = 0;
        $sid    = 'AC4ab5b2e4a9da816dc45e5af158dc770d';
        $token  = 'c2bed0cfbdee0f4dad5db438219b995e';
        $client = new Client($sid, $token);
        
        if($client->messages->create('+91'.$mobile,array('from' => '+18588159100','body' => 'Your Seller Registration confirmed By Web Admin.'))){
   
            $smsSend = 1;
        }
        else
        {
            $smsSend = 0;
        }

        return $smsSend;        
    }

    /* in brodRequest */
}
