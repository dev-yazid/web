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
use App\Api\Transaction;

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

class ApiPaymentController  extends Controller {
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
    public function getUpdatePayment(Request $request){
        
        $user = User::find($request->user_id);
        if(count($user) > 0 )
        {
            $userType = $user->usertype;
            
            if($userType == "Freelancer" && $user->is_profile_updated == 1)
            {
                $payStatus = Payment::savePayment($request);
                if($payStatus)
                {            
                    $this->resultapi('1','Payment Details Added Sucessfully.', true);
                }
                else
                {
                    $this->resultapi('0','Failed to save payment details.', true);
                } 
            }
            else if($userType == "Client" && $user->is_client_profile_updated == 1 )
            {
                $payStatus = Payment::savePayment($request);
                if($payStatus)
                {            
                    $this->resultapi('1','Payment Details Added Sucessfully.', true);
                }
                else
                {
                    $this->resultapi('0','Failed to save payment details.', true);
                } 
            }
            else
            {
                $this->resultapi('0','You must have to update your Profile Details First.', true);
            }
        }
        else
        {
            $this->resultapi('0','User Details not found..', true);
        }
    }
   

    public function getPaymentProcess(Request $request){
        
        if($request->ProjId && $request->currUserId && $request->userId)
        {            

            $hireUsertatus = JobDetail::find($request->ProjId);

            if(count($hireUsertatus) > 0 )
            {
                if(!$hireUsertatus->hired_user_id)
                {
                    if($hireUsertatus->user_id !== $request->currUserId)
                    {
                        if($hireUsertatus->job_stage !== 'Finished' && $hireUsertatus->status === 'Active')
                        {
                            
                            $transStatus = 1; /* Live Transactions statis 0 or 1 */
                            $trsStatus = Transaction::UpdateTransactionDetails($request->currUserId,$request->userId,$request->ProjId, $transStatus);                            
                            
                            if($transStatus == 1)
                            {
                                $hireUsertatus->hired_user_id   = $request->userId;
                                $hireUsertatus->final_job_cost  = $request->finalPropCost; 
                                $hireUsertatus->job_stattime    = date('Y-m-d');
                                //$hireUsertatus->status          = 'InActive';
                               // $hireUsertatus->save();
                                if($hireUsertatus->save())
                                {
                                    $hireDesc = "You are Hired for a project.";

                                    $mailData = array(
                                        'clientId'          => $request->currUserId,
                                        'userId'            => $request->userId,
                                        'projectId'         => $request->ProjId,
                                        'inv_description'   => $hireDesc,
                                        'inv_status'        => "Pending",
                                        'inv_attachemnts'   => "",
                                        'template'          => 'hire-freelancer',
                                        'search'            => "a",
                                        'replace'           => "a"        
                                    );

                                    $sendStatus = EmailTemplates::SendEmail($mailData);
                                }

                                $this->resultapi('1','User Hired Sucessfully.', true);
                            }
                            else
                            {
                                $this->resultapi('0','Transaction failed.', true);
                            }
                        }
                        else
                        {
                            $this->resultapi('0','This Project is in Finished Or Inactive Stage.', true);
                        }
                    }
                    else
                    {
                        $this->resultapi('0','You Cannot Assign to Your Own Posted Project.', true);
                    }
                }
                else
                {
                    $this->resultapi('0','User already hired for this project.', true); 
                }
            }
            else
            {
                $this->resultapi('0','Hiring Details Not Found.', true); 
            }           
        }
        else
        {
            $this->resultapi('0','System Error Details Not found.', true); 
        }
           
    } 

    public function getReleasePayment(Request $request){

        if($request->projId)
        {
            $projDetails = JobDetail::find($request->projId);    
            if($projDetails->final_job_cost)
            {                
                if($projDetails->hired_user_id)
                {
                    if(!$projDetails->is_payment_relased)
                    {
                        if($projDetails->job_stage !== "Finished")
                        {
                            $projDetails->is_payment_relased = 1;
                            if($projDetails->save())
                            {
                                $hireDesc = "You are reecived your Pending payment, Please check your account.";

                                $mailData = array(
                                    'clientId'          => $projDetails->user_id,
                                    'userId'            => $projDetails->hired_user_id,
                                    'projectId'         => $projDetails->id,
                                    'inv_description'   => $hireDesc,
                                    'inv_status'        => "",
                                    'inv_attachemnts'   => "",
                                    'template'          => 'payment-released',
                                    'search'            => "a",
                                    'replace'           => "a"        
                                );

                                $sendStatus = EmailTemplates::SendEmail($mailData);

                                $this->resultapi('1','Payment Relased Sucessfully.', true);
                            }
                            else
                            {
                                $this->resultapi('0','Some Problem in release payment.', true);
                            }
                        }
                        else
                        {
                            $this->resultapi('0','Project Already Closed.', true);
                        }
                    }
                    else
                    {
                        $this->resultapi('0','You have Already released Amount For This Project.', true);
                    }
                }
                else
                {
                    $this->resultapi('0','Hired User Details Not Found.', true);
                }
            }
            else
            {
                $this->resultapi('0','Final Proposal Cost Not Decided.', true);
            }
        }
        else
        {
            $this->resultapi('0','Project Details Not Found.', false); 
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