<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Api\User;
use App\UserProfiles;
use App\Api\Payment; 
use App\Skills;
use App\Category;
use App\Api\JobDetail;
use App\Api\JobInvitation;
use App\Api\JobMessage;
use App\JobProposal;
use App\Api\JobQuestion;
use App\EmailTemplates; 
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\Validator; 
use Hash;
use Illuminate\Support\Facades\URL;
use Mail; 

use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

use File;
use Image;

class ApiClientController extends Controller {
    private $req;
    private $user;
    private $jwtAuth;
    public function __construct(Request $request, User $user, ResponseFactory $responseFactory, JWTAuth $jwtAuth)
    {
        header('Content-Type: application/json');
        $this->user = $user;
        $this->jwtAuth = $jwtAuth;
        $this->req = $request;
        $this->res = $responseFactory;
    }
    
    public function postClientData(Request $request){ 
    
        $users = DB::table('users')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->leftJoin('payments', 'users.id', '=', 'payments.user_id')
            ->leftJoin('qualifications', 'user_profiles.qualifications', '=', 'qualifications.id')
            ->select('users.*', 'user_profiles.*', 'payments.*','qualifications.name as qualificationsType','users.id as id')
            ->where('users.id',$request->id)
            ->first();
           
        if(count($users)>0){ 
            $this->resultapi('1','Profile Details Updated Sucessfully.',$users);
        }
    }

