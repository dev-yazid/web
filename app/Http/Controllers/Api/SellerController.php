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
use App\EmailTemplates;
use Auth;
use Mail;

class SellerController extends Controller
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

    public function getRegisterSeller(Request $request) {

        if($request->all())
        {
            $validator = Validator::make($request->all(), [                
                'name'              => 'required|min:1|',
                'password'          => 'required|min:4|',
                'email'             => 'required|email|unique:users',
                'shop_mobile'       => 'required',               
                'shop_name'         => 'required',
                'shop_address'      => 'required',
                'shop_document'     => 'required',
                'shop_city'         => 'required|numeric',
                'shop_start_time'   => 'required|numeric',
                'shop_close_time'   => 'required|numeric',
                'shop_location_map' => 'required',
                'shop_zipcode'      => 'required|numeric',
                
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {   
                $digits = 4;
                $email_verify_code = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

                $regNewMobile = new User;
                $regNewMobile->name               = trim($request->name);
                $regNewMobile->email              = trim($request->email);
                $regNewMobile->password           = bcrypt($request->password);
                $regNewMobile->email_verify_code  = $email_verify_code;                
                $regNewMobile->email_verified     = "No";
                $regNewMobile->email_verify_code  = $email_verify_code;

                $regNewProfile = new UserProfiles;
                $regNewProfile->user_id             = DB::getPdo()->lastInsertId();
                $regNewProfile->shop_name           = $request->shop_name;
                $regNewProfile->shop_mobile         = $request->shop_mobile;
                $regNewProfile->shop_address        = $request->shop_address;
                $regNewProfile->shop_document       = $request->shop_document;
                $regNewProfile->shop_city           = $request->shop_city;
                $regNewProfile->shop_start_time     = $request->shop_start_time;
                $regNewProfile->shop_close_time     = $request->shop_close_time;
                $regNewProfile->shop_location_map   = $request->shop_location_map;
                $regNewProfile->shop_zipcode        = $request->shop_zipcode;

                if( $regNewMobile->save() && $regNewProfile->save() )
                {
                    $subject  =  "Email Verification";
                    $content  =  "Your Email Verification Code Is : ".$email_verify_code;

                    $mail_data = array(
                        'content'   => $content,
                        'toEmail'   => trim($request->email),
                        'subject'   => $subject,
                        'fromEmail' => 'admin@feeh.com'
                    );

                    $sent = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
                        $message->to($mail_data['toEmail']);
                        $message->from($mail_data['fromEmail']);
                        $message->subject($mail_data['subject']);
                    });

                    if($sent)
                    {
                        $this->resultapi('1','Seller Registered Sucessfully and Verification Code Send To Your Email Address.', true);
                    }
                    else
                    {
                        $this->resultapi('0','Some Problem with Email Send.', true);
                    }
                }
                else
                {
                    $this->resultapi('0','Some Problem with Seller Registration Process.', true);
                }
            }
        }
        else
        {
            $this->resultapi('0','Request Details Not Found.', 0);
        }
    }

    public function getUpdateSellerProfile(Request $request) {

        if($request->all())
        {
            $validator = Validator::make($request->all(), [                
                'uid'               => 'required|numeric',
                'name'              => 'required',
                'shop_mobile'       => 'required',               
                'shop_name'         => 'required',
                'shop_address'      => 'required',
                'shop_city'         => 'required|numeric',
                'shop_start_time'   => 'required|numeric',
                'shop_close_time'   => 'required|numeric',
                'shop_location_map' => 'required',
                'shop_zipcode'      => 'required|numeric',
                'shop_document'     => 'required',                
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {   
                $updateProfileSeller = User::where('id', '=', $request->uid)->first();                
                $updateProfileSeller->name                = trim($request->name);

                $updateSellerProfile = UserProfiles::where('user_id', '=', $request->uid)->first();
                $updateSellerProfile->shop_name           = $request->shop_name;
                $updateSellerProfile->shop_mobile         = $request->shop_mobile;
                $updateSellerProfile->shop_address        = $request->shop_address;
                /*$updateSellerProfile->shop_document       = $request->shop_document;*/
                $updateSellerProfile->shop_city           = $request->shop_city;
                $updateSellerProfile->shop_start_time     = $request->shop_start_time;
                $updateSellerProfile->shop_close_time     = $request->shop_close_time;
                $updateSellerProfile->shop_location_map   = $request->shop_location_map;
                $updateSellerProfile->shop_zipcode        = $request->shop_zipcode;

                if( $updateSellerProfile->save() && $updateProfileSeller->save() )
                {                    
                    $this->resultapi('1','Seller Details Updated Sucessfully.', true);                    
                }
                else
                {
                    $this->resultapi('0','Some Problem with Seller Registration Process.', true);
                }
            }
        }
        else
        {
            $this->resultapi('0','Request Details Not Found.', 0);
        }
    }

    public function getSendEmailVerifyCodeAgain(Request $request) {

        if($request->uid)
        {
            $emailVerification = User::where('id', '=', $request->uid)->first();

            if($emailVerification->email_verified  == "No")
            {   
                $digits = 4;
                $email_verify_code = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

                $emailVerification->email_verified       = 'No';
                $emailVerification->email_verify_code    = $email_verify_code;
                //$emailVerification->save();

                if($emailVerification->save())
                {
                    $subject  =  "Re::Email Verification Code";
                    $content  =  "Your Email Verification Code Is : ".$email_verify_code;

                    $mail_data = array(
                        'content'   => $content,
                        'toEmail'   => trim($emailVerification->email),
                        'subject'   => $subject,
                        'fromEmail' => 'admin@feeh.com'
                    );

                    $sent = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
                        $message->to($mail_data['toEmail']);
                        $message->from($mail_data['fromEmail']);
                        $message->subject($mail_data['subject']);
                    });

                    if($sent)
                    {
                        $this->resultapi('1','Re Email Verification Code Send To Your Registered Email Address.', true);
                    }
                    else
                    {
                        $this->resultapi('0','Some Problem with Email Send.', true);
                    }
                }
                else
                {
                    $this->resultapi('0','Some Problem with Email Send.', true);
                }
            }
            else
            {
                $this->resultapi('0','Email Already Verified.', true);
            }
        }
        else
        {
            $this->resultapi('0','User Not Found.', 0);
        }
    }

    public function getSellerLogin(Request $request) {

        if($request->email && $request->password)
        {
            $validator = Validator::make($request->all(), [                
                'email'              => 'required|email',
                'password'           => 'required|max:8',               
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                if(Auth::attempt(array('email' => trim($request->email), 'password' => trim($request->password),'email_verified' => 'Yes')))
                {
                    $user = Auth::user();
                    $user['tokenId'] = $this->jwtAuth->fromUser($user);
                    //echo $userToken  = Session::token();
                    //die;
                    $this->resultapi('1','Login Sucessfully as Seller.', $user);
                } 
                else
                {
                    $user = array();
                    $this->resultapi('0','Some Problem With Seller Login.', $user);
                }
            }
        }
        else
        {
            $this->resultapi('0','Request Details Not Matched.', 0);
        }
    }

    public function getSellerDetails(Request $request)
    {
        if($request->uid)
        {
            $myProfileDetails = User::getSellerDetails($request->uid);

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

    /*public function getViewRequestByUser(Request $request)
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
    }*/

    public function getAllBrodRequests()
    {   
        $allResponse = BrodResponse::getAllBrodRequest();
        
        if(count($allResponse))
        {
            $this->resultapi('1','Brodcast Request Found.', $allResponse);
        }
        else
        {
            $this->resultapi('0','No Brodcast Request Found.', $allResponse);
        }        
    }

    public function getProductConfirmedBySeller(Request $request)
    {            
        if($request->res_id) 
        {
            $prodConfirmation = BrodResponse::productConfirmedBySeller($request->res_id);
            
            if($prodConfirmation === 1)
            {
                $this->resultapi('1','Product Confirmed By Seller.', true);
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

    public function getChangePasswordSeller(Request $request)
    {        
        if($request->email && $request->password && $request->uid)
        {
            $validator = Validator::make($request->all(), [                
                'email'       => 'required',
                'password'    => 'required',
                'uid'         => 'required|numeric',               
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                $changePassword = User::updatePasswordSeller($request->email, $request->password, $request->uid);
                
                if($changePassword === 1)
                {
                    $this->resultapi('1','Password Updated Sucesfully.', true);
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

    public function resultapi($status,$message,$result = array()) {

        $finalArray['STATUS']   = $status;
        $finalArray['MESSAGE']  = $message;
        $finalArray['DATA']     = $result;

        echo json_encode($finalArray);  
    }    
}
