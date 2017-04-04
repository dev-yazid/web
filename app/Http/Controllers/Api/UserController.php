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

        //pr($request->all());

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
                $regNewMobile->save();

                $user = User::where('phone_number', '=', $request->phone_number)->first();

                $regNewProfile = new UserProfiles;
                $regNewProfile->user_id = $user->id;
                $regNewProfile->save();

                $this->resultapi('0','4 Digit Mobile Verification Code Send.', true);
            }
        }
        else
        {
            $this->resultapi('0','Request Data Not Found.', 0);

        }
    }

    public function resultapi($status,$message,$result = array()) {

        $finalArray['STATUS']   = $status;
        $finalArray['MESSAGE']  = $message;
        $finalArray['DATA']     = $result;

        echo json_encode($finalArray);  
    }    
}
