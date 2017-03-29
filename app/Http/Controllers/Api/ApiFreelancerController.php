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
use App\JobProposal;
use App\Skills;
use App\Category;
use App\Api\JobDetail;
use App\Api\JobMessage;
use App\Api\JobProtfolio;
use App\Api\JobInvitation;
use App\Api\JobQuestion;

use Illuminate\Support\Facades\Hash;
use App\EmailTemplates; 
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\URL;
use Mail; 

use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

use File;
use Image;

class ApiFreelancerController extends Controller {
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

    public function postChangePasssword(Request $request){

        if($request->user_id && $request->all() && count($request->all()) > 0)
        {      
            $validator = Validator::make($request->all(), [

                'user_id'           =>'required|numeric',
                'old_password'      =>'required|max:255|min:6',
                'password'          =>'required|max:255|min:6',
                'confirm_password'  =>'required|max:255|min:6|same:password',
                
            ]);
             
            if ($validator->fails()) 
            {
                $this->resultapi('0',$validator->errors()->all(), null);
            }
            else
            {
                $userId = $request->user_id;
                $user = User::find($userId);
                
                if(Hash::check($request->old_password, $user->password))
                {                    
                    $user->password  =  bcrypt($request->password);
                    $user->save();

                    $this->resultapi('1','Password Updated Sucessfully. Please Login.',null);                   
                }
                else
                {
                    $this->resultapi('0','Wrong Old password.',null);
                }
            }
        }
        else
        {
            $this->resultapi('0','Operation not not possible',null);
        }
    }

    public function postUpdateCompany(Request $request){
        
        if($request->user_id && $request->all() && count($request->all()) > 0)
        {  
            $validator = Validator::make($request->all(), [

                'user_id'                    =>'required|numeric',
                'company_no_of_employer'     =>'required|max:5',
                'company_type'               =>'required',
                'vat_number'                 =>'required|max:25',
                'commercial_register_number' =>'required|max:25',
                'company_address'            =>'required|max:180',
            ]);
             
            if ($validator->fails()) 
            {
                $this->resultapi('0',$validator->errors()->all(), null);
            }
            else
            {
                $userId = $request->user_id;
                $user = User::find($userId);

                if($user->is_profile_updated == 1)
                {
                    $userProfile = UserProfiles::where("user_id","=", $userId)->first();
                    $userProfile->company_no_of_employer        = $request->company_no_of_employer;
                    $userProfile->company_type                  = $request->company_type;
                    $userProfile->vat_number                    = $request->vat_number;
                    $userProfile->commercial_register_number    = $request->commercial_register_number;
                    $userProfile->company_address               = $request->company_address;                    
                    $user->is_company                           = "Yes";

                    $user->save();
                    $userProfile->save(); 
                 
                    if($userProfile->save() &&  $user->save())
                    {
                        $this->resultapi('1','Company Details Updated Sucessfully.',$userId);
                    }
                    else
                    {
                        $this->resultapi('0','Company Details Not Updated.',$userId);
                    }
                }
                else
                {
                    $this->resultapi('0','You must have to update your Profile Details First.',$userId);
                } 
            }                 
        }
        else
        {
            $this->resultapi('0','Company Details Not Updated.',$userId);
        }
    }
    
    // client document uploads for documents
    public function file_upload(Request $request){

        $profileImage = ""; 
        if($request->hasFile('file'))
        {
            $file = $request->file('file');
            foreach ($file  as $key => $value) {
            $img_name = $value->getClientOriginalName(); 

            $timestamp = time().  uniqid();
            $name = $timestamp. '-' .$value->getClientOriginalName();
            $profileImage = $name;

            $path = public_path().'/asset/User/portfolio/'; 
            if(!is_dir($path)){
                File::makeDirectory($path, 0755, true, true);
            }
            $paththumb = public_path().'/asset/User/portfolio/thumb/';
            if(!is_dir($paththumb)){
                File::makeDirectory($paththumb, 0755, true, true);
            }

            $value->move(public_path().'/asset/User/portfolio/', $name);
            $file_data[] = $profileImage; 

            $path = public_path().'/asset/User/portfolio/';
            $destinationPath = public_path('/asset/User/portfolio/thumb/');
            $img = Image::make($path.$name);
            $img->resize(120, 120, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$name);
            }
            $result_data = json_encode($file_data);
            $this->resultapi('1','Documents Updated Sucessfully',$result_data);  
        }
        else
        {
            $result_data = "";
            $this->resultapi('1','Documents Updated Sucessfully',$result_data); 
        }
            
    }
    