    // client document uploads for documents
    public function file_upload(Request $request){

        $profileImage = ""; 
        if($request->hasFile('file')) {
            $file = $request->file('file');
            foreach ($file  as $key => $value) {
                $img_name = $value->getClientOriginalName(); 

                $timestamp = time().  uniqid();
                $name = $timestamp. '-' .$value->getClientOriginalName();
                $profileImage = $name;
            
                $value->move(public_path().'/asset/User/portfolio/', $name);
                $file_data[] = $profileImage;

                $path = public_path().'/asset/User/portfolio/';
                $destinationPath = public_path('/asset/User/portfolio/thumb');
                $img = Image::make($path.$name);
                $img->resize(120, 120, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$name);

            }
            $result_data = json_encode($file_data);
            $this->resultapi('1','Documents Updated Sucessfully.',$result_data);  
        }else{
            $result_data = "";
            $this->resultapi('1','Documents Updated Sucessfully.',$result_data); 
        }
            
    }
    // profile image upload for client profile image
    public function profile_upload(Request $request){
       
       $data = ($request->all()); 
        $profileImage = ""; 
        if($request->hasFile('profile_file')) {
            $file = $request->file('profile_file');
            foreach ($file  as $key => $value) {
                $img_name = $value->getClientOriginalName(); 

                $timestamp = time().  uniqid();
                $name = $timestamp. '-' .$value->getClientOriginalName();
                $profileImage = $name;
            
                $value->move(public_path().'/asset/User/Profile', $name);
                $file_data[] = $profileImage;

                $path = public_path().'/asset/User/Profile/';
                $destinationPath = public_path('/asset/User/Profile/thumb');
                $img = Image::make($path.$name);
                $img->resize(120, 120, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$name);
                //File::delete(public_path().'/asset/userImages/'.$user->profile_pic);               
            }

            $result_data = json_encode($file_data);
         
            $this->resultapi('1','Profile Details Updated Sucessfully.',$result_data);  
            
        }else{
            $result_data = "";
            $this->resultapi('1','Profile Details Updated Sucessfully.',$result_data); 
        }
            
    }

    
    public function postUpdateClient(Request $request){ 
           // $user = User::find($request->user_id);   
            $user_array = $request->user;  
            $userId = $user_array['id'];

            $user = User::find($userId);
           

            if($request->hasFile('profile_file')) {

                $file[] = $request->file('profile_file');

                foreach ($file  as $key => $value) { 
                    $img_name = $value->getClientOriginalName();  
                    $timestamp = time().  uniqid();
                    $name = $timestamp. '-' .$value->getClientOriginalName();
                    $profileImage = $name;
                
                    $value->move(public_path().'/asset/User/Profile', $name);
                    $file_data = $profileImage;

                    $path = public_path().'/asset/User/Profile/';
                    $destinationPath = public_path('/asset/User/Profile/thumb');
                    $img = Image::make($path.$name);
                    $img->resize(120, 120, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$name);
            
                    $userProfile_data =  User::where('id',$userId)->first();
                }
                 
                $result_data = $file_data; 
                if(count($userProfile_data)>0 && $file_data!=""){
                    $array_update = array("profile_image"=>$result_data);
                    $userProfilepic_update = User::where('id',$userId)->update($array_update); 
                } 
                
            } 
             
            if($request->user && count($request->user))
            {
                $validator = Validator::make($request->user, [
                    'firstname'                  =>'required|max:100',
                    'lastname'                   =>'required|max:100',
                    'phone_number'               =>'required',
                    'street'                     =>'required',
                    'zipcode'                    =>'required|max:6',
                    'profile_description'        =>'required|max:1000',
                    /*'job_title'                  =>'required', */
                    'stateId'                    =>'required',
                    'locationId'                 =>'required',
                    'countryId'                  =>'required', 
                    /*'vat_number'                 =>'required',
                    'commercial_register_number' =>'required',*/
                    //'qualifications'             =>'required' 
                   /* 'cc_name'                    =>'required',
                    'cc_cvv'                     =>'required',
                    'cc_number'                  =>'required', */
                    
                ]);
                if ($validator->fails()) { 
                     $this->resultapi('0',$validator->errors()->all(),null);
                }
                else
                {  
                    if(isset($user_array['address_same']) && $user_array['address_same'] ==1)
                    {
                        $invoice_address  = $user_array['invoice_address'];
                        $delivery_address = $user_array['invoice_address'];

                        $invoice_zipcode  = $user_array['invoice_zipcode'];
                        $delivery_zipcode = $user_array['invoice_zipcode'];
                    }
                    else
                    {
                        $invoice_address  = $user_array['invoice_address'];
                        $delivery_address = $user_array['delivery_address'];

                        $invoice_zipcode  = $user_array['invoice_zipcode'];
                        $delivery_zipcode = $user_array['delivery_zipcode'];
                    }

                    if($user && $user->status === "Active")
                    {

                        $user->firstname                    = $user_array['firstname'];
                        $user->lastname                     = $user_array['lastname']; 
                        $user->phone_number                 = $user_array['phone_number'];
                        $user->is_client_profile_updated    = 1;
                        $user->status                       = 'Active';                      
                        $user->is_company                   = 'No'; 
                        $user->update(); 
                                         
                        $userProfile_array = array(  
                            "gender"                     => $user_array['gender'],
                            "countryId"                  => $user_array['countryId'],
                            "stateId"                    => $user_array['stateId'],
                            "locationId"                 => $user_array['locationId'],
                            "street"                     => $user_array['street'],
                            "birth_date"                 => $user_array['birth_date'],
                           /* "qualifications"             => $user_array['qualifications'],*/                
                            "language_id"                => 2,
                           /* "job_title"                  => $user_array['job_title'],*/
                            "zipcode"                    => $user_array['zipcode'], 
                            "street"                     => $user_array['street'], 
                            "profile_description"        => $user_array['profile_description'],
                            /*"vat_number"                 => $user_array['vat_number'],
                            "commercial_register_number" => $user_array['commercial_register_number'],*/
                            "invoice_address"            => $invoice_address,
                            "delivery_address"           => $delivery_address,
                            "invoice_zipcode"            => $invoice_zipcode,
                            "delivery_zipcode"           => $delivery_zipcode,
                        );
                        /*if(!empty($request->profile_image)){
                            $user_profile_pic = array("portfolio_images" => $request->portfolio_images);
                            $user_pic = User::where('id',$userId)->update($user_profile_pic);
                        }*/

                        /*if(!empty($user_array['portfolio_images'])){
                            $userProfile_array['portfolio_images'] = $user_array['portfolio_images'];
                            //print_r($request->exist_images);exit; 
                            if(!empty($user_array['exist_images'])){
                                 
                                if(!is_array($user_array['portfolio_images'])){
                                    $portfolio_images = json_decode($user_array['portfolio_images'],true);
                                }else{
                                    $portfolio_images = $user_array['portfolio_images'];
                                } 
                                $userProfile_array['portfolio_images'] = array_merge($portfolio_images,$user_array['exist_images']);
                            }
                            $userProfile_array['portfolio_images'] = json_encode($userProfile_array['portfolio_images']);
                        }
                          */
                      
                        $userProfile_update = UserProfiles::where('user_id',$userId)->update($userProfile_array);  

                        /* $Payments = Payment::where('user_id', $userId)->first();    
                        
                        if(count($Payments)>0){
                            $Payment_array = array(  
                                "cc_cvv" => $user_array['cc_cvv'],
                                "cc_name"=> $user_array['cc_name'],
                                "cc_no"  => $user_array['cc_no']  
                            ); 
                            $Payment_detail =  Payment::where('user_id',$userId)->update($Payment_array);   
                        }else{
                            $Payment = new Payment();  
                            $Payment->user_id    = $userId;
                            $Payment->cc_cvv     = $user_array['cc_cvv'];
                            $Payment->cc_name    = $user_array['cc_name'];
                            $Payment->cc_no  = $user_array['cc_no']; 
                            $Payment->save();    
                        }  */
                        $userProfile = DB::table('users')
                        ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                        //->join('payments', 'users.id', '=', 'payments.user_id')
                        ->select('users.*', 'user_profiles.*')
                        ->where('users.id',$userId)->get();

                        // $userProfile =  UserProfiles::where('user_id',$userId)->first();   
                        $userdata = $userProfile[0];
                        //$userdata['profile_image'] =  User::select('profile_image')->where('id',$userId)->first(); 
                        $this->resultapi('1','Profile Details Updated Sucessfully.',$userdata); 

                    }
                    else
                    {
                        $this->resultapi('0','Some Problem in Profile Update.',null);
                    }

                }
            }
    }

