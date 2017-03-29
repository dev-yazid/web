<?php
namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Hash;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    
    protected $fillable = [
    'firstname', 'lastname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    'password', 'remember_token',
    ];

    // ************* Check Site User Login *************
    public function authenticateSiteUser($mobile, $password)
    {
        $user = User::where('email', $mobile)->first();

        if (!isset($user->password) || !Hash::check($password, $user->password)) {
            return false;
        }
        return $user;
    }

    public static $CareTakerRules = array(
        'first_name' =>'required',
        'last_name' =>'required',
        'email' =>'required|email', 
        'password' =>'required|same:confirm_password',
        'confirm_password' =>'required|same:password'
    );
}