    public function postUpdateFreelancer(Request $request){

            $user_array = $request->user;
            $userId = $user_array['user_id']; 

            if($request->hasFile('profile_file')) { 

                $file[] = $request->file('profile_file'); 
                foreach ($file  as $key => $value) { 
                    $img_name = $value->getClientOriginalName();  
                    $timestamp = time().  uniqid();
                    $name = $timestamp. '-' .$value->getClientOriginalName();
                    $profileImage = $name;
                    
                    //$path = public_path().'/asset/Freelancer/'; 
                    $path = config('constant.FreelancerAssetPath');
                    if(!is_dir($path)){
                        File::makeDirectory($path, 0755, true, true);
                    }
                    
                    $paththumb = public_path().'/asset/User/thumb';
                    if(!is_dir($paththumb)){
                        File::makeDirectory($paththumb, 0755, true, true);
                    }
                    
                    $pathProfile = config('constant.FreelancerAssetPathProfile');
                    if(!is_dir($pathProfile)){
                        File::makeDirectory($pathProfile, 0755, true, true);
                    }
                    $pathProfilethumb = public_path().'/asset/Freelancer/Profile/thumb';
                    if(!is_dir($pathProfilethumb)){
                        File::makeDirectory($pathProfilethumb, 0755, true, true);
                    }

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
                    'firstname'                  =>'required|max:30',
                    'lastname'                   =>'required|max:300',
                    'phone_number'               =>'required|max:12',
                    'street'                     =>'required',
                    'zipcode'                    =>'required|max:5',
                    'profile_description'        =>'required|max:1000',
                    'job_title'                  =>'required', 
                    'stateId'                    =>'required',
                    'locationId'                 =>'required',
                    'countryId'                  =>'required',  
                    'hourly_rate'                =>'required',
                    'job_skills'                 =>'required',
                    'gender'                     =>'required',
                    'sva_number'                 =>'required_without:uid_number',
                    'uid_number'                 =>'required_without:sva_number'
                ]);

                if ($validator->fails()) { 
                    $this->resultapi('0','Fail',$validator->errors()->all());
                }
                else
                {  
                    $user = User::find($userId);
                    //pr($user);exit;
                    if($user && $user->status === "Active")
                    {
                        if(isset($user_array['address_same']) && $user_array['address_same'] == 1)
                        {
                            $invoice_address  = $user_array['invoice_address'];
                            $delivery_address = $user_array['invoice_address'];

                            $invoice_zipcode  = $user_array['invoice_zipcode'];
                            $delivery_zipcode = $user_array['invoice_zipcode'] ? $user_array['invoice_zipcode'] : "";
                        }
                        else
                        {
                            $invoice_address  = $user_array['invoice_address'];
                            $delivery_address = $user_array['delivery_address'];

                            $invoice_zipcode  = $user_array['invoice_zipcode'];
                            $delivery_zipcode = $user_array['delivery_zipcode'] ? $user_array['delivery_zipcode'] : ""; 
                        }

                        $user->firstname            = $user_array['firstname'];
                        $user->lastname             = $user_array['lastname']; 
                        $user->phone_number         = $user_array['phone_number'];
                        $user->status               = 'Active';
                        $user->usertype             = $user_array['usertype'];
                        $user->is_profile_updated   = 1;
                        $user->is_company           = !empty($user_array['company'] ==="Yes")?'Yes':'No'; 
                        $user->update(); 
                                         
                        $userProfile_array = array(  
                            "gender"                     => $user_array['gender'],
                            "countryId"                  => $user_array['countryId'],
                            "stateId"                    => $user_array['stateId'],
                            "locationId"                 => $user_array['locationId'],
                            "street"                     => $user_array['street'],
                            "birth_date"                 => $user_array['birth_date'],
                            "qualifications"             => $user_array['qualifications'],                
                            "language_id"                => 2,
                            "skills"                     => implode(",", $user_array['job_skills']),
                            "job_title"                  => $user_array['job_title'],
                            "zipcode"                    => $user_array['zipcode'], 
                            "street"                     => $user_array['street'], 
                            "profile_description"        => $user_array['profile_description'],  
                            "invoice_address"            => $invoice_address,
                            "delivery_address"           => $delivery_address,
                            "invoice_zipcode"            => $invoice_zipcode,
                            "delivery_zipcode"           => $delivery_zipcode,
                            "website"                    => $user_array['website'],
                            "videos"                     => $user_array['videos'],                    
                            "school_gratuation"          => $user_array['school_gratuation'],
                            "hourly_rate"                => $user_array['hourly_rate'],
                            "sva_number"                 =>!empty($user_array['sva_number'])?$user_array['sva_number']:"",
                            "uid_number"                 =>!empty($user_array['uid_number'])?$user_array['uid_number']:""
                        );
                 
                        if(!empty($user_array['portfolio_images'])){
                            $userProfile_array['portfolio_images'] = $user_array['portfolio_images'];                           
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
                        $userProfile_update = UserProfiles::where('user_id',$userId)->update($userProfile_array); 
                    
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
                        $this->resultapi('0','Some Problem in Profile Update',null);
                    }

                }
            }
      
        }


