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
use Form;
use File;
use Image;

class SellerController extends Controller
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

        $this->middleware('jwt.auth', ['except' => ['getSellerLogin','getMobileVerify','getSendEmailVerifyCodeAgain','getRegisterSeller']]);
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
                'seller_name'       => 'required',
                'password'          => 'required|min:4',
                'email'             => 'required|email|unique:users',
                'shop_mobile'       => 'required',               
                'shop_name'         => 'required',
                'shop_address'      => 'required',
                'file'              => 'mimes:jpeg,jpg,png,pdf,doc|max:1024',
                'shop_city'         => 'required',
                'shop_start_time'   => 'required',
                'shop_close_time'   => 'required',
                'shop_location_map' => 'required',              
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {   
                $checkMobile = User::where('phone_number',$request->shop_mobile)->first();
                if(count($checkMobile) < 1)
                {
                    $digits = 4;
                    $email_verify_code = str_pad(rand(1, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);

                    $sendSms = User::sendSms(trim($request->shop_mobile), trim($email_verify_code));

                    $filename = "";
                    $regNewMobile = new User;                
                    $regNewMobile->email              = trim($request->email);
                    $regNewMobile->password           = bcrypt($request->password);
                    $regNewMobile->phone_number       = trim($request->shop_mobile);
                    $regNewMobile->usertype           = "Seller";
                    $regNewMobile->email_verify_code  = $email_verify_code;                
                    $regNewMobile->email_verified     = "No";
                    $regNewMobile->status             = 0;
                                     
                    /* for Shop Licence Image Upload */
                    $bserUrlImg = asset('/public/asset/shopLicence/thumb/');
                    if($request->hasFile('file'))
                    {           
                        $file = $request->file('file');
                        $path = public_path().'/asset/shopLicence/';
                        $thumbPath = public_path('/asset/shopLicence/thumb/');

                        $timestamp = time().  uniqid(); 
                        $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                        $file->move($thumbPath,$filename);

                        /*$img = Image::make($path.$filename);
                        $img->resize(100, 100, function ($constraint) { 
                            $constraint->aspectRatio();
                        })->save($thumbPath.'/'.$filename);*/
                    }

                    if($regNewMobile->save())
                    {
                        $insertedUser = User::where('email',trim($request->email))->first();
                        $regNewProfile = new UserProfiles;
                        $regNewProfile->seller_name         = trim($request->seller_name);
                        $regNewProfile->user_id             = $insertedUser->id;
                        $regNewProfile->shop_name           = $request->shop_name;
                        $regNewProfile->shop_mobile         = $request->shop_mobile;
                        $regNewProfile->shop_address        = $request->shop_address;                    
                        $regNewProfile->shop_city           = $request->shop_city;
                        $regNewProfile->shop_start_time     = $request->shop_start_time;
                        $regNewProfile->shop_close_time     = $request->shop_close_time;
                        $regNewProfile->shop_location_map   = $request->shop_location_map;
                        $regNewProfile->shop_zipcode        = $request->shop_zipcode ? $request->shop_zipcode : "";
                        $regNewProfile->shop_document       = $filename ? $filename : "";

                        /*if($insertedUser->mobile_verified=='Yes' && $insertedUser->email_verified=='Yes')
                        {
                            $regNewMobile->usertype  = 'Both';
                        }
                        else
                        {
                            $regNewMobile->usertype  = 'Seller';
                        }*/
                    }             

                    if($regNewMobile->save() && $regNewProfile->save() )
                    {                 
                        /* Seller register Notification for Admin By Email */
                        //$adminDetails = User::where('usertype','Super Admin')->where('role','Super Admin')->first();
                        //$adminEmail  =  $adminDetails->email;
                        $adminEmail  =  'amitg@techuz.com';
                        $subject     =  'New Seller Register';
                        $content     =  "Hello, <br/><br/>A New User Registered With Name : ".$request->seller_name.", Email : ".$request->email.", and User Id : ".$insertedUser->id;

                        $mail_data = array(
                            'content'   => $content,
                            'toEmail'   => trim($adminEmail),
                            'subject'   => $subject,
                            'fromEmail' => trim($request->email)
                        );

                        $send = Mail::send('emails.mail-template', $mail_data, function($message) use ($mail_data) {
                            $message->to($mail_data['toEmail']);
                            $message->from($mail_data['fromEmail']);
                            $message->subject($mail_data['subject']);
                        });                    
                        
                        $this->resultapi('1','Registered Sucessfully and Verification Code Send To Your Mobile Number.',$insertedUser->email_verify_code);                    
                    }
                    else
                    {
                        $this->resultapi('0','Some Problem with Seller Registration Process.', false);
                    }
                }
                else
                {
                    $this->resultapi('0','This Mobile Number is already Taken.', false);
                }
            }
        }
        else
        {
            $this->resultapi('0','Request Details Not Found.',false);
        }
    }

    public function getUpdateSellerProfile(Request $request) {
        if(Auth::check())
        {
            if($request->all())
            {
                $validator = Validator::make($request->all(), [                
                    'uid'               => 'required|numeric',
                    'seller_name'       => 'required',
                    'shop_mobile'       => 'required',               
                    'shop_name'         => 'required',
                    'shop_address'      => 'required',
                    'shop_city'         => 'required|numeric',
                    'shop_start_time'   => 'required',
                    'shop_close_time'   => 'required',
                    'shop_location_map' => 'required',
                    'shop_zipcode'      => 'required|numeric',
                    'file'              => 'mimes:jpeg,jpg,png,pdf,doc|max:1024',               
                ]);
                
                if ($validator->fails()) 
                {
                    $this->resultapi('0', $validator->errors()->all(), 0);
                }
                else
                {   
                    $updateProfileSeller                = User::where('id',$request->uid)->first();
                    $updateProfileSeller->phone_number  = trim($request->shop_mobile);                
                    $updateProfileSeller->name          = trim($request->name);
                    $updateProfileSeller->usertype      = 'Seller'; 

                    $filename = "";
                    if($request->image_upload === "YES")
                    {           
                        $file = $request->file('file');
                        $path = public_path().'/asset/shopLicence/';
                        $thumbPath = public_path('/asset/shopLicence/thumb/');

                        $timestamp = time().  uniqid(); 
                        $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                        $file->move($thumbPath,$filename);

                        /*$img = Image::make($path.$filename);
                        $img->resize(100, 100, function ($constraint) { 
                            $constraint->aspectRatio();
                        })->save($thumbPath.'/'.$filename);*/
                    }

                    $updateSellerProfile = UserProfiles::where('user_id', '=', $request->uid)->first();
                    $updateSellerProfile->seller_name         = $request->seller_name;
                    $updateSellerProfile->shop_name           = $request->shop_name;
                    $updateSellerProfile->shop_mobile         = $request->shop_mobile;
                    $updateSellerProfile->shop_address        = $request->shop_address;
                    $updateProfileSeller->email_verified      = $updateProfileSeller->email_verified;
                    $updateProfileSeller->status              = $updateProfileSeller->status;                                       
                    
                    $updateSellerProfile->seller_name         = $request->seller_name;
                    $updateSellerProfile->shop_city           = $request->shop_city;
                    $updateSellerProfile->shop_start_time     = $request->shop_start_time;
                    $updateSellerProfile->shop_close_time     = $request->shop_close_time;
                    $updateSellerProfile->shop_location_map   = $request->shop_location_map;
                    $updateSellerProfile->shop_zipcode        = $request->shop_zipcode ? $request->shop_zipcode : "";

                    if($request->image_upload === "YES")
                    { 
                        $updateSellerProfile->shop_document       = $filename;
                    }

                    if( $updateSellerProfile->save() && $updateProfileSeller->save() )
                    {                    
                        $this->resultapi('1','Seller Details Updated Sucessfully.', true);                    
                    }
                    else
                    {
                        $this->resultapi('0','Some Problem with Seller Registration Process.', false);
                    }
                }
            }
            else
            {
                $this->resultapi('0','Request Details Not Found.', 0);
            }
        }
        else
        {            
            $this->resultapi('0','Authentication Failed.', 0);
        }  
    }

    public function getSendEmailVerifyCodeAgain(Request $request) {
                   
        $validator = Validator::make($request->all(), [                
            'phone_number'  => 'required',
        ]);
        
        if ($validator->fails()) 
        {
            $this->resultapi('0', $validator->errors()->all(), 0);
        }
        else
        {
            $emailVerification = User::where('phone_number',$request->phone_number)->first();

            if(count($emailVerification) > 0)
            {
                $digits = 4;
                $email_verify_code = str_pad(rand(1, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
                // sms gateway 
                $sendSms = User::sendSms(trim($request->phone_number), trim($email_verify_code));

                $emailVerification->email_verified       = 'No';
                $emailVerification->email_verify_code    = $email_verify_code;
                $emailVerification->save();
                                         
                $this->resultapi('1','Re Verification Code Send To Your Mobile Number.', $email_verify_code);
            }
            else
            {
                $this->resultapi('0','Phone Number Not Exist.', false);
            }
        }
    }

    public function getMobileVerify(Request $request) {
        
        if($request->phone_number && $request->verification_code)
        {
            $validator = Validator::make($request->all(), [                
                'phone_number'          => 'required|min:8',
                'verification_code'     => 'required|max:4',               
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                $emailCodeVerification = User::where('phone_number',$request->phone_number)->where('email_verify_code',$request->verification_code)->first();               

                if(count($emailCodeVerification) > 0 )
                {
                    $emailCodeVerification->email_verified = "Yes";
                    if($emailCodeVerification->mobile_verified == "Yes")
                    {
                        $emailCodeVerification->usertype = 'Both';
                    }
                    $emailCodeVerification->save();

                    $this->resultapi('1','Mobile Verified Sucessfully.', true);
                } 
                else
                {
                    $user = array();
                    $this->resultapi('0','Mobile Number Not Exist.', false);
                }
            }
        }
        else
        {
            $this->resultapi('0','Request Details Not Matched.', 0);
        }
    }

    public function getSellerLogin(Request $request) {
       
        $validator = Validator::make($request->all(), [                
            'email'       => 'required|email',
            'password'    => 'required|max:8|min:4',               
        ]);
        
        if ($validator->fails()) 
        {
            $this->resultapi('0', $validator->errors()->all(), 0);
        }
        else
        {            
            $userDetails = User::where('email',$request->email)->select('email_verified','email_verify_code','phone_number')->first();

            if(count($userDetails) > 0)
            {
                if($userDetails->email_verified == "Yes")
                {
                    /*if(Auth::attempt(array('email' => trim($request->email), 'password' => trim($request->password),'email_verified' => 'Yes', 'status' => '1')))*/

                    if(Auth::attempt(array('email' => trim($request->email), 'password' => trim($request->password),'email_verified' => 'Yes', 'status' => '1')))
                    {
                        $user = Auth::user();
                        $user['tokenId'] = $this->jwtAuth->fromUser($user);
                        $user['profDetails'] = UserProfiles::where('user_id',$user['id'])->get();                                     
                        $mapUrl ='https://www.google.com/maps?q=';
                        $user['map_location'] = $mapUrl.$user['profDetails'][0]['shop_location_map'];

                        $this->resultapi('1','Logged In Sucessfully.', $user);
                    } 
                    else
                    {
                        $user = array();
                        $this->resultapi('0','Invalid Login Details.', $user);
                    }
                }
                else
                {
                    $this->resultapi('0','Your Mobile Number is not Verified.', $userDetails);
                }
            }
            else
            {
                $user = array();
                $this->resultapi('0','Email Address Not Exist.', $user);
            }
        }
    }

    public function getSellerDetails(Request $request)
    {   
        if(Auth::check())
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
        else
        {
            $myProfileDetails = array();
            $this->resultapi('0','Authentication Failed.', $myProfileDetails);
        }   
    }

    public function getAllBrodRequests(Request $request)
    {   
        if(Auth::check())
        {
            if($request->uid)
            {
                $allResponse = BrodRequest::getAllBrodRequest($request->uid);

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
                $allResponse = array();
                $this->resultapi('0','User Id Not Found.', $allResponse);
            }
        }
        else
        {
            $allResponse = array();
            $this->resultapi('0','Authentication Failed.', $allResponse);
        }      
    }

    public function getUpdateResponseBySeller(Request $request)
    {   
        if(Auth::check())
        {
            if($request->res_id && $request->price)
            {
                $validator = Validator::make($request->all(), [                
                    'res_id'   => 'required|numeric',
                    'price'    => 'required|max:6',               
                ]);
            
                if ($validator->fails()) 
                {
                    $this->resultapi('0', $validator->errors()->all(), 0);
                }
                else
                {
                    $respDetail = BrodResponse::find($request->res_id);

                    if(count($respDetail) > 0)
                    {   
                        if($respDetail->is_prod_confirm_by_buyer == 1)
                        {
                            $this->resultapi('0','Product Already Confirmed by Customer.', true);
                        }
                        elseif($respDetail->removed_by_user == 1)
                        {
                            $this->resultapi('0','Customer Rejected Your Proposal.', true);
                        }
                        else
                        {                           
                            $respDetail->price          = $request->price;
                            $respDetail->price_updated  = 1;
                            $respDetail->save();

                            $reqDetail = BrodRequest::find($respDetail->request_id);
                            $reqDetail->is_seller_replied = 1;
                            $reqDetail->save();

                            $this->resultapi('1','Response Details Updated Sucessfully.', true);
                        }
                    }
                    else
                    {
                        $this->resultapi('0','Response Details Not Exist.', $allResponse);
                    }
                }
            }
            else
            {
                $allResponse = array();
                $this->resultapi('0','Details Not Found.', $allResponse);
            }
        }
        else
        {
            $allResponse = array();
            $this->resultapi('0','Authentication Failed.', $allResponse);
        }      
    }

    public function getProductConfirmedBySeller(Request $request)
    {            
        if(Auth::check())
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
        else
        {           
            $this->resultapi('0','Authentication Failed.', false);
        }          
    }

    public function getChangePasswordSeller(Request $request)
    {        
        if(Auth::check())
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
        else
        {
            $this->resultapi('0','Authentication Failed.', false);
        }   
    }

    public function getRequestDeatils(Request $request)
    {          
        if(Auth::check())
        {
            if($request->req_id && $request->uid )
            {
                $brodRequestByUser = BrodResponse::getRequestDetailsBySeller($request->req_id, $request->uid);
                
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
        else
        {            
            $this->resultapi('0','Authentication Failed.', false);
        }   
    }       

    public function resultapi($status,$message,$result = array()) {

        $finalArray['STATUS']   = $status;
        $finalArray['MESSAGE']  = $message;
        $finalArray['DATA']     = $result;

        echo json_encode($finalArray);  
    }

    public function resultapi2($status,$result = array(),$message) {

        $finalArray['STATUS']   = $status;
        $finalArray['IMGPATH']  = $message;
        $finalArray['MESSAGE']  = $result;

        echo json_encode($finalArray);  
    }    
}
