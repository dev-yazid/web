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
        
        $this->middleware('jwt.auth', ['except' => ['testUser','getMyProfileDetails','getAppInitData','getChangePassword','getVerifyMobile','getRegisterMobile','getRegisterMobileTest','getSendCodeAgain','getBuyerRegisterInit','getUserLogin','getViewRequestByUser','getRemoveResponse']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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



    public function getRegisterMobile(Request $request) {
        /* 1 for buyer 2 for seller */ 
        if($request->phone_number && $request->customer_type)
        {
            $validator = Validator::make($request->all(), [
                'phone_number'  => 'required|min:8|numeric',
                'customer_type'  => 'required|numeric',
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                $checkMobileExist = User::where('phone_number',trim($request->phone_number))->first();

                $phoneNumber = $request->phone_number;
                $mobile_verify_code = rand (1000 , 9999);

                if($request->customer_type == 2)
                {
                    if(count($checkMobileExist) > 0)
                    {   
                        if($checkMobileExist->status == 1)
                        {
                            $sendSms = User::sendSms(trim($request->phone_number), trim($mobile_verify_code));
                        }                        
                        else
                        {
                            $this->resultapi('0','Mobile Number Not Exist.', 0);
                        }
                    }
                    else
                    {
                        $this->resultapi('0','Mobile Number Not Exist.', 0);
                    }
                }
                else
                {
                    $sendSms = User::sendSms(trim($request->phone_number), trim($mobile_verify_code));                
                }
               
                if(count($checkMobileExist) > 0)
                {
                    if($request->customer_type == 2)
                    {
                        if($checkMobileExist->status == 1)
                        {
                            if($checkMobileExist->seller_mobile_verified == 'Yes')
                            {
                                $checkMobileExist->phone_number                = $request->phone_number;
                                $checkMobileExist->seller_mobile_verify_code   = $mobile_verify_code;
                                //$checkMobileExist->mobile_verified      = "No";
                                //$checkMobileExist->status               = 1;
                                $checkMobileExist->save();

                                $this->resultapi('1','4 Digit Mobile Verification Code Send.', $mobile_verify_code);
                            }
                            else if($checkMobileExist->seller_mobile_verified == 'No')
                            {
                                $checkMobileExist->phone_number                = $request->phone_number;
                                $checkMobileExist->seller_mobile_verify_code   = $mobile_verify_code;
                                //$checkMobileExist->mobile_verified      = "No";
                                //$checkMobileExist->status               = 1;
                                $checkMobileExist->save();

                                $this->resultapi('1','4 Digit Mobile Verification Code Send.', $mobile_verify_code);
                           
                            }
                            else
                            {
                                $this->resultapi('0','Problem In User Verification.', $mobile_verify_code);
                            }
                       }
                       else
                       {
                            $this->resultapi('2','You Have To Wait Until Admin Will Approve.', $mobile_verify_code);
                       }
                    }
                    else
                    {
                        $checkMobileExist->phone_number         = $request->phone_number;
                        $checkMobileExist->mobile_verify_code   = $mobile_verify_code;
                        $checkMobileExist->mobile_verified      = "No";
                        $checkMobileExist->status               = 1;
                        $checkMobileExist->save();
                        
                        $this->resultapi('1','4 Digit Mobile Verification Code Send.', $mobile_verify_code);
                    }
                }
                else
                {
                    $regNewMobile = new User;
                    $checkMobileExist->name             = "Feeh User";
                    $regNewMobile->phone_number         = $request->phone_number;
                    $regNewMobile->mobile_verify_code   = $mobile_verify_code;
                    $regNewMobile->status               = 1;
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
                'phone_number'  => 'required|min:8|numeric',
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else 
            {
                $regNewMobile = User::where('phone_number',$request->phone_number)->first();

                if(count($regNewMobile) > 0)
                {  
                	$mobile_verify_code = rand (1000 , 9999);
                    $sendSms = User::sendSms(trim($request->phone_number), trim($mobile_verify_code));

                    $regNewMobile->phone_number         = $request->phone_number;
                    $regNewMobile->mobile_verify_code   = $mobile_verify_code;
                    $regNewMobile->mobile_verified      = "No";
                    $regNewMobile->save();

                    $this->resultapi('1','Mobile Verification Code Send.', $regNewMobile->mobile_verify_code);
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
                'phone_number'       => 'required|min:8|numeric',
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
                    $verifyMobile->mobile_verified      = $request->phone_number;
                    $verifyMobile->name                 = 'Feeh User';
                    $verifyMobile->mobile_verified      = "Yes";
                    //$verifyMobile->mobile_verify_code   = "";
                    
                    if($verifyMobile->save())
                    {
                        if(Auth::attempt(array( 'phone_number' => $request->phone_number,'password' => "123456",'mobile_verified' => 'Yes','mobile_verify_code' => $request->verification_code)))
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
    
    public function getUpdateProfile(Request $request) {

        if($request->all())
        {
            $validator = Validator::make($request->all(), [
                'phone_number'       => 'required|min:8|numeric',
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
                        $this->resultapi('0','Some Problem With Profile Update.', false);
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
                'phone_number'       => 'required|min:8|numeric',
                'password'           => 'required|max:20',
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                if(Auth::attempt(array( 'phone_number' => $request->phone_number,'password' => '123456','mobile_verified' => 'Yes','mobile_verify_code' => $request->password)))
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

    public function getViewRequestByUser(Request $request)
    {          
        if($request->uid && $request->status && $request->per_page)
        {
            $brodRequestByUser = BrodRequest::getBrodRequestByUser($request->uid,$request->status,$request->per_page);
    
            if(count($brodRequestByUser) > 0)
            {
                $this->resultapi('1','Product Request Found.', $brodRequestByUser);
            }
            else
            {
                $this->resultapi('0','No Product Request Found.', $brodRequestByUser);
            }
        }
        else
        {
            $this->resultapi('0','User Details Not Found.', false);
        } 
    }

    public function getViewResponse(Request $request)
    {         
        if($request->request_id && $request->per_page)
        {
            $allResponse = BrodResponse::getBrodResponseByReqId($request->request_id,$request->per_page);
            
            if(count($allResponse))
            {
                $this->resultapi('1','Response Found.', $allResponse);
            }
            else
            {
                $this->resultapi('0','No Response Found.', $allResponse);
            }
        }
        else
        {
            $this->resultapi('0','User Not Found.', false);
        } 
    }

    public function getRemoveRequest(Request $request)
    { 
        if($request->req_id && $request->uid) 
        {
            $resUpdated = BrodResponse::removeResponse($request->req_id,$request->uid);
            
            if($resUpdated == 1)
            {
                $this->resultapi('1','Removed Sucessfully.', true);
            }
            else
            {
                $this->resultapi('0','Invalid Request Id.', false);
            }            
        }
        else
        {
            $this->resultapi('0','Request Id Not Found.', false);
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
            else if($prodConfirmation === 2)
            {
                 $this->resultapi('0','You have Already Confirmed This Request.', false);
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
                'phone_number'       => 'required|min:8|numeric',
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

    public function getRemoveResponse(Request $request)
    {
        if($request->res_id && $request->uid)
        {
            $checkResponse = BrodResponse::where('id',$request->res_id)->first();
            if(count($checkResponse) > 0)
            {
                $checkResponse->removed_by_user = 1;
                $checkResponse->save();

                $this->resultapi('1','Response Removed Sucessfully.', false);
            }
            else
            {
                $this->resultapi('0','Response Details Not Found.', false);
            }
        }
        else
        {
            $this->resultapi('0','Response Details Not Found.', false);
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