    public function getFreelancerDetails(Request $request) {
       
        $userId = $request->input('user_id');
        if($userId && $userId != "")
        {
            $user = DB::table('users')
            ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->join('payments', 'users.id', '=', 'payments.user_id')
            ->select('users.*', 'user_profiles.*', 'payments.*')
            ->where('users.id',$userId)
            ->first();

            $this->resultapi('1','Profile Details Found.', $user);
        }

    }

    public function getAllProjects(Request $request) {
        
        $countFilter =  count($request->all());

        if($countFilter && $countFilter > 0)
        {

        }
        else
        {
           $results = DB::table('job_details')            
            ->select('id','job_title','job_subtitle','job_skills','job_images','job_documents','job_subtitle','job_cost','job_description','job_availble_for','job_location','job_stage')     
            ->orderBy('created_at', 'desc')
            ->where('status','Active')
            ->get();
           
            foreach ($results as $key => $result) {
                
                $skillsDecodeJson = explode(",", $result->job_skills);
                $results[$key]->job_images = explode(",", $result->job_images);
      
                if($skillsDecodeJson && count($skillsDecodeJson) > 0)
                {
                     $results[$key]->skill_list = Skills::whereIn('id',$skillsDecodeJson)
                    ->select('skill')
                    ->where('status', 'Active')
                    ->get();
                }
                
            }

            if(count($results) > 0)
            {
                $this->resultapi('1','Projects Found.', $results);
            }
            else
            {                
                $this->resultapi('0','No Projects Found.', $results);
            }
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

            if(count($jobDetails) > 0)
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

                $this->resultapi('1','Projects Found.', $jobDetails);
            }
            else
            {                
                $this->resultapi('0','No Projects Found.', $jobDetails);
            }
        }
    }

    public function getSkillsByArray(Request $request){ 
        
        if($request->skillsArray && count($request->skillsArray) > 0)
        {
            $data = json_decode($request->skillsArray, TRUE);
            $models = Skills::whereIn('id', $data)
            ->select('skill')
            ->where('status', 'Active')
            ->get();

            return $this->resultapi('1','success',$models);
        }
        else
        {
            return $this->resultapi('0','success',null);
        }
        
    }

    public function checkInformation(Request $request){
        $userId = $request->input('uid');
        if($userId && $userId !== "")
        {
            $user = User::find($userId);

            $isProfileUpdated = $user->is_profile_updated;
            $isPaymentUpdated = $user->is_payment_updated;
            $emailVerified    = $user->email_verified;

            if($isProfileUpdated == 1)
            {
                if($emailVerified == "Yes")
                {
                    if($isPaymentUpdated == 1)
                    {
                        $this->resultapi('1','All Information Updated.', true); 
                    }
                    else
                    {
                        $this->resultapi('0','Please update Payment Details.',null);
                    }
                }
                else
                {
                    $this->resultapi('0','Your Email Is not Verified.',null);
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

    public function postUpdateProposal(Request $request){
     
        if($request->propId && $request->propDesc && $request->propPrice)
        {
            $propData = JobProposal::find($request->propId);
            if(count($propData) > 0 )
            {
                $projData = JobDetail::find($propData->job_id);

                if($projData->hired_user_id == 0 && $projData->job_stage != "Finished" && $propData->status == 3)

                {
                    $propData->prop_price       = $request->propPrice;  
                    $propData->prop_desc        = $request->propDesc;
                    $propData->is_proposal_edit = 1;  
                    $propData->status           = 1;

                    if($propData->save())
                    {
                        $mailData = array(
                            'clientId'          => $propData->client_id,
                            'userId'            => $propData->user_id,
                            'projectId'         => $propData->job_id,
                            'inv_description'   => $propData->prop_desc,
                            'inv_status'        => "Pending",
                            'inv_attachemnts'   => empty($request->prop_attachment) ? "" : $request->prop_attachment,
                            'template'          => 'new-price-updated-by-freelancer',
                            'search'            => "a",
                            'replace'           => "a"        
                        );

                        $sendStatus = EmailTemplates::SendEmail($mailData); 
                        $this->resultapi('1','Proposal Updated Sucessfully.',0);   
                    }
                    else
                    {
                        $this->resultapi('0','Problem in proposal Updated.',1); 
                    }
                }
                else
                {
                    $this->resultapi('0','User already Hired for this Job.',1);
                }
            }
            else
            {
                $this->resultapi('0','Problem in proposal Updated.',1);
            }
        }
        else
        {
            $this->resultapi('0','Problem in proposal Updated.',1);
        }
    }

    public function getCheckJobApplied(Request $request){
        
        $isProposalApplied = $this->checkExistingProposal($request->uid,$request->pid);            

        if($isProposalApplied == 0)
        {
            $this->resultapi('1','Job Applied.',0);   
        }
        else
        {
             $this->resultapi('1','Job Not Applied.',1);
        }
    }    

    public function checkExistingProposal($uid,$pid){

        $findProposal = DB::table('job_proposals')
            ->where('user_id', $uid)
            ->where('job_id', $pid)
            ->get();

        $count = count($findProposal);
        
        return $count;
    }

     public function apply_job(Request $request){      
         
        if($request->hasFile('file')) {            
            $file = $request->file('file');
            foreach ($file  as $key => $value) 
            {
                $img_name = $value->getClientOriginalName(); 

                $timestamp = time().  uniqid();
                $name = $timestamp. '-' .$value->getClientOriginalName();
                $profileImage = $name;

                $path = public_path().'/asset/jobApply/'; 
                if(!is_dir($path)){
                    File::makeDirectory($path, 0755, true, true);
                } 

                $value->move(public_path().'/asset/jobApply/', $name);
                $file_data[] = $profileImage;
            }

            $result_data = implode(',' , $file_data);
            $this->resultapi('1','Documents Updated Sucessfully.',$result_data);  
        }
        else
        {
            $result_data = "";
            $this->resultapi('0','Problem In Document Upload',$result_data); 
        }
            
    }
   
    /* save Job posted by freelancer */
    public function postSaveProposal(Request $request){

        if($request->all() && count($request->all()) > 0)
        {
            $validator = Validator::make($request->all(), [
                'prop_pid'          => 'required|numeric|min:1',
                'prop_desc'         => 'required',
                'prop_uid'          => 'required|numeric|min:1',
                'prop_cid'          => 'required|numeric|min:1',
                'prop_price'        => 'numeric|min:1',
                'terms_condition'   => 'required',
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0',$validator->errors()->all(), $request->prop_pid);
            }
            else
            {
                $isProposalApplied = $this->checkExistingProposal($request->prop_uid,$request->prop_pid);              

                if($isProposalApplied == 0)
                {
                    $proposalSaveDone = JobProposal::sendJobProposal($request);
                    
                    if($proposalSaveDone)
                    {
                       $JobMessagesAttach  = json_encode($request->prop_attachment, true);

                       /* JobMessage::sendJobMessage($request->prop_uid, $request->prop_cid, $request->prop_pid, $request->prop_desc, $JobMessagesAttach,2, 0);*/
                        
                        $mailData = array(
                            'clientId'          => $request->prop_uid,
                            'userId'            => $request->prop_cid,
                            'projectId'         => $request->prop_pid,
                            'inv_description'   => $request->prop_desc,
                            'inv_status'        => "Pending",
                            'inv_attachemnts'   =>  empty($request->prop_attachment) ? "" : $request->prop_attachment,
                            'template'          => 'new-project-invtation-by-freelancer',
                            'search'            => "a",
                            'replace'           => "a"        
                        );

                        $sendStatus = EmailTemplates::SendEmail($mailData); 

                        $this->resultapi('1','Proposal Send sucessfully.', $request->prop_pid);
                    }
                    else
                    {
                        $this->resultapi('0','Some Problem to apply job.',$request->prop_pid);
                    }
                }
                else
                {
                     $this->resultapi('0','You are already applied to this job.',$request->prop_pid);
                }
            }
        }
        else
        {
            $this->resultapi('0','Operation not not possible.',0);
        }
      
    }

    public function getMyProposals(Request $request){  
        
        if($request->uid && $request->uid !="" && $request->uid !="undefiend")
        {
            $myProposals = DB::table('job_proposals')
            ->join('job_details', 'job_proposals.job_id', '=', 'job_details.id')
            ->select('job_details.id','job_proposals.status','job_proposals.created_at','job_proposals.job_id','job_details.job_title','job_details.job_stage')
            ->where('job_proposals.user_id',$request->uid)            
            ->orderBy('created_at', 'desc')                    
            ->get();

            $this->resultapi('1','Proposal Found.',$myProposals ); 
        }
        else
        {   
            $myProposals ="";
            $this->resultapi('0','User not found.',null); 
        }    
       
    }

     public function getMyProjects(Request $request){  
        
        if($request->uid && $request->uid !="" && $request->uid !="undefiend")
        {
            $myProjects = DB::table('job_details')            
            ->where('hired_user_id',$request->uid)            
            ->orderBy('created_at', 'desc')                    
            ->get();

            $this->resultapi('1','Project Not Found.',$myProjects); 
        }
        else
        {   
            $myProjects ="";
            $this->resultapi('0','User not found.',null); 
        }      
    }

    public function getMyInvitations(Request $request){  
        
        if($request->uid && $request->uid !="" && $request->uid !="undefiend")
        {
            $myInvitations = DB::table('job_invitations')
            ->join('job_details', 'job_invitations.job_id', '=', 'job_details.id')
            ->select('job_invitations.inv_status','job_invitations.created_at','job_invitations.inv_attachemnts','job_invitations.job_id','job_invitations.id','job_details.job_title','job_details.job_stage','job_details.job_stage')
            ->where('job_invitations.user_id',$request->uid)            
            ->orderBy('job_invitations.job_id', 'desc')                    
            ->get();

            $this->resultapi('1','Proposal Found.',$myInvitations ); 
        }
        else
        {   
            $myInvitations ="";
            $this->resultapi('0','User not found.',null); 
        }   
       
    }

    public function changeInvStatus(Request $request){

        if($request->invId && $request->invStatus && $request->uid)
        {
            $userDetails = User::find($request->uid);
     
            if($userDetails->is_profile_updated == 1)
            {
                $changeInvStatus = $request->invStatus;
                if($changeInvStatus == 1)
                {
                    $changeInvStatusTo  = 'Accept';
                    $changeInvStatusMsg = 'Accepted';
                    $emailTeplate       = 'accept-project-invtation-freelancer';
                }
                else if($changeInvStatus == 2)
                {
                    $changeInvStatusTo = 'Reject';
                    $changeInvStatusMsg = 'Rejected';
                    $emailTeplate       = 'decline-project-invtation-freelancer';
                }
                else
                {
                    $changeInvStatusTo = 'Pending';
                    $changeInvStatusMsg = 'Changed';  
                }

                $chekInv = JobInvitation::find($request->invId);
                $chekInv->inv_status = $changeInvStatusTo;

                if($chekInv->save())
                {
                    $InvMsg = "Job Invtation ".$changeInvStatusMsg;

                   /* JobMessage::sendJobMessage($chekInv->user_id, $chekInv->client_id, $chekInv->job_id, $InvMsg,  null, 2, 0);*/

                    $mailData = array(
                        'clientId'          => $chekInv->user_id,
                        'userId'            => $chekInv->client_id,
                        'projectId'         => $chekInv->job_id,
                        'inv_description'   => $InvMsg,
                        'inv_status'        => "Pending",
                        'inv_attachemnts'   => "",
                        'template'          => $emailTeplate,
                        'search'            => "a",
                        'replace'           => "a"        
                    );

                    $sendStatus = EmailTemplates::SendEmail($mailData);
                  
                    $msg = "Invtation ".$changeInvStatusMsg." sucessfully.";
                    $this->resultapi('1',$msg,$changeInvStatus); 
                }
            }
            else
            {   
                $InvStatus ="";
                $this->resultapi('0','Please update Payment Details.',$InvStatus);
            }
        }
        else
        {   
            $InvStatus ="";
            $this->resultapi('0','Server Error',$InvStatus); 
        }
    }

    public function getFreelancerProjectStatus(Request $request){

        if($request->pid && $request->uid)
        {
            $projectStatus['jobDetails'] = DB::table('job_details')         
            ->where('hired_user_id',$request->uid)
            ->where('id',$request->pid)
            //->where('job_stage','!=','Finished')
            ->select('id','user_id','hired_user_id','job_title','job_subtitle','job_cost','final_job_cost','job_stattime','job_endtime','job_description','job_images','job_submittion_date','is_payment_relased','proj_close_noti_freelancer','proj_close_noti_client','status','job_stage')
            ->first();

            $resultCount = count($projectStatus['jobDetails']);
            if($resultCount > 0)
            {
                $projectStatus['clientDesc'] =  DB::table('users')
                ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->where('users.id',$projectStatus['jobDetails']->user_id)
                ->leftJoin('states', 'states.id', '=', 'user_profiles.stateId')             
                ->join('cities', 'user_profiles.locationId', '=', 'cities.id')
                ->select('users.id','users.firstname','users.lastname','users.profile_image','user_profiles.street','cities.name as cityName','states.name as stateName','user_profiles.zipcode')
                ->first();

                $projectStatus['freelancerDesc'] =  DB::table('users')
                ->join('user_profiles', 'users.id', '=', 'user_profiles.user_id')
                ->where('users.id',$projectStatus['jobDetails']->hired_user_id)
                ->leftJoin('states', 'states.id', '=', 'user_profiles.stateId')             
                ->join('cities', 'user_profiles.locationId', '=', 'cities.id')
                 ->select('users.id','users.firstname','users.lastname','users.profile_image','user_profiles.street','cities.name as cityName','states.name as stateName','user_profiles.zipcode')
                ->first();          

                $projectStatus['jobProtfolio']  = JobProtfolio::portfolioByProjectId($request->pid);
               
                $this->resultapi('1','Project Found.',$projectStatus); 
            }
            else
            {   
                $projectStatus = "";
                $this->resultapi('0','Project Not Found.',$projectStatus); 
            } 
        }
        else
        {   
            $projectStatus ="";
            $this->resultapi('0','Project Not Found.',$projectStatus); 
        }      
    }        

    public function resultapi($status,$message,$result = array()) {
            $finalArray['STATUS'] = $status;
            $finalArray['MESSAGE'] = $message;
            $finalArray['DATA'] = $result;
            echo json_encode($finalArray);  
    } 

}