<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;
use DB;

class User extends Authenticatable
{    
    public static function getProfileDetails($userId)
    {  
        $userDetails = DB::table('users')
        ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
        ->leftJoin('cities', 'user_profiles.customer_city', '=', 'cities.id')
        ->select('users.name','users.email','users.phone_number','users.password','user_profiles.customer_address','user_profiles.customer_zipcode','cities.name as city')
        ->where('users.id',$userId)
        ->first();

       return $userDetails;
    }

    public static function updatePassword($request)
    {  
        $is_password_updated = 0;

        $userDetails = DB::table('users')
        ->where('id',$request->$uid)
        ->where('phone_number',$request->$phone_number)
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
