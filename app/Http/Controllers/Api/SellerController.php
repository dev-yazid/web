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
use Session; 

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

        $this->middleware('jwt.auth', ['except' => ['getSellerDetails','getUpdateSellerProfile','getSellerApprovedByAdmin','getSellerLogin','getMobileVerify','getSendMobileVerifyCodeAgain','getRegisterSeller']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function getRegisterSeller(Request $request) {

       
        $validator = Validator::make($request->all(), [                
            'seller_name'       => 'required',
            'email'             => 'required|unique:users|email',
            'phone_number'      => 'required|min:8',               
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
            $checkMobile = User::where('phone_number',$request->phone_number)->first();
            if( count($checkMobile) > 0)
            {
                if($checkMobile->status == 1)
                {
                    //die("Amit");
                    $seller_mobile_verify_code = rand (1000 , 9999);
                    $sendSms = User::sendSms(trim($request->phone_number), trim($seller_mobile_verify_code));

                    //$checkMobile->email                      = trim($request->email);
                    //$checkMobile->name                       = trim($request->seller_name);
                    //$checkMobile->phone_number               = trim($request->phone_number);
                    $checkMobile->seller_mobile_verify_code    = $seller_mobile_verify_code;
                    $checkMobile->save();

                    $this->resultapi('1','Verification Code Send To Your Mobile Number.',$seller_mobile_verify_code); 
       
                }
                else if($checkMobile->status == 0)
                {
                    $userProfile = UserProfiles::where('user_id',$checkMobile->id)->first();
                    if(count($userProfile) > 0)
                    {
                        $checkMobile->email                        = trim($request->email);
                        $checkMobile->phone_number                 = trim($request->phone_number);
                        //$checkMobile->seller_mobile_verify_code    = $seller_mobile_verify_code;
                       

                        $userProfile->seller_name         = trim($request->seller_name);
                        $userProfile->shop_name           = $request->shop_name;
                        $userProfile->shop_mobile         = $request->phone_number;
                        $userProfile->shop_address        = $request->shop_address;                    
                        $userProfile->shop_city           = $request->shop_city;
                        $userProfile->shop_start_time     = $request->shop_start_time;
                        $userProfile->shop_close_time     = $request->shop_close_time;
                        $userProfile->shop_location_map   = $request->shop_location_map;
                        $userProfile->shop_zipcode        = $request->shop_zipcode ? $request->shop_zipcode : "";
                        
                        if($request->image_upload === "YES")
                        {           
                            if($request->hasFile('file'))
                            {
                                $file = $request->file('file');
                                $path = public_path().'/asset/shopLicence/';
                                $thumbPath = public_path('/asset/shopLicence/thumb/');

                                $timestamp = time().  uniqid(); 
                                $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                                File::makeDirectory(public_path().'asset/', 0777, true, true);
                                $file->move($thumbPath,$filename);

                                $userProfile->shop_document       = $filename ;
                            }
                        }
                        $checkMobile->save();
                        $userProfile->save();

                        $this->resultapi('2','Profile Registered Sucessfully, You Have Wait for Admin Approval.',false);
                    }
                    else
                    {
                         $this->resultapi('0','Some Problem In Registration.',false); 
                    }
                }
                else
                {
                    $this->resultapi('0','Some Problem In Verification.',false);           
                }
            }
            else
            {
                $filename = "";
                $regNewMobile = new User;                
                $regNewMobile->email              = trim($request->email);
                $regNewMobile->phone_number       = trim($request->phone_number);
                $regNewMobile->email_verified     = "No";
                                 
                /* for Shop Licence Image Upload */
                /*$bserUrlImg = asset('public/asset/shopLicence/thumb/');
                if($request->hasFile('file'))
                {           
                    $file = $request->file('file');
                    $path = public_path().'/asset/shopLicence/';
                    $thumbPath = public_path('asset/shopLicence/thumb/');

                    $timestamp = time().  uniqid(); 
                    $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                    $file->move($thumbPath,$filename);
                }*/

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
                    # $regNewProfile->shop_document       = $filename ? $filename : "";
                    //$regNewProfile->shop_document       = "";
                    if($request->image_upload === "YES")
                    {           
                        $file = $request->file('file');
                        $path = public_path().'/asset/shopLicence/';
                        $thumbPath = public_path('/asset/shopLicence/thumb/');

                        $timestamp = time().  uniqid(); 
                        $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                        File::makeDirectory(public_path().'asset/', 0777, true, true);
                        $file->move($thumbPath,$filename);

                        $regNewProfile->shop_document       = $filename ;
                    }
                }             

                if($regNewMobile->save() && $regNewProfile->save() )
                {   
                    $checkMobile = User::where('phone_number',$request->phone_number)->first();
                    if( count($checkMobile) > 0)
                    {
                        if($checkMobile->status == 1)
                        {
                            $seller_mobile_verify_code = rand (1000 , 9999);
                            $sendSms = User::sendSms(trim($request->phone_number), trim($seller_mobile_verify_code));

                            $checkMobile->email                        = trim($request->email);
                            $checkMobile->phone_number                 = trim($request->phone_number);
                            $checkMobile->seller_mobile_verify_code    = $seller_mobile_verify_code;
                            $checkMobile->save();

                            $this->resultapi('1','Verification Code Send To Your Mobile Number.',$seller_mobile_verify_code); 
               
                        }
                        else if($checkMobile->status == 0)
                        {
                            
                             $this->resultapi('2','You Have to Wait Until Admin Will Approve.',false);
                        }
                        else
                        {
                            $this->resultapi('0','Some Problem In Verification.',false);           
                        }
                    }

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
                }
                else
                {
                    $this->resultapi('0','Some Problem with Seller Registration Process.', false);
                }
     
            }
        }        
    }

    public function getUpdateSellerProfile(Request $request) {
        if($request->all())
        {
            $validator = Validator::make($request->all(), [                
                'uid'               => 'required|numeric',
                'seller_name'       => 'required',
                'phone_number'      => 'required:min:8',               
                'shop_name'         => 'required',
                'email'             => 'required|email',
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
                $updateProfileSeller                          = User::where('id',$request->uid)->first();
                $updateProfileSeller->phone_number            = trim($request->phone_number);                
                //$updateProfileSeller->name                    = trim($request->name);
                $updateProfileSeller->email                   = $request->email;
                $updateProfileSeller->is_seller_updated       = 1; 

                $filename = "";
                if($request->image_upload === "YES")
                {           
                    $file = $request->file('file');
                    $path = public_path().'/asset/shopLicence/';
                    $thumbPath = public_path('/asset/shopLicence/thumb/');

                    $timestamp = time().  uniqid(); 
                    $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                    File::makeDirectory(public_path().'asset/', 0777, true, true);
                    $file->move($thumbPath,$filename);

                    /*$img = Image::make($path.$filename);
                    $img->resize(100, 100, function ($constraint) { 
                        $constraint->aspectRatio();
                    })->save($thumbPath.'/'.$filename);*/
                }

                $updateSellerProfile = UserProfiles::where('user_id', '=', $request->uid)->first();
                $updateSellerProfile->seller_name         = $request->seller_name;
                $updateSellerProfile->shop_name           = $request->shop_name;
                //$updateSellerProfile->shop_email        = $request->email;
                $updateSellerProfile->shop_mobile         = $request->phone_number;
                $updateSellerProfile->shop_address        = $request->shop_address;
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
                    $this->resultapi('0','Some Problem with Profile Update.', false);
                }
            }
        }
        else
        {
            $this->resultapi('0','Request Details Not Found.', 0);
        }
    }

    public function getSendMobileVerifyCodeAgain(Request $request) {
                   
        $validator = Validator::make($request->all(), [                
            'phone_number'  => 'required:min:8',
        ]);
        
        if ($validator->fails()) 
        {
            $this->resultapi('0', $validator->errors()->all(), 0);
        }
        else
        {
            $checkUserExist = User::where('phone_number',$request->phone_number)->first();

            if(count($checkUserExist) > 0)
            {
                
                $mobile_verify_code = rand ( 1000 , 9999 );
                // sms gateway 
                $sendSms = User::sendSms(trim($request->phone_number), trim($mobile_verify_code));

                $checkUserExist->seller_mobile_verified      = 'No';
                $checkUserExist->seller_mobile_verify_code   = $mobile_verify_code;
                $checkUserExist->save();
                                         
                $this->resultapi('1','Re Verification Code Send To Your Mobile Number.', $mobile_verify_code);
            }
            else
            {
                $this->resultapi('0','Mobile Number Not Exist.', false);
            }
        }
    }

    /*public function getSellerApprovedByAdmin(Request $request) {
                   
        $validator = Validator::make($request->all(), [                
            'phone_number'  => 'required',
        ]);
        
        if ($validator->fails()) 
        {
            $this->resultapi('0', $validator->errors()->all(), 0);
        }
        else
        {
            $checkUserExist = User::where('phone_number',$request->phone_number)->first();         
            if(count($checkUserExist) > 0)
            {   
                if($checkUserExist->status == 1)
                {
                    $this->resultapi('2','Seller Approved By Admin.', " Approved By Admin");
                }
                else
                {
                    $this->resultapi('0','Seller Not Approved.', "Not Approved By Admin");
                }
            }
            else
            {
                $this->resultapi('0','Mobile Number Not Exist.', false);
            }
        }
    }*/

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
                $mobileVerification = User::where('phone_number',$request->phone_number)->where('seller_mobile_verify_code',$request->verification_code)->first();               

                if(count($mobileVerification) > 0 )
                {
                    $mobileVerification->seller_mobile_verified = "Yes";
                    //$mobileVerification->seller_mobile_verify_code = "";
                    $mobileVerification->save();
                   
                    if($mobileVerification->status == 1)
                    {
                        if(Auth::attempt(array('phone_number' => trim($request->phone_number), 'password' => '123456','seller_mobile_verified' => 'Yes', 'status' => '1', 'seller_mobile_verify_code' => $request->verification_code)))
                        {
                            $user = Auth::user();
                            $user['tokenId'] = $this->jwtAuth->fromUser($user);
                            $user['profDetails'] = UserProfiles::where('user_id',$user['id'])->get();                                     
                            $mapUrl ='https://www.google.com/maps?q=';
                            $user['map_location'] = $mapUrl.$user['profDetails'][0]['shop_location_map'];

                            $this->resultapi('1','Verified Sucessfully.', $user);
                        } 
                        else
                        {
                            $user = array();
                            $this->resultapi('0','Invalid Verification Details.', $user);
                        }
                    }
                    else
                    {
                        $this->resultapi('2','Mobile Verified Sucessfully, You Have To Wait Untill Admin Will Approve.', 'Not Approved By Admin.');
                    }
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

    public function getAllBrodRequests(Request $request)
    { 
        if($request->uid && $request->per_page)
        {
            $allResponse = BrodRequest::getAllBrodRequest($request->uid, $request->per_page);

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

    public function getSendResponse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price'           => 'required|max:6',
            'customer_id'     => 'required|numeric',
            'seller_id'       => 'required|numeric',
            'request_id'      => 'required|numeric',
        ]);

        $respDetail = BrodResponse::where('seller_id',$request->seller_id)->where('request_id',$request->request_id)->first();

        if(count($respDetail) > 0)
        {
            if($request->price_updated == "YES")
            {
                if( $respDetail->is_prod_confirm_by_buyer == 0)
                {
                    $respDetail->price              = $request->price;
                    $respDetail->price_updated      = 1;
                    $respDetail->removed_by_user    = 0;
                    $respDetail->read_status        = 0;
                    $respDetail->save();

                    $this->resultapi(1,'Price Updated Sucessfully.', true);
                }
                else
                {
                    $this->resultapi(0,'You Cannot Change Price After Product Confirmation', false);
                }
            }
            else
            {
                $this->resultapi(0,'يرجى تغيير السعر أولا.', true);
            }
        }
        else
        {
            $sellerReplied = BrodRequest::find($request->request_id);            
            if(count($sellerReplied) > 0)
            {   
                $newResponse = new BrodResponse;
                $newResponse->customer_id           = $request->customer_id;
                $newResponse->seller_id             = $request->seller_id;
                $newResponse->request_id            = $request->request_id;
                $newResponse->price                 = $request->price;
                $newResponse->price_updated         = 0;
                $newResponse->save();
                
                $sellerReplied->is_seller_replied = 1;
                $sellerReplied->status = 2;
                $sellerReplied->save();

                $this->resultapi(1,'Your Product Response Send Sucessfully.', true);
            }
            else
            {
                $this->resultapi(0,'Request Details Not Found.', true);
            }     
        }      
    }

    public function getUpdateResponseBySeller(Request $request)
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
                    else if($respDetail->removed_by_user == 1)
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
                    $allResponse = array();
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

    public function getProductConfirmedBySeller(Request $request)
    {  
        if($request->res_id) 
        {
            $prodConfirmation = BrodResponse::productConfirmedBySeller($request->res_id);
            
            if($prodConfirmation === 1)
            {
                $this->resultapi('1','Product Confirmed Sucessfully.', true);
            }
            else if($prodConfirmation === 2)
            {
                $this->resultapi('0','Buyer Not confirmed, Wait For Buyer Confirmation.', false);
            }
            else if($prodConfirmation === 3)
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
            $this->resultapi('0','Invalid Response Id.', false);
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

    public function getRequestDeatils(Request $request)
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
