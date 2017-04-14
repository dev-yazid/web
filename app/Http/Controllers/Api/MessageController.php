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
use App\Message;
use Auth;
use Mail;
use Form;
use File;
use Image;

class MessageController extends Controller
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

        $this->middleware('jwt.auth', ['except' => ['']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function sendMessage(Request $request) {
        if(Auth::check())
        {
            $validator = Validator::make($request->all(), [                
                'sender_id'       => 'required|numeric',
                'reeciver_id'     => 'required|numeric',
                'req_id'          => 'required|numeric',
                /*'res_id'          => 'required|numeric',   */            
                'description'     => 'required|max:255',
                'usertype'        => 'required',
                'file'            => 'mimes:jpeg,jpg,png,pdf|max:1024',           
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                /* for Shop Licence Image Upload */
                $filename = "";
                if($request->hasFile('file'))
                {           
                    $file = $request->file('file');
                    $path = public_path().'/asset/Messages/';
                    $thumbPath = public_path('/asset/Messages/thumb/');

                    $timestamp = time().  uniqid(); 
                    $filename = $timestamp.'_'.trim($file->getClientOriginalName());
                    $file->move($path,$filename);

                    $img = Image::make($path.$filename);
                    $img->resize(100, 100, function ($constraint) { 
                        $constraint->aspectRatio();
                    })->save($thumbPath.'/'.$filename);
                }

                $regNewMobile = new Message;                
                $regNewMobile->sender_id          = trim($request->sender_id);
                $regNewMobile->reeciver_id        = trim($request->reeciver_id);                
                /*$regNewMobile->req_id             = trim($request->req_id);  */              
                $regNewMobile->res_id             = trim($request->res_id);
                $regNewMobile->description        = trim($request->description);                
                $regNewMobile->usertype           = trim($request->usertype);
                $regNewMobile->read_status        = 0;
                $regNewMobile->attachment         = $filename;

                $currentMessage = array(
                        'message'    => $request->description,
                        'attachment' => $filename,
                        'imgpath'    => asset('/public/asset/Message/thumb'),
                    );

                if($regNewMobile->save())
                {
                    $this->resultapi('1','Message Send Sucessfully', $currentMessage);
                }
                else
                {   
                    $this->resultapi('0','Some Problem with Email Send.', $currentMessage);
                }                   
            }
        }
        else
        {            
            $this->resultapi('0','Authentication Failed.', 0);
        }
    }

    public function getAllMessages(Request $request) {
        if(Auth::check())
        {
            $validator = Validator::make($request->all(), [
                'uid'             => 'required|numeric',
                'req_id'          => 'required|numeric',
                'usertype'        => 'required',                         
            ]);
            
            if ($validator->fails()) 
            {
                $this->resultapi('0', $validator->errors()->all(), 0);
            }
            else
            {
                $allMessages = DB::table('messages')
                    ->where('usertype',$request->usertype)
                    ->where('req_id',$request->req_id)
                    ->Where(function ($query) use ($request){              
                        $query->where('reeciver_id',$request->uid)
                        ->orWhere('sender_id',$request->uid);
                        })
                    ->select('id','read_status','attachment','attachment','usertype','created_at')
                    ->get();

                foreach ($allMessages as $key => $value)
                {
                    //$allMessages[$key]->imgpath = asset('/public/asset/Message/thumb');
                    if($value->read_status == 0 )
                    {
                        $msgStatus = Message::find($value->id);
                        $msgStatus->read_status = 1;
                        $msgStatus->save();
                    }
                }
                $this->resultapi('1','Message Send Sucessfully', $allMessages);   
            }
            //print_r($allMessages);           
        }
        else
        {            
            $allMessages = array();
            $this->resultapi('0','Authentication Failed.', $allMessages);
        }
    }
    
    public function resultapi($status,$message,$result = array()) {

        $finalArray['STATUS']   = $status;
        $finalArray['MESSAGE']  = $message;
        $finalArray['DATA']     = $result;

        echo json_encode($finalArray);  
    }   
}
