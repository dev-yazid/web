<?php
namespace App\Http\Controllers\Api;
use App\Libraries\Miscellaneous;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
use App\Http\Controllers\Controller;


use DB;
use Illuminate\Support\Facades\Input;
use Validator;
use Hash;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\UserProfiles;
use App\User;
use App\Cities;
use Auth;

class UserController extends Controller
{
    private $req;
    private $user;
    private $jwtAuth;
    function __construct(Request $request, User $user, ResponseFactory $responseFactory, JWTAuth $jwtAuth)
    {
        $this->user = $user;
        $this->jwtAuth = $jwtAuth;
        $this->req = $request;
        $this->res = $responseFactory;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getRegisterMobile(Request $request) {

        if($request->phone_number)
        {
            $validator = Validator::make($request->all(), [                
                'phone_number'  => 'required|min:10|numeric|unique:users',               
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                $digits = 4;
                $mobile_verify_code = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

                /* need to integrate Sms Gateway Here */

                $regNewMobile = new User;
                $regNewMobile->phone_number         = $request->phone_number;
                $regNewMobile->mobile_verify_code   = $mobile_verify_code;
                $regNewMobile->mobile_verified      = "No";
                $regNewMobile->save();

                $user = User::where('phone_number', '=', $request->phone_number)->first();

                $regNewProfile = new UserProfiles;
                $regNewProfile->user_id = $user->id;
                $regNewProfile->save();

                $this->resultapi('1','4 Digit Mobile Verification Code Send.', true);
            }
        }
        else
        {
            $this->resultapi('0','Mobile Number Not Found.', 0);
        }
    }

    public function getSendCodeAgain(Request $request) {

        if($request->phone_number)
        {
            $validator = Validator::make($request->all(), [                
                'phone_number'  => 'required|min:10|numeric',               
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                $digits = 4;
                $mobile_verify_code = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

                /* need to integrate Sms Gateway Here */                

                $regNewMobile = User::where('phone_number', '=', $request->phone_number)->first();

                if($regNewMobile->mobile_verified  == "No")
                {
                    $regNewMobile->phone_number         = $request->phone_number;
                    $regNewMobile->mobile_verify_code   = $mobile_verify_code;
                    $regNewMobile->mobile_verified      = "No";
                    $regNewMobile->save();

                    $this->resultapi('1','4 Digit Mobile Verification Code Send.', true);
                }
                else
                {
                    $this->resultapi('2','This Mobile Number Is Already Verified.', true);
                }
            }
        }
        else
        {
            $this->resultapi('0','Mobile Number Not Found.', 0);
        }
    }

    public function getVerifyMobile(Request $request) {

        if($request->phone_number && $request->verification_code)
        {
            $validator = Validator::make($request->all(), [                
                'phone_number'       => 'required|min:10|numeric',
                'verification_code'  => 'required|min:4|numeric',               
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                $verifyMobile = User::where('phone_number', '=', $request->phone_number)->where('mobile_verify_code',$request->verification_code)->first();

                if($verifyMobile && count($verifyMobile) > 0)
                {                    
                    $verifyMobile->mobile_verified  = "Yes";
                    $verifyMobile->save();

                    $this->resultapi('1','Mobile Verified SucessFully.', true);
                }
                else
                {
                    $this->resultapi('2','Wrong Mobile Number Or Verification Code.', false);
                }
            }
        }
        else
        {
            $this->resultapi('0','Mobile Number Not Found.', 0);
        }
    }

    public function getBuyerRegisterInit(Request $request) {

        $buyerRegInit = Cities::getAllCities();

        if(count($buyerRegInit))
        {
            $this->resultapi('1','Cities Found.', $buyerRegInit);
        }
        else
        {
            $this->resultapi('0','No Cities Found.', $buyerRegInit);
        }
    }

    public function getUpdateProfile(Request $request) {

        if($request->phone_number && $request->password)
        {
            $validator = Validator::make($request->all(), [                
                'phone_number'       => 'required|min:10|numeric',
                'password'           => 'required|min:4|numeric',               
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                $updateUser = User::where('phone_number', '=', $request->phone_number)->where('mobile_verified','Yes')->first();

                if(count($updateUser) > 0)
                {
                    //$updateUser->phone_number      = $request->phone_number;
                    $updateUser->password            = bcrypt($request->password);
                    $updateUser->name                = trim($request->name) ? $request->name : $request->phone_number;
                    $updateUser->email               = trim($request->email);
                    $updateUser->usertype            = 'customer';
                    $updateUser->mobile_verified     = "Yes";
                    $updateUser->is_customer_updated = 1;
                    //$updateUser->save();

                    $updateProf                      = UserProfiles::where('user_id',$updateUser->id)->first();
                    $updateProf->customer_address    = trim($request->customer_address) ? $request->customer_address : "";
                    $updateProf->customer_city       = trim($request->customer_city) ? $request->customer_city : "";
                    $updateProf->customer_zipcode    = trim($request->customer_zipcode) ? $request->customer_zipcode :'';
                    //$updateProf->save();

                    if(Auth::attempt(array('phone_number' => $request->phone_number, 'password' => $request->password)) && $updateUser->save() && $updateProf->save())
                    {
                        /*$user = Auth::user();
                        print_r($user);
                        die;*/
                        $this->resultapi('1','Profile Details Updated SucessFully.', true);
                    } 
                    else
                    {
                        $this->resultapi('0','Some Problem With Update Profile.', false);
                    }                    
                }
                else
                {
                    $this->resultapi('0','Customer Related to This Mobile Number Not.', false);
                }
            }
        }
        else
        {
            $this->resultapi('0','Request Details Not Found.', 0);
        }
    }

    public function getUserLogin(Request $request) {

        if($request->phone_number && $request->password)
        {
            $validator = Validator::make($request->all(), [                
                'phone_number'       => 'required|min:10|numeric',
                'password'           => 'required|min:4|numeric',               
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                if(Auth::attempt(array( 'phone_number' => $request->phone_number,'password' => $request->password,'mobile_verified' => 'Yes')))
                {
                    $user = Auth::user();
                    $user['tokenId'] = $this->jwtAuth->fromUser($user);
                    //echo $userToken  = Session::token();
                    //die;
                    $this->resultapi('1','Login SucessFully.', $user);
                } 
                else
                {
                    $user = array();
                    $this->resultapi('0','Some Problem With User Login.', $user);
                }
            }
        }
        else
        {
            $this->resultapi('0','Request Details Not Found.', 0);
        }
    }

    public function getLogout()
    {        
        if(Auth::logout())
        {
            $this->resultapi('1','Logout Successfully.',true);
        }
        else
        {
            $this->resultapi('0','Some Problem With Logout.',true);
        }
    }

    public function resultapi($status,$message,$result = array()) {

        $finalArray['STATUS']   = $status;
        $finalArray['MESSAGE']  = $message;
        $finalArray['DATA']     = $result;

        echo json_encode($finalArray);  
    }    
}