    public function remove_file_image(Request $request){
        
        if(!empty($request->all())){
            $userId = $request->user_id;
            $image_name = $request->image_name;
            $userProfile_image =  UserProfiles::select('portfolio_images')->where('user_id',$userId)->first();   
            $images_array = json_decode($userProfile_image['portfolio_images'],true);
            foreach ($images_array as $key => $value) {
                if($image_name == $value){ 
                    $path = public_path().'/asset/User/portfolio/';
                    $destinationPath = public_path('/asset/User/portfolio/thumb/');
                    if(file_exists($path.$value) && file_exists($destinationPath.$value)){ 
                        File::delete($path.$value);
                        File::delete($destinationPath.$value);
                    }
                    //pr($images_array);exit;
                    $newimages_array = array_diff($images_array,array($value));
                    //echo public_path().'/asset/userImages/'.$value;exit; 
                }
            }
            $portfolio_images  = array('portfolio_images'=> json_encode(array_values($newimages_array))); 
            $userProfile_image = UserProfiles::where('user_id',$userId)->update($portfolio_images); 

            $this->resultapi('1','Images deleted sucessfully',$portfolio_images); 
             
        }
    }

    // Job Documents For upload job posting
    public function jobDocuments(Request $request){  
        //pr($request->all());exit;
        $profileImage = ""; 
        if($request->hasFile('file')) {
            $file = $request->file('file');
            foreach ($file  as $key => $value) {
            $img_name = $value->getClientOriginalName(); 

            $timestamp = time().  uniqid();
            $name = $timestamp. '-' .$value->getClientOriginalName();
            $profileImage = $name;

            $path = public_path().'/asset/JobDetails/docs'; 
            if(!is_dir($path)){
                File::makeDirectory($path, 0755, true, true);
            } 

            $value->move($path, $name);
            $file_data[] = $profileImage; 
            
            }
            $result_data = json_encode($file_data);
            $this->resultapi('1','Documents Updated Sucessfully',$result_data);  
        }else{
            $result_data = "";
            $this->resultapi('1','Documents Updated Sucessfully',$result_data); 
        }
    }

