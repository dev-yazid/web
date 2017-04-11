<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;
use DB;
use App\BrodResponse;

class User extends Authenticatable
{    
    public static function getProfileDetails($userId)
    {  
        $userDetails = DB::table('users')
        ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
        ->leftJoin('cities', 'user_profiles.customer_city', '=', 'cities.id')
        ->select('users.name','users.phone_number','users.password','user_profiles.customer_address','user_profiles.customer_zipcode','user_profiles.customer_email','cities.name as city')
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
        $userDetails = DB::table('users')
        ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
        ->leftJoin('cities', 'user_profiles.shop_city', '=', 'cities.id')
        ->select('users.id','users.name','users.email','user_profiles.customer_address','user_profiles.shop_name','user_profiles.shop_mobile','user_profiles.shop_address','user_profiles.shop_zipcode','user_profiles.shop_location_map','user_profiles.shop_start_time','user_profiles.shop_close_time','user_profiles.shop_location_map','cities.name as shop_city')
        ->where('users.id',$userId)
        ->first();

        //print_r($userDetails);

       return $userDetails;
    }

    public static function updatePasswordSeller($email, $password, $uid)
    {  
        $is_password_updated = 0;
        //echo $request->$uid;

        $userDetails = User::find($uid)
        ->where('email',$email)
        ->first();

        if(count($userDetails) > 0)
        {
           $userDetails->password = trim(bcrypt($request->$password));
           $userDetails->save();

           $is_password_updated = 1; 
        }

       return $is_password_updated;
    }
}
