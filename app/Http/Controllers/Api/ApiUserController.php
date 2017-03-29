<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use Auth;
 
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Routing\ResponseFactory;

use App\Api\User; 
use App\Api\UserProfile;
use App\Api\Payment;
use App\Api\JobMessage;
use App\Api\JobInvitation;
use App\Api\JobDetail;
use App\Api\JobProtfolio;
use App\JobProposal;
use App\Api\JobQuestion;

use App\EmailTemplates; 
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\Validator; 
use Hash;
use Illuminate\Support\Facades\URL;
use Mail; 
use File;

use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class ApiUserController extends Controller {
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
        //$this->middleware('auth');

    }

    public function users(){
        $users = DB::table('users')->select('id','firstname', 'lastname','email')->paginate(5);
        return $users;         
    }

    public function getHiringDetails(Request $request){
        
        if($request->userId && $request->ProjId)
        {
            $hiringDetails['Freelancer'] = DB::table('users')
                                        ->where('id',$request->userId)
                                        ->select('id','firstname','lastname')
                                        ->first();

            $hiringDetails['Project']    = DB::table('job_details')
                                        ->where('id',$request->ProjId)
                                        ->select('id','job_cost','job_submittion_date')
                                        ->first();
            
            $this->resultapi('1','User Details',$hiringDetails);       
        }
        else
        {
            $hiringDetails = array();
            $this->resultapi('0',"Required data not found.",$hiringDetails);
        } 
    }
    
    public function getForgotPassword(Request $request){
        if($request->email && $request->email != "")
        {     
            $validator = Validator::make($request->all(), [
                'email' =>'required|max:100|email',
            ]);

            if ($validator->fails()) 
            {
                $this->resultapi('0',$validator->errors()->all(), null);
            }
            else
            {
                $email      = $request->email;
                $verifyCode = str_random(8);
                
                $user =  User::where("email",$email)->where("email_verified",'Yes')->first();

                if(count($user) > 0)
                {  
                    $websiteLink      = config('constant.base_url')."passwordReset/id/".$user['id'];
                    $search           = array("[FIRSTNAME]","[WEBSITELINK]","[VCODE]");
                    $replace          = array($user['firstname'], $websiteLink, $verifyCode);  
                    
                    $params = array(
                        'subject'     => 'JoboBookers Password Reset',
                        'from'        => "info@techuz.com",
                        'to'          =>  $user['email'],  
                        'template'    => 'forgot-password',
                        'search'      =>  $search,
                        'replace'     =>  $replace        
                    );
               
                    $result = $this->SendEmail($params);

                    if($result == true)
                    {  
                        $user ->verify_forgot_password = $verifyCode;
                        $user->save();

                        $this->resultapi('1','Check your email to reset your password.',$user['id']);
                    }
                    else
                    { 
                        $this->resultapi('0','Something went wrong with server,Please try again.');
                        return false;
                    }                    
                }
                else
                {
                    $this->resultapi('0','Email Not Registered or Verified.',null);
                }
            }
        }
        else
        {

        }
    }

    public function getPasswordReset(Request $request){

        if($request->user_id && $request->all() && count($request->all()) > 0)
        {      
            $validator = Validator::make($request->all(), [

                'user_id'                   =>'required|numeric',
                'password_verify_code'      =>'required|max:255|min:6|max:10',
                'password'                  =>'required|max:255|min:6',
                'confirm_password'          =>'required|max:255|min:6|same:password',
                
            ]);
             
            if ($validator->fails()) 
            {
                $this->resultapi('0',$validator->errors()->all(), null);
            }
            else
            {
                $userId = $request->user_id;
                $user = User::find($userId);
                
                if(trim($request->password_verify_code) === trim($user->verify_forgot_password))
                {                    
                    $user->password  =  bcrypt($request->password);
                    $user->verify_forgot_password = "";
                    $user->save();

                    $this->resultapi('1','Password Updated Sucessfully. Please Login.',null);
                   
                }
                else
                {
                     $this->resultapi('0','Wrong verification password.',null);
                }
            }
        }
        else
        {
            $this->resultapi('0','Operation not not possible.',null);
        }
    }


    public function SendEmail($params){
    
        $config = array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.gmail.com',
            'smtp_port' => 465, 
            'smtp_user' => "devangp@techuz.com", //SITE_EMAIL_ID_SHK change it to yours
            'smtp_pass' => "dev@%$#@!", // SITE_PASSWORD_SHK change it to yours 
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );

        $email_template = EmailTemplates::where('slug', '=', $params["template"])->first();  
     
        $message = str_replace($params["search"], $params["replace"], $email_template["content"]); 
        
        $user = User::where('email', '=', $params['to'])->first();
         
        $sent = Mail::raw( $message,function ($m) use ($params)
        {   $m->from($params['from'], 'JobBookers');
            $m->to($params['to'])->subject($params['subject']);
        });  

        if($sent == true)
        {  
            return true; 
        }
        else
        { 
            show_error($this->email->print_debugger());
            return false;
        }
    }

    /**
     * Get a CSRF-TOKEN.
     *
     * @return Response
     */
    public function getFormToken()
    { 
        $formToken['_token'] = csrf_token(); 
        echo json_encode($formToken);
    } 

    /**
    * Get a user by the token from the header.
    *
    * @return Response
    */
    public function getByToken()
    {   
        try {
             header('Content-Type: application/json');
            $userdata = array($this->jwtAuth->parseToken()->authenticate());
            $this->resultapi('1','Login Successfully',$userdata);
            //return $this->jwtAuth->parseToken()->authenticate();
        } catch(TokenExpiredException $e) {
            return [
            'error' => true,
            'code'  => 11,
            'message'   => 'Token Expired'
            ];
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return [
            'error' => true,
            'code'  => 12,
            'message'   => 'Invalid Token'
            ];
        } catch (JWTException $e) {
            return [
            'error' => true,
            'code'  => 13,
            'message'   => ''
                //Token absent
            ];
        }
    }

    public function verification($number){ 
        if($number!=""){  

            $is_verified = User::where('email_verify_code',$number)->where('email_verified','Yes')->first();
            if(count($is_verified)>0)
            { 
                // update verification status  
                $this->resultapi('0',"your email is already verified, please Login.");
            }
            else
            {
                $users = User::where('email_verify_code',$number)->first();
                if(count($users)>0)
                {
                    $array_updated= array('email_verified' => 'Yes','email_verify_code' => '');
                    DB::table('users')->where('email_verify_code',$number)->update($array_updated);
                    $this->resultapi('1','Your email has been successfully verified.');
                }
                else
                {
                    $this->resultapi('0',"Your email doesn't match.");
                }
            }
        }
        else
        {
            $this->resultapi('0',"your email doesn't verified, please try again.");
        }
    }
    public function logout(Request $request)
    {
        $user_id = $request->user_id;
        Auth::logout();
        $this->resultapi('1','Logout Successfully.');
    }

    public function register(Request $request){ 
        DB::beginTransaction();
       
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255', 
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
            'usertype' => 'required',
            'terms_conditions' => 'required',

        ]);             
        
        if ($validator->fails())
        {
            $this->resultapi('0','validation Error',$validator->errors()->all());
        }

        $user = User::where('email', '=', $request->email)->first();

        if ($user === null)
        {
            $fbid             = 0;
            $emailVerified    = 'No';
            $gplusId          = 0;
            $termsConditions  = 1;

            if($request->fbid && $request->fbid != 0)
            {
                $fbid             = $request->fbid;
                $emailVerified    = 'Yes';
            }
 
            $userpost_array = array(
                'firstname'         => $request->firstname,
                'lastname'          => $request->lastname, 
                'email'             => $request->email,
                'password'          => bcrypt($request->password),
                'usertype'          => $request->usertype,               
                'terms_conditions'  => $termsConditions,
                'email_verified'    => $emailVerified,
                'fb_id'             => $fbid,                
                'gplus_id'          => $gplusId,
            );
          
            $create = User::create($userpost_array);

            //$user = User::find($create->id);
            
            $UserProfile = new UserProfile();
            $UserProfile->user_id = $create->id; 
            $UserProfile->save();


            $Payment = new Payment();
            $Payment->user_id = $create->id; 
            $Payment->save();

             
            $rand = str_random(18);  
            DB::table('users')->where('id',$create->id)->update(['email_verify_code' => $rand]);

            $VerificationLink = config('constant.base_url')."verification/".$rand;
            $search = array("[FIRSTNAME]","[WEBSITELINK]");
            $replace = array($user['firstname'],$VerificationLink); 
       
            $result = EmailTemplates::RegisterUser($request->email,'new-register',$search,$replace); 
            if($result==true)
            {
                DB::commit();
                $this->resultapi('1','User Registered Successfully and Verification link send to your email.',$user);
            }
            else
            {
                DB::rollBack();
                $this->resultapi('0','User added sucessfully but due to some problem verification link not send.');
            }

            
        } else {
                $this->resultapi('0','Email already exist.');  
        }
    }
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'password'=>'required|min:6',
        ]);
        if ($validator->fails()) {   
                $this->resultapi('0','Fail',$validator->errors()->all());
        }

        if (Auth::attempt(array('email' => $request->email, 'password' => $request->password)))
        { 
            $user = Auth::user();
            $user['tokenId'] = $this->jwtAuth->fromUser($user);
            $isUerEmailVerified = $user['email_verified'];
            $userdata = array($user);
            if($isUerEmailVerified  && $isUerEmailVerified === "Yes")
            {
                $this->resultapi('1','Login Successfully.',$userdata);  
            }
            else
            {
                $this->resultapi('2','Your Email is not verified Please verify then you can login.');
            }
        }
        else
        { 
                $this->resultapi('0','Invalid login details.');
        }
    }

    public function socialLogin(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255',
                'id'    => 'required',                
        ]);
        if ($validator->fails()) {   
                $this->resultapi('0','Login Failed',$validator->errors()->all());
        }
        else
        {
            //$fbId = $request->id;
            $user = User::where('email',$request->email)
                            ->where('fbid',$request->id)
                            ->first();

            if(count($user) > 0)
            {
                $this->resultapi('1','Found',$request->id);
            }
            else
            {
                $this->resultapi('0','You have no account related to this facebook email id',$request->id);
            }
        }
    }
    
    
    public function forgotPassword(Request $request)
    {

        $user = User::where('email', '=', $request->email)
                    ->where('password','!=','')->first();
        
        if($user == null)
        {
            $this->resultapi('0','We can\'t find a user with that e-mail address.'); 
        }

        $this->validate($request, ['email' => 'required|email']);

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                $message->subject($this->getEmailSubject());
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                $this->resultapi('1','Success',trans($response));

            case Password::INVALID_USER:
                $this->resultapi('0','Fail',trans($response));
        }
    }

   /* public function profile()
    {
        $users = User::find(); 
        return $users;
        
    }*/
    public function getNotification(Request $request)
    {
        if($request->uid)
        {
            $readStatus = 0;
            $unreadMsgs = JobMessage::where('reeciver_id',$request->uid)
            ->where('read_status',$readStatus)
            ->get()
            ->count();
            $this->resultapi('1','Msg Found.', $unreadMsgs);
           
        }
        else
        {
            $allMsgs = array();
            $this->resultapi('0','No. Msg Found.', $allMsgs);  
        }        
    }
    
    public function getAllMessages(Request $request)
    { 
        if($request->uid && $request->usertype)
        { 
            if($request->usertype == "Freelancer")
            {
                $msgProjects =  DB::table('job_details')
                ->select('job_title','id','job_images','job_images','created_at','status','job_stage','hired_user_id')
                ->where('hired_user_id',$request->uid)
                ->orderBy('job_title','asc')
                ->get();
            }
            else
            {
                $msgProjects =  DB::table('job_details')
                ->select('job_title','id','job_images','job_images','created_at','status','job_stage','hired_user_id')
                ->where('user_id',$request->uid)
                ->where('hired_user_id','!=','0')
                ->orderBy('job_title','asc')
                ->get();
            }

            if($request->usertype === 'Client')
            {
                $usertype = 2;
            }
            else
            {
                $usertype = 1;
            }  

            if(count($msgProjects) > 0)
            {
                foreach ($msgProjects as $key => $result)
                {
                    $msgProjects[$key]->unread_msg = DB::table('job_messages')                       
                    ->where('project_id', $result->id)
                    ->where('reeciver_id',$request->uid)
                    ->Where('messsge_type',$usertype)
                    ->where('read_status',0)
                    ->count();
                }

                $this->resultapi('1','No Msg Found.', $msgProjects);
            }           
            else
            {   $msgProjects = array();             
                $this->resultapi('1','No Msg Found.', $msgProjects);
            }
        }
        else
        {
            $msgProjects = array();
            $this->resultapi('0','No. user Found.', $msgProjects);  
        }
        
    }

    public function getLoadMessages(Request $request)
    {  
        if($request->uid && $request->pid && $request->usertype)
        { 
            $msgDetails['messages'] = DB::table('job_messages')
            ->Where(function ($query) use ($request)  {              
                $query->where('reeciver_id',$request->uid)
                      ->orWhere('sender_id',$request->uid);
            })

            ->where('project_id',$request->pid)
            ->orderBy('id')
            ->get();

            $msgDetails['projects'] =  DB::table('job_details')
            ->select('job_title','id','job_images','job_stage','job_stattime','created_at')
            ->where('id',$request->pid)            
            ->first(); 

            if($request->usertype === 'Client')
            {
                $usertype = 2;
            }
            else
            {
                $usertype = 1;
            }           

            JobMessage::where('project_id',$request->pid)
            ->Where('messsge_type',$usertype)
            ->Where(function ($query) use ($request)  {              
                $query->where('reeciver_id',$request->uid)
                      ->orWhere('sender_id',$request->uid);
            })
            ->update(['read_status' => '1']);

            if(count($msgDetails) > 0)
            {
                $this->resultapi('1','Messages Found.', $msgDetails);  
            }
            else
            {
                $this->resultapi('1','No Messages Found.', $msgDetails);   
            }            
        }
        else
        {
            $msgDetails = array();
            $this->resultapi('0','Unable to find messages.', $msgDetails);  
        
        }      
    }

    public function getMakeMsgRead(Request $request)
    {
        $msgStatus = JobMessage::find($request->mid);
        $msgStatus->read_status =1;
        $msgStatus->save();

        $this->resultapi('1','Msg. Status Changed Sucessfully.', $msgStatus);   
    }

    public function sendMessage(Request $request)
    {
        if($request->msg_desc && $request->proj_id && $request->usertype )
        {
            $projDetail = JobDetail::find($request->proj_id);
            if($projDetail->hired_user_id && $projDetail->job_stage != "Finished" && count($projDetail) > 0)
            {            
                $files = "";
                $msg_attachemnts = "";
                if(isset($request->file))
                {
                    $files = $request->file;
                    $msg_attachemnts = json_encode($this->uploadAttachments($files));
                }
                if($request->usertype === "Client")
                {
                    $senderId       = $projDetail->user_id;
                    $reeciverId     = $projDetail->hired_user_id;
                    $messsge_type   = 1;
                }
                else
                {
                    $reeciverId     = $projDetail->user_id;
                    $senderId       = $projDetail->hired_user_id;
                    $messsge_type   = 2;
                }

                $JobMessages = new JobMessage; 
                $JobMessages->sender_id             = $senderId;
                $JobMessages->reeciver_id           = $reeciverId;
                $JobMessages->project_id            = $request->proj_id;
                $JobMessages->messsge_description   = $request->msg_desc;
                $JobMessages->messsge_type          = $messsge_type;                                   
                $JobMessages->messsge_documents     = $msg_attachemnts; 
                $JobMessages->read_status           = 0;                                   
                
                if($JobMessages->save())
                { 
                    $mailData = array(
                        'clientId'          => $senderId,
                        'userId'            => $reeciverId,
                        'projectId'         => $request->proj_id,
                        'description'       => $request->msg_desc,
                        'attachemnts'       => $msg_attachemnts,
                        'template'          => 'assign-proj-related-msg',
                        'search'            => "a",
                        'replace'           => "a"        
                    );

                    $sendStatus = EmailTemplates::SendEmail($mailData);

                    $this->resultapi('1','Message Send Sucessfully.', true); 
                }
                else
                {
                    $this->resultapi('0','Failed to send Message.', $msgStatus);
                }                 
            }
            else
            {
                $this->resultapi('0','Failed to send Message.', $msgStatus);
            }    
        }
        else 
        {
           $this->resultapi('0','Failed to send Message.', $msgStatus); 
        }
    }
    

    public function getRejectUser(Request $request)
    {
        if($request->propid)
        {
           $rejectUsertatus = JobProposal::rejectProposalByClient($request);
           if($rejectUsertatus)
            {
                $this->resultapi('1','Freelancer rejected sucessfully.', true);
            }
            else
            {
                $this->resultapi('0','Freelancer not rejected.', true);
            }                           
        }
        else
        {
            $this->resultapi('0','System Error Details Not found.', true); 
        }
    }

    public function rejectUserInv(Request $request)
    {
        if($request->invId)
        {
           $rejectUserInv = JobInvitation::rejectInvByClient($request);
           if($rejectUserInv)
            {
                $jobInv = JobInvitation::find($request->invId);
                $projCloseDesc = 'Not Intrested Any More';

                $mailData = array(
                    'clientId'          => $jobInv->client_id,
                    'userId'            => $jobInv->user_id,
                    'projectId'         => $jobInv->job_id,
                    'inv_description'   => $projCloseDesc,
                    'inv_status'        => "Rejected",
                    'inv_attachemnts'   => "",
                    'template'          => 'decline-project-invtation-client',
                    'search'            => "a",
                    'replace'           => "a"        
                );

                $sendStatus = EmailTemplates::SendEmail($mailData);

                $this->resultapi('1','Invitation Rejected.', true); 
            }
            else
            {
                $this->resultapi('0','Invitation not Rejected.', true);
            }                           
        }
        else
        {
            $this->resultapi('0','System Error Details Not found.', true); 
        }
    }    

    public function getQuitProject(Request $request)
    {
        if($request->pid && $request->fid && $request->cid && $request->rating && $request->ratingDetails )
        {
            //JobProtfolio::addPortfolio($request->pid, $request->fid, $request->cid, $request->rating, $request->ratingDetails);
            $jobDetails = JobDetail::find($request->pid);
            if(count($jobDetails) > 0)
            {
                if($jobDetails->user_id == $request->cid)
                {                    
                    if($jobDetails->is_payment_relased ==1)
                    {
                        $jobDetails->proj_close_noti_client = 2;
                        $jobDetails->job_stage      = "Finished";
                        $jobDetails->job_endtime    = date('Y-m-d');

                        if($jobDetails->save())
                        {
                            JobProtfolio::addPortfolio($request->userType, $request->pid, $request->fid, $request->cid, $request->rating, $request->ratingDetails);
                            
                            $projCloseDesc = "Project closed by user.";

                            /*JobMessage::sendJobMessage($request->cid, $request->fid, $request->pid, $projCloseDesc, null,1, 0);*/
                            
                            $mailData = array(
                                'clientId'          => $request->cid,
                                'userId'            => $request->fid,
                                'projectId'         => $request->pid,
                                'inv_description'   => $projCloseDesc,
                                'inv_status'        => "Pending",
                                'inv_attachemnts'   => "",
                                'template'          => 'close-project-client',
                                'search'            => "a",
                                'replace'           => "a"        
                            );

                            $sendStatus = EmailTemplates::SendEmail($mailData); 
                            
                            $this->resultapi('1','Project Inactive / Compleated sucessfully.', true);
                        }
                        else
                        {
                            $this->resultapi('0','Some Problem to apply job.',true);
                        }
                    }
                    else
                    {
                        $this->resultapi('0','Please release payment first.', true);
                    }
                }
                else
                {
                    $this->resultapi('0','You can close only one posted project.', true); 
                }
            }
            else
            {
                $this->resultapi('0','No Projects Found.', true); 
            }               
        }
        else
        {
            $this->resultapi('0','System Error Details Not found.', true); 
        } 
    }

    public function getQuitProjectFreelancer(Request $request)
    { 
       
        if($request->pid && $request->fid && $request->cid && $request->rating && $request->requestType)
        {
            $jobDetails = JobDetail::find($request->pid);
            if(count($jobDetails) > 0)
            {
                if($jobDetails->user_id == $request->cid)
                {                    
                    if($jobDetails->proj_close_noti_client != 2 || $jobDetails->proj_close_noti_freelancer != 1)
                    {
                        
                        if($request->requestType == 1)
                        {
                            $jobDetails->proj_close_noti_client = 2;
                            $emailtemplate ='close-project-req-accepted-by-freelancer';
                        }
                        else if($request->requestType == 2)
                        {   
                            $jobDetails->proj_close_noti_freelancer = 1;
                            $emailtemplate ='close-project-req-sent-by-freelancer';  
                        }
                        else
                        {
                            return false;
                        }

                        if($jobDetails->save())
                        {
                            JobProtfolio::addPortfolio($request->userType, $request->pid, $request->fid, $request->cid, $request->rating, $request->ratingDetails);
                            
                            $projCloseDesc = "Project closed by Freelancer.";

                            /*JobMessage::sendJobMessage($request->cid, $request->fid, $request->pid, $projCloseDesc, null,1, 0);*/
                            
                            $mailData = array(
                                'clientId'          => $request->fid,
                                'userId'            => $request->cid,
                                'projectId'         => $request->pid,
                                'inv_description'   => $projCloseDesc,
                                'inv_status'        => "Pending",
                                'inv_attachemnts'   => "",
                                'template'          => $emailtemplate,
                                'search'            => "a",
                                'replace'           => "a"        
                            );

                            $sendStatus = EmailTemplates::SendEmail($mailData); 
                            
                            $this->resultapi('1','Request Send Sucessfully.', true);
                        }
                        else
                        {
                            $this->resultapi('0','Some Problem to apply job',true);
                        }
                    }
                    else
                    {
                        $this->resultapi('0','you cannot resubmit your feedback.', true); 
                    }
                }
                else
                {
                    $this->resultapi('0','You can close only own posted project.', true); 
                }
            }
            else
            {
                $this->resultapi('0','No Projects Found.', true); 
            }               
        }
        else
        {
            $this->resultapi('0','System Error Details Not found.', true); 
        } 
    }   
   

    public function uploadAttachments($files)
    {   
        $uploadcount = 0;                   
        $msg_attachemnts = array();
        foreach($files as $file)
        {
            $rules = array('file' => 'mimes:txt,pdf,doc,xls,odt,jpg,jpeg,png,gif|max:5120');

            $validator = Validator::make(array('file'=> $file), $rules); 
            if($validator->passes()){ 
                $destinationPath = public_path().'/asset/msgAttachments/';
                if(!is_dir($destinationPath)){
                    File::makeDirectory($destinationPath, 0755, true, true);
                }                       
                $timestamp = time().  uniqid();
                $filename = $timestamp. '_' .trim($file->getClientOriginalName()); 
                $upload_success = $file->move($destinationPath, $filename);
                $msg_attachemnts[] = $filename;
                $uploadcount ++;
            }           
        }

        return $msg_attachemnts;
    }

    /* question Answer module */

    public function questionAns(Request $request)
    {
        if($request->pid)
        {   
            $jobQuesAns  = JobQuestion::getAllQuesAns($request->pid);
            if($jobQuesAns)
            {
                $this->resultapi('1','Question found.', $jobQuesAns);
            }
            else
            {
                $this->resultapi('0','Question details not found.', true); 
            }
        }
        else
        {
            $this->resultapi('0','Some required details not found.', true); 
        }
    }


    public function submitQuestion(Request $request)
    {
        if($request->cId && $request->ProjId && $request->fId && $request->ques)
        {   
            $saveStatus = JobQuestion::submitQuestion($request);

            if($saveStatus == 1)
            {
                $mailData = array(
                    'clientId'          => $request->fId,
                    'userId'            => $request->cId,
                    'projectId'         => $request->ProjId,
                    'inv_description'   => $request->ques,
                    'template'          => 'new-question-freelancer',
                    'search'            => "a",
                    'replace'           => "a"        
                );

                $sendStatus = EmailTemplates::SendEmail($mailData);

                $this->resultapi('1','Your Question submitted sucessfully, you have to wait untill client reply.', true);
            }
            elseif($saveStatus == 2)
            {
                $this->resultapi('0','This job is Closed or A User already hired.', true); 
            }
            else
            {
                 $this->resultapi('0','Some Required Details Not found.', true); 
            }
        }
        else
        {
            $this->resultapi('0','Some Required Details Not found.', true); 
        }
    }

    public function submitAnswer(Request $request)
    {
        if($request->QuesId && $request->Ans)
        {   
            $updateStatus = JobQuestion::submitAnswer($request->QuesId, $request->Ans);
            if($updateStatus)
            {
                $Question = DB::table('job_questions')->where('id',$request->QuesId)->first();  
                $mailData = array(
                    'clientId'          => $Question->cid,
                    'userId'            => $Question->fid,
                    'projectId'         => $Question->pid,
                    'inv_description'   => $request->Ans,
                    'template'          => 'question-reply-client',
                    'search'            => "a",
                    'replace'           => "a"        
                );

                $sendStatus = EmailTemplates::SendEmail($mailData);
              
                $this->resultapi('1','Answer submitted sucessfully.', true);
                  
            }
            else
            {
                $this->resultapi('0','Some required details not found.', true); 
            }
        }
        else
        {
            $this->resultapi('0','Some required details not found.', true); 
        }
    }

    public function replyOnAnswer(Request $request)
    {
        //print_r($request->all());
        //die;
        if($request->QuesId && $request->Ans)
        {   
            $updateStatus = JobQuestion::replyOnAnswer($request->QuesId, $request->Ans);
            if($updateStatus)
            {
                $Question = DB::table('job_questions')->where('id',$request->QuesId)->first();  
                $mailData = array(
                    'clientId'          => $Question->cid,
                    'userId'            => $Question->fid,
                    'projectId'         => $Question->pid,
                    'inv_description'   => $request->Ans,
                    'template'          => 'question-reply-client',
                    'search'            => "a",
                    'replace'           => "a"        
                );

                $sendStatus = EmailTemplates::SendEmail($mailData);
              
                $this->resultapi('1','Answer submitted sucessfully.', true);
                  
            }
            else
            {
                $this->resultapi('0','Some required details not found.', true); 
            }
        }
        else
        {
            $this->resultapi('0','Some required details not found.', true); 
        }
    }

    public function deleteQuestion(Request $request)
    {
        if($request->QuesId)
        {   
            $delStatus = JobQuestion::deleteQuestion($request->QuesId);
            if($delStatus)
            {
                $this->resultapi('1','Question deleted sucessfully.', true);
            }
            else
            {
                $this->resultapi('0','Some required details not found.', true); 
            }
        }
        else
        {
            $this->resultapi('0','Some required details not found.', true); 
        }
    }

    /* projec Close Notification */

    public function projCloseNotification(Request $request)
    {
        if($request->projId && $request->cId && $request->usertype )
        {   
            $status = JobDetail::projCloseNotification($request);
            if($status)
            {
                $projDetail = JobDetail::find($request->projId);
                $mailData = array(
                    'clientId'          => $projDetail->user_id,
                    'userId'            => $projDetail->hired_user_id,
                    'projectId'         => $projDetail->id,
                    'inv_description'   => "",
                    'inv_status'        => "Pending",
                    'inv_attachemnts'   => "",
                    'template'          => "close-proj-req-by-client",
                    'search'            => "a",
                    'replace'           => "a"        
                );

                $sendStatus = EmailTemplates::SendEmail($mailData);

                $this->resultapi('1','Notification Send to the Related User.', true);
            }
            else
            {
                $this->resultapi('0','Problem into the send notification.', true); 
            }
        }
        else
        {
            $this->resultapi('0','Some required details not found.', true); 
        }
    }

     public function acceptCloseNotification(Request $request)
    {
        if($request->projId && $request->cId && $request->usertype )
        {   
            $status = JobDetail::acceptCloseNotification($request);
            if($status)
            {
                $this->resultapi('1','Notification Send to the Related User.', true);
            }
            else
            {
                $this->resultapi('0','Problem into the accept notification.', true); 
            }
        }
        else
        {
            $this->resultapi('0','Some required details not found', true); 
        }
    }    

    public function resultapi($status,$message,$result = array())
    {
        $finalArray['STATUS'] = $status;
        $finalArray['MESSAGE'] = $message;
        $finalArray['RESULT'] = $result;
        echo json_encode($finalArray);
        die;
    }
}