    // Job Documents For upload job posting
    public function jobPic(Request $request){  
        $profileImage = ""; 
        if($request->hasFile('picfiles')) {
            $file = $request->file('picfiles');
            foreach ($file  as $key => $value) {
            $img_name = $value->getClientOriginalName(); 

            $timestamp = time().  uniqid();
            $name = $timestamp. '-' .$value->getClientOriginalName();
            $profileImage = $name;

            $path = public_path().'/asset/JobDetails/images'; 
            if(!is_dir($path)){
                File::makeDirectory($path, 0755, true, true);
            }
            $destinationPath = public_path().'/asset/JobDetails/images/thumb';
            if(!is_dir($destinationPath)){
                File::makeDirectory($destinationPath, 0755, true, true);
            }
        
            $value->move($path, $name);
            $file_data[] = $profileImage; 
            
            $img = Image::make($path.'/'.$name); 
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$name);
           
            }
            $result_data = json_encode($file_data);
            $this->resultapi('1','Job Pics Updated Sucessfully',$result_data);  
        }else{
            $result_data = "";
            $this->resultapi('1','Job Pics Updated Sucessfully',$result_data); 
        }
    }

    public function postPostProject(Request $request){
        $job_array      = $request->user;
        $job_crop_image = $request->croppedImage;
        $userId         = $job_array['user_id'];

        if($request->user && $request->croppedImage)
        {
            $validator = Validator::make($request->user, [ 
                'job_title'              => 'required',
                'job_skills'             => 'required', 
                'job_category'           => 'required',
                'job_availble_for'       => 'required', 
                'job_description'        => 'required', 
                'job_cost'               => 'required', 
                'job_location'           => 'required',
                'terms_conditions'       => 'required',
                'job_finish_time'        => 'required',
            ]);

            if ($validator->fails()) 
            {                
                $this->resultapi('0',$validator->errors()->all(),null);
            }
            else
            { 
                $imageDecode = $this->uploadCroppedImage($job_crop_image);
                $jobDocs = "";
                if(!empty($job_array['portfolio_images']))
                {                    
                    $jobDocs = json_encode($job_array['portfolio_images']);
                }

                $job = new JobDetail; 
                $job->hired_user_id         = "";
                $job->user_id               = $userId;
                $job->status                = "Active";
                $job->job_title             = $job_array['job_title'];
                $job->job_subtitle          = $job_array['job_subtitle'] ? $job_array['job_subtitle'] : "";
                $job->job_description       = $job_array['job_description']; 
                $job->job_cost              = $job_array['job_cost'];
                $job->job_length            = 10; /* not using anywhere dummy purpose only */  
                $job->job_location          = $job_array['job_location'];
                $job->job_stage             = "Starting";
                $job->job_keywords          = $job_array['job_title'];
                $job->job_availble_for      = $job_array['job_availble_for'];
                $job->job_submittion_date   = $job_array['job_finish_time'];   
                $job->terms_conditions      = $job_array['terms_conditions']; 
                $job->job_category          = implode(",",$job_array['job_category']);
                $job->job_skills            = implode(",",$job_array['job_skills']); 
                $job->job_documents         = $jobDocs;
                //$job->job_stattime          = date('Y-m-d');
                $job->job_images            = $imageDecode; 

                               
               /*  if(isset($job_crop_image)){
                    
                    $job->job_images = json_encode($imageDecode); 
                }*/

                $job->save();

                $job_details = "";
                 
                $this->resultapi('1','Job Posted Sucessfully.',$job_details);
            }
        }
        else
        {
            $job_details = "";
            $this->resultapi('0','Job Image Required.',$job_details);  
        }
    }
    public function uploadCroppedImage($photo){
       $fileName = '';
        try {
            if(strlen($photo) > 128) {
                list($ext, $data)   = explode(';', $photo);
                list(, $data)       = explode(',', $data);
                $data = base64_decode($data);
                $path = public_path().'/asset/JobDetails/images/';
               
                $fileName = 'crop_'.mt_rand().time().'.png';
                file_put_contents($path.$fileName, $data);

                $thumbPath = public_path().'/asset/JobDetails/images/thumb/';
                $img = Image::make($path.$fileName); 
                $img->resize(120, 120)->save($thumbPath.'/'.$fileName);
            }
        }
        catch (\Exception $e) {
            $msg = $e;
        }
        return $fileName;
    }
    public function getMyPostedProjects(Request $request){
       
        if($request->uid)
        {
            $query =  DB::table('job_details');
            $query->select('id','job_title','job_stage',"created_at","job_cost","job_images","hired_user_id");
            if($request->uid) $query->where('user_id', $request->uid);
            if($request->hired_user_id == true) $query->where('hired_user_id',0);  
            $results = $query->get(); 

            $this->resultapi('1','List Fetched Sucessfully.',$results);
        }
        else
        {
            $results = "";
            $this->resultapi('0','Fails to fetch list.',$results);
        }
    }


    public function getProjectProposalsSendByFreelancer(Request $request){

        $jobProposalsByUser = DB::table('job_proposals')       
        ->where('job_proposals.job_id',$request->pid)
        ->where('job_proposals.status','!=','3')        
        ->join('job_details', 'job_proposals.job_id', '=', 'job_details.id')
        ->join('user_profiles', 'job_proposals.user_id', '=', 'user_profiles.user_id')
        ->join('users', 'job_proposals.user_id', '=', 'users.id')
        ->join('cities', 'user_profiles.locationId', '=', 'cities.id') 
        ->select('job_proposals.*','job_details.job_title','user_profiles.job_title','user_profiles.hourly_rate','user_profiles.skills','user_profiles.profile_description','users.firstname','users.lastname','users.profile_image','cities.name','job_details.hired_user_id')                         
        ->orderBy('job_proposals.job_id', 'desc')                    
        ->get();

        return $jobProposalsByUser;

    }

    public function isInvitationSend($request){
        $isInvitationSend = JobInvitation::where('job_id',$request->projectId)
        ->where('user_id', $request->userId)
        ->where('client_id',$request->clientId)
        ->first();

        return count($isInvitationSend);

    }

    public function postSentProjectInvitationToFreelancer(Request $request){
        
        if(!empty($request->all()))
        {
            $validator = Validator::make($request->all(), [ 
                'clientId'              => 'required|integer',
                'userId'                => 'required|integer',
                'projectId'             => 'required|integer', 
                'proposalDesc'          => 'required|max:500',               
            ]);

            if ($validator->fails()) 
            {
                $this->resultapi('0','Fail',$validator->errors()->all());
            }
            else
            {                
                $userId = $request->input('clientId');
                if($userId && $userId !== "")
                {
                    $user = User::find($userId);
                    
                    $isProfileUpdated   = $user->is_client_profile_updated;
                    $isPaymentUpdated   = $user->is_client_payment_updated;
                    $emailVerified      = $user->email_verified;
                    $currentProfileView = $user->usertype;

                    if($isProfileUpdated == 1)
                    { 
                        if($emailVerified == "Yes")
                        {
                            if($currentProfileView === "Client")
                            {
                                if($isPaymentUpdated == 1)
                                {                                  
                                    $isInvitationSend = $this->isInvitationSend($request);
                                    if($isInvitationSend === 0)
                                    {
                                        $JobInvitations = new JobInvitation;      
                                        $JobInvitations->client_id        = $request->clientId;
                                        $JobInvitations->user_id          = $request->userId;
                                        $JobInvitations->job_id           = $request->projectId;
                                        $JobInvitations->inv_description  = $request->proposalDesc;
                                        $JobInvitations->inv_status       = "Pending";                                   
                                        $JobInvitations->inv_attachemnts  = "";                            
                                        //$JobInvitations->save(); 
                                        if($JobInvitations->save())
                                        {
                                            /*JobMessage::sendJobMessage($request->clientId, $request->userId, $request->projectId, $request->proposalDesc,null, 1, 0);*/
                                            $mailData = array(
                                                'clientId'          => $request->clientId,
                                                'userId'            => $request->userId,
                                                'projectId'         => $request->projectId,
                                                'inv_description'   => $request->proposalDesc,
                                                'inv_status'        => "Pending",
                                                'inv_attachemnts'   => "",
                                                'template'          => 'new-project-invtation',
                                                'search'            => "a",
                                                'replace'           => "a"        
                                            );

                                            $sendStatus = EmailTemplates::SendEmail($mailData); 

                                            $this->resultapi('1','Job Invitation Send Sucessfully.',1);
                                        }
                                        else
                                        {
                                            $this->resultapi('0','Problem In Invitation Send.',null);
                                        }
                                    } 
                                    else
                                    {
                                        $this->resultapi('2','Cannot Resend Invitation.',1);
                                    }                   
                                }
                                else
                                {
                                    $this->resultapi('2','Please update Payment Details.',null);
                                }
                            }
                            else
                            {
                                $this->resultapi('2','Please Change your Profile view as a Client.',null);
                            }
                        }
                        else
                        {
                            $this->resultapi('2','Your Email Is not Verified.',null);
                        }
                    }
                    else
                    {   
                        $this->resultapi('0','Please Update Your Profile.',null);
                    }            
                }
                else
                {
                    $this->resultapi('0','User details not found or session expired please try again.',null); 
                } 
            }
        } 
        else
        {
            $this->resultapi('0','Invation Details not forund.',false);
        } 

    }

    public function getProjectStatusByClientId(Request $request){

        if($request->uid && $request->pid)
        {
            
            $projectStatus['JobDetails'] = DB::table('job_details')
            ->where('job_details.id',$request->pid)
            ->where('job_details.user_id',$request->uid)
            ->select('id','user_id','job_title','job_length','job_cost','final_job_cost','job_stage','hired_user_id','job_stattime','job_endtime','job_description','job_submittion_date','is_payment_relased','proj_close_noti_freelancer','proj_close_noti_client') 
            ->first();

            if($projectStatus['JobDetails']->hired_user_id)
            {
               $hiredUserId = $projectStatus['JobDetails']->hired_user_id;
               $projectStatus['hiredByClient'] = DB::table('users')
                ->where('users.id',$hiredUserId)
                ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->join('cities', 'user_profiles.locationId', '=', 'cities.id')
                ->select('user_profiles.job_title','user_profiles.hourly_rate','user_profiles.skills','user_profiles.profile_description','users.firstname','users.lastname','users.profile_image','cities.name')
                ->get();
            }
            else
            {                
                $projectStatus['hiredByClient'] = array();
            }

            $projectStatus['Invited'] = DB::table('job_invitations')           
            ->join('user_profiles', 'job_invitations.user_id', '=', 'user_profiles.user_id')
            ->join('users', 'job_invitations.user_id', '=', 'users.id')
            ->join('cities', 'user_profiles.locationId', '=', 'cities.id')
            ->join('job_details', 'job_details.id', '=', 'job_invitations.job_id')
            ->select('job_invitations.*', 'user_profiles.job_title','user_profiles.hourly_rate','user_profiles.skills','user_profiles.profile_description','users.firstname','users.lastname','users.profile_image','cities.name','job_details.job_cost','job_details.hired_user_id')
            ->where('job_invitations.job_id',$request->pid)
            ->where('job_invitations.client_id',$request->uid)                    
            ->orderBy('job_invitations.job_id', 'desc')                    
            ->get();

            $projectStatus['AppliedByUser'] = $this->getProjectProposalsSendByFreelancer($request);

            $projectStatus['QuesAns'] = JobQuestion::getAllQuesAns($request->pid);

            $this->resultapi('1','success',$projectStatus);
        }
        else
        {
            $$projectStatus = "";
            $this->resultapi('0','fails',$projectStatus);
        }
    }

    public function sendFinalProposal(Request $request)
    {
        $finalProposal = JobProposal::sendFinalProposalToFreelancer($request);

        if($finalProposal)
        {
            $this->resultapi('1','Proposal Send Successfully.',$finalProposal);
        }
        else
        {
            $this->resultapi('0','Some Problem in send Proposal, Please try again.',$finalProposal);
        }
    }

     /*
    ** Job Details page
    */

    public function getProjectDetailsById(Request $request) {

        if($request->all() && count($request->all()) > 0)
        {
            $jobDetails = DB::table('job_details')
            ->where('status','Active')
            ->where('id',$request->pid)           
            ->get(); 
             
            if(count($jobDetails[0]) > 0)
            { 
                $skills     = $jobDetails[0]->job_skills;
                $category   = $jobDetails[0]->job_category;
                $images     = $jobDetails[0]->job_images;
                $documents  = $jobDetails[0]->job_documents;
                $locationId = $jobDetails[0]->job_location;
                  
                $imagesDecodeJson       = json_decode($images, TRUE);
                $documentsDecodeJson    = json_decode($documents, TRUE);
                $skillsDecodeJson       = explode(",", $skills);
                $categoryDecodeJson     = explode(",", $category);
                
                $allSkills = Skills::whereIn('id',$skillsDecodeJson)
                ->select('skill')
                ->where('status', 'Active')
                ->get();
                
                $allCategories = Category::whereIn('id', $categoryDecodeJson)
                ->select('name')
                ->where('status', 'Active')
                ->get(); 
                
                $jobDetails['skills']         = $allSkills;
                $jobDetails['categories']     = $allCategories;
                $jobDetails['job_images']     = $imagesDecodeJson;
                $jobDetails['job_documents']  = $documentsDecodeJson;

                $location = DB::table('cities')->where('id',$locationId)->select('name')->first();
                //print_r($location->name);
                $jobDetails['user_location'] = $location->name;

                $jobDetails['appliedProposals']  = $appliedProposal = DB::table('job_proposals')
                ->where('job_proposals.job_id',$request->pid)    
                ->join('users', 'job_proposals.user_id', '=', 'users.id')               
                ->select('job_proposals.id as prop_id','job_proposals.prop_price','job_proposals.user_id','job_proposals.updated_at','job_proposals.status','users.lastname','users.firstname','users.id')
                ->orderBy('job_proposals.updated_at','desc')                    
                ->get();

                $jobDetails['job_questions']  = JobQuestion::getAllQuesAns($request->pid); 
                $this->resultapi('1','Projects Found.', $jobDetails);
            }
            else
            {                
                $this->resultapi('0','No Projects Found.', $jobDetails);
            }
        }
    }

    public function getProjectReviewById(Request $request) {  
        if($request->pid){  
            $jobReviewDetails = DB::table('job_protfolios') 
            ->where('job_protfolios.pid',$request->pid)    
            ->join('users as freelancerUser', 'job_protfolios.fid', '=', 'freelancerUser.id')
            ->join('users as clientUser', 'job_protfolios.cid', '=', 'clientUser.id')  
            ->select('freelancerUser.firstname as f_firstname','freelancerUser.lastname as f_lastname','clientUser.firstname as c_firstname','clientUser.lastname as c_lastname','job_protfolios.id as job_protfolios_id','job_protfolios.*')            
            ->get(); 

            $jobDetails['jobReviewDetails'] = $jobReviewDetails;
            $this->resultapi('1','Projects Found.', $jobDetails);
        }else{                
            $this->resultapi('0','No Projects Found.', $jobDetails);
        } 
    }

    public function resultapi($status,$message,$result = array()) {
            $finalArray['STATUS'] = $status;
            $finalArray['MESSAGE'] = $message;
            $finalArray['RESULT'] = $result;
            echo json_encode($finalArray);
            die;
    }
}