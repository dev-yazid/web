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
use App\BrodRequest;
use App\BrodResponse;
use App\Transaction;
use Auth;
use Form;
use File;
use Image;


use Twilio\Rest\Client;

class UserController extends Controller
{
    private $req;
    private $user;
    private $jwtAuth;
    function __construct(Request $request, User $user, ResponseFactory $responseFactory, JWTAuth $jwtAuth)
    {
        header('Content-Type: application/json');
        $this->user = $user;
        $this->jwtAuth = $jwtAuth;
        $this->req = $request;
        $this->res = $responseFactory;
        
        $this->middleware('jwt.auth', ['except' => ['testUser','getAppInitData','getVerifyMobile','getRegisterMobile','getSendCodeAgain','getBuyerRegisterInit','getUserLogin']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function testUser(Request $request)
    {
        if(Auth::attempt(array('phone_number' => $request->phone_number, 'password' => $request->password)))
        {
           // echo header('ssss');
            $user = Auth::user();
            //$user['tokenId']     = $this->jwtAuth->fromUser($user);
            //$user['profDetails'] = UserProfiles::where('user_id',$user['id'])->get();
            print_r($user);

            //$this->resultapi('1','Mobile Verified SucessFully..', $user);
        } 
        else
        {
            $user = array();
            $this->resultapi('0','Some Problem With Mobile Verification', $user);
        }
    }

    public function getAppInitData(Request $request)
    {
        $appInitData = array(
            'base_url'             => url('/'),
            'msgImgPath'           => asset('/public/asset/Message/'),
            'msgImgPathThumb'      => asset('/public/asset/Message/thumb/'),
            'brandImgPath'         => asset('/public/asset/brand/'),
            'brandImgPathThumb'    => asset('/public/asset/brand/thumb/'),
            'brodcastImgPath'      => asset('/public/asset/brodcastImg/'),
            'brodcastImgPathThumb' => asset('/public/asset/brodcastImg/thumb/'),
            'shopImgPath'          => asset('/public/asset/shopLicence/'),
            'shopImgPathThumb'     => asset('/public/asset/shopLicence/thumb'),
        );

        $this->resultapi('0','App Init Data.', $appInitData);
    }   

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
                $phoneNumber = $request->phone_number;
                $mobile_verify_code = str_pad(rand(1, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

                /*$sid = 'AC4ab5b2e4a9da816dc45e5af158dc770d';
                $token = 'c2bed0cfbdee0f4dad5db438219b995e';*/

                /*$sid = 'ACa19d22e0d513e7601aa8c06f71b433d0';
                $token = '2d1cb1a4a9a496caf6225ebd122de083';

                $client = new Client($sid, $token);
  
                $number = $client->incomingPhoneNumbers->create(
                    array(
                        "voiceUrl" => "http://demo.twilio.com/docs/voice.xml",
                        "phoneNumber" => "+15005550006"
                    )
                );

                print_r($number);
                */
                //echo $number->sid;


                // Use the client to do fun stuff like send text messages!
                /*$a = $client->messages->create(
                    // the number you'd like to send the message to
                    '+918306062028',
                    array(
                        // A Twilio phone number you purchased at twilio.com/console
                        'from' => '+15005550006',
                        // the body of the text message you'd like to send
                        'body' => 'Hey Jenny! Good luck on the bar exam!'
                    )
                );

               echo $a->status;

                die("sms gateway integration.");
            */
                $regNewMobile = new User;
                $regNewMobile->phone_number         = $request->phone_number;
                $regNewMobile->mobile_verify_code   = $mobile_verify_code;
                $regNewMobile->mobile_verified      = "No";

                if($regNewMobile->save())
                {
                    $userDetails = User::where('phone_number', '=', $request->phone_number)->first();

                    if(count($userDetails) > 0)
                    {
                        $regNewProfile = new UserProfiles;
                        $regNewProfile->user_id = $userDetails->id;
                        $regNewProfile->save();

                        $this->resultapi('1','4 Digit Mobile Verification Code Send.', $userDetails->mobile_verify_code);
                    }
                    else
                    {
                        $this->resultapi('0','User Details Not Found.', 0);
                    }
                }
                else
                {
                    $this->resultapi('0','User Details Not Found.', 0);
                }
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

                if(count($regNewMobile) > 0)
                {
                    /*if($regNewMobile->mobile_verified  == "No")
                    {*/
                        $regNewMobile->phone_number         = $request->phone_number;
                        $regNewMobile->mobile_verify_code   = $mobile_verify_code;
                        $regNewMobile->mobile_verified      = "No";
                        $regNewMobile->save();

                        $this->resultapi('1','Mobile Verification Code Send.', $regNewMobile->mobile_verify_code);
                    /*}
                    else
                    {
                        $this->resultapi('0','This Mobile Number Is Already Verified.', true);
                    }*/
                }
                else
                {
                    $this->resultapi('0','User Details Not Found.', 0);
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
                    $verifyMobile->mobile_verified  = $request->phone_number;
                    $verifyMobile->mobile_verified  = "Yes";
                    $verifyMobile->password         = bcrypt($request->verification_code);
                    
                    if($verifyMobile->save())
                    {
                        if(Auth::attempt(array( 'phone_number' => $request->phone_number,'password' => $request->verification_code,'mobile_verified' => 'Yes')))
                        {
                            $user = Auth::user();
                            $user['tokenId']     = $this->jwtAuth->fromUser($user);
                            $user['profDetails'] = UserProfiles::where('user_id',$user['id'])->get();

                            $this->resultapi('1','Mobile Verified SucessFully..', $user);
                        } 
                        else
                        {
                            $user = array();
                            $this->resultapi('0','Some Problem With Mobile Verification', $user);
                        }
                    }            
                    else
                    {
                        $this->resultapi('0','Some Problem With User Authentication.', false);
                    }
                }
                else
                {
                    $this->resultapi('0','Wrong Mobile Number Or Verification Code.', false);
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

        if($request->all())
        {
            $validator = Validator::make($request->all(), [
                'phone_number'       => 'required|min:10|numeric',
                'uid'                => 'required|numeric',
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                $updateUser = User::where('id',$request->uid)->first();

                if(count($updateUser) > 0)
                {                    
                    $updateUser->name                = trim($request->name) ? $request->name : 'Feeh User';
                    $updateUser->mobile_verified     = $updateUser->mobile_verified;
                    $updateUser->is_customer_updated = 1;

                    if($updateUser->mobile_verified == 'Yes' && $updateUser->email_verified == 'Yes')
                    {
                        $updateUser->usertype  = 'Both';
                    }
                    else
                    {
                        $updateUser->usertype  = 'Seller';
                    }                  

                    $updateProf                   = UserProfiles::where('user_id',$updateUser->id)->first();
                    $updateProf->customer_email   = trim($request->customer_email);
                    $updateProf->customer_address = trim($request->customer_address) ? $request->customer_address : '';
                    $updateProf->customer_city    = trim($request->customer_city) ? $request->customer_city : '';
                    $updateProf->customer_zipcode = trim($request->customer_zipcode) ? $request->customer_zipcode :'';
                    
                    if($updateUser->save() && $updateProf->save())
                    {                        
                        $this->resultapi('1','Profile Details Updated Sucessfully.', true);
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
                'password'           => 'required|min:4',
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
                    $user['tokenId']     = $this->jwtAuth->fromUser($user);
                    $user['profDetails'] = UserProfiles::where('user_id',$user['id'])->get(); 
                    $this->resultapi('1','Logeed In SucessFully.', $user);
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

    public function getMyProfileDetails(Request $request)
    {
        if(Auth::check())
        {
            if($request->uid)
            {
                $myProfileDetails = User::getProfileDetails($request->uid);

                if(count($myProfileDetails))
                {
                    $this->resultapi('1','Profile Details Found.', $myProfileDetails);
                }
                else
                {
                    $this->resultapi('0','No Profile Details Found.', $myProfileDetails);
                }
            } 
            else
            {
                $myProfileDetails = array();
                $this->resultapi('0','User Id Not Found.', $myProfileDetails);
            }
        }
        else
        {
           $this->resultapi('0','Authentication Failed.', $myProfileDetails); 
        }
    }

    public function getViewRequestByUser(Request $request)
    {          
        if($request->uid)
        {
            $brodRequestByUser = BrodRequest::getBrodRequestByUser($request->uid);
            
            if(count($brodRequestByUser))
            {
                $this->resultapi('1','Brodcast Request Found.', $brodRequestByUser);
            }
            else
            {
                $this->resultapi('0','No Brodcast Request Found.', $brodRequestByUser);
            }
        }
        else
        {
            $this->resultapi('0','User Not Found.', false);
        } 
    }

    public function getViewResponse(Request $request)
    {          
        if($request->request_id)
        {
            $allResponse = BrodResponse::getBrodResponseByReqId($request);
            
            if(count($allResponse))
            {
                $this->resultapi('1','Brodcast Request Found.', $allResponse);
            }
            else
            {
                $this->resultapi('0','No Brodcast Request Found.', $allResponse);
            }
        }
        else
        {
            $this->resultapi('0','User Not Found.', false);
        } 
    }

    public function getRemoveResponse(Request $request)
    {            
        if($request->res_id) 
        {
            $resUpdated = BrodResponse::removeResponse($request->res_id);
            
            if($resUpdated === 1)
            {
                $this->resultapi('1','Response Removed Form List Sucessfully.', true);
            }
            else
            {
                $this->resultapi('0','Invalid Response Id.', false);
            }            
        }
        else
        {
            $this->resultapi('0','Seller Response Id Not Found.', false);
        }        
    }

    public function getMarkPriceReadUpdateNoti(Request $request)
    {            
        if($request->res_id) 
        {
            $resUpdated = BrodResponse::priceUpdateNotiRead($request->res_id);
            
            if($resUpdated === 1)
            {
                $this->resultapi('1','Price Notification Status Changed.', true);
            }
            else
            {
                $this->resultapi('0','Invalid Response Id.', false);
            }            
        }
        else
        {
            $this->resultapi('0','Seller Response Id Not Found.', false);
        }        
    }

    public function getProductConfirmedByBuyer(Request $request)
    {            
        if($request->res_id) 
        {
            $prodConfirmation = BrodResponse::productConfirmedByBuyer($request->res_id);
            
            if($prodConfirmation === 1)
            {
                $this->resultapi('1','Product Confirmed By Buyer.', true);
            }
            else
            {
                $this->resultapi('0','Invalid Response Id.', false);
            }            
        }
        else
        {
            $this->resultapi('0','Buyer Response Id Not Found.', false);
        }        
    }

    public function getChangePassword(Request $request)
    {        
        if($request->phone_number && $request->password && $request->uid)
        {
            $validator = Validator::make($request->all(), [                
                'phone_number'       => 'required|min:10|numeric',
                'password'           => 'required',
                'uid'                => 'required|numeric',               
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                $changePassword = User::updatePassword($request->phone_number, $request->password, $request->uid);
                
                if($changePassword === 1)
                {
                    $this->resultapi('1','Password Changed Sucesfully.', true);
                }
                else
                {
                    $this->resultapi('0','Some Problem In Change Password.', false);
                }
            }
        }
        else
        {
            $this->resultapi('0','User Details Not Found.', false);
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